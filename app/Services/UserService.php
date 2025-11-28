<?php

namespace App\Services;

use App\Models\SecondaryUser;
use App\Models\CompanyPartner;
use App\Models\ProductPartner;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\RoyaltyBonus;
use App\Models\Seller;
use App\Models\WalletsHistory;
use App\Models\User;
use App\Models\BonusWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserService
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function updateSecondaryUserBonuses(
        string $customerUsername,
        string $sellerUsername,
        string $sellerType,
        float $bv,
        float $pv,
        float $ddsRefBonus,
        float $shopBonus,
        float $shopReference,
        float $franchiseBonus,
        float $franchiseRefBonus,
        float $companyPartnerBonus,
        float $productPartnerBonus,
        float $royaltyBonus,
        float $cityRefBonus,
        array $cartItems,
        array $totals
    ): void {
        $distributed = array_fill_keys([
            'bv', 'pv', 'dds_ref_bonus', 'shop_bonus', 'shop_reference',
            'franchise_bonus', 'franchise_ref_bonus', 'city_ref_bonus',
            'company_partner_bonus', 'product_partner_bonus', 'royalty_bonus'
        ], 0);

        // Customer updates
        $customer = SecondaryUser::where('username', $customerUsername)->first();
        if ($customer) {
            $customer->bv += $bv;
			$customer->pv += $pv;
            $customer->balance += $bv;
            $customer->save();
            $distributed['bv'] += $bv;
            $distributed['pv'] += $pv;

            // Create transactions for BV and PV
            if ($bv > 0) {
                $this->createBonusTransaction($customer, 'bv', $bv, compact('customerUsername', 'sellerUsername', 'cartItems'));
            }
            if ($pv > 0) {
                $this->createBonusTransaction($customer, 'pv', $pv, compact('customerUsername', 'sellerUsername', 'cartItems'));
            }
        }

        // Seller commission updates
        $sellerUser = SecondaryUser::where('username', $sellerUsername)->first();
        if ($sellerUser) {
            if ($sellerType === 'shop' && $shopBonus > 0) {
                $sellerUser->shop_bonus += $shopBonus;
                $sellerUser->balance += $shopBonus;
                $sellerUser->save();
                $distributed['shop_bonus'] += $shopBonus;
                $this->createBonusTransaction($sellerUser, 'shop_bonus', $shopBonus, compact('customerUsername', 'sellerUsername', 'cartItems'));
            }
            if ($sellerType === 'franchise' && $franchiseBonus > 0) {
                $sellerUser->franchise_bonus += $franchiseBonus;
                $sellerUser->balance += $franchiseBonus;
                $sellerUser->save();
                $distributed['franchise_bonus'] += $franchiseBonus;
                $this->createBonusTransaction($sellerUser, 'franchise_bonus', $franchiseBonus, compact('customerUsername', 'sellerUsername', 'cartItems'));
            }
        }

         // Referral bonuses (for dds_ref_bonus only)
    if ($customer && $customer->ref_by) {
        $referrer = SecondaryUser::find($customer->ref_by);
        if ($referrer) {
            $isDSP = str_starts_with(strtolower($referrer->username), 'dsp');
            $target = $isDSP && $referrer->ref_by 
                ? SecondaryUser::find($referrer->ref_by) 
                : $referrer;

            if ($target) {
                if ($ddsRefBonus > 0) {
                    $target->dds_ref_bonus += $ddsRefBonus;
                    $target->balance += $ddsRefBonus;
                    $target->save();
                    $distributed['dds_ref_bonus'] += $ddsRefBonus;
                    $this->createBonusTransaction($target, 'dds_ref_bonus', $ddsRefBonus, compact('customerUsername', 'sellerUsername', 'cartItems'));
                }
            }
        }
    }

    // New logic: Seller's referral bonuses (shop_reference & franchise_ref_bonus)
    if ($sellerUser && $sellerUser->ref_by) {
        $sellerReferrer = SecondaryUser::find($sellerUser->ref_by);
        if ($sellerReferrer) {
            $isDSP = str_starts_with(strtolower($sellerReferrer->username), 'dsp');
            $target = $isDSP && $sellerReferrer->ref_by 
                ? SecondaryUser::find($sellerReferrer->ref_by) 
                : $sellerReferrer;

            if ($target) {
                if ($sellerType === 'shop' && $shopReference > 0) {
                    $target->shop_reference += $shopReference;
                    $target->balance += $shopReference;
                    $target->save();
                    $distributed['shop_reference'] += $shopReference;
                    $this->createBonusTransaction($target, 'shop_reference', $shopReference, compact('customerUsername', 'sellerUsername', 'cartItems'));
                } elseif ($sellerType === 'franchise' && $franchiseRefBonus > 0) {
                    $target->franchise_ref_bonus += $franchiseRefBonus;
                    $target->balance += $franchiseRefBonus;
                    $target->save();
                    $distributed['franchise_ref_bonus'] += $franchiseRefBonus;
                    $this->createBonusTransaction($target, 'franchise_ref_bonus', $franchiseRefBonus, compact('customerUsername', 'sellerUsername', 'cartItems'));
                }
            }
        }
    }


        // Company Partner Bonus
        if ($companyPartnerBonus > 0) {
            $partners = CompanyPartner::all();
            $totalOwnership = $partners->sum('ownership_share');

            foreach ($partners as $partner) {
                $share = ($companyPartnerBonus * $partner->ownership_share) / 100;
                if ($share > 0) {
                    $partner->company_partner_bonus += $share;
                    $partner->balance += $share;
                    $partner->save();
                    $this->createAdminBonusTransaction(
                        $partner->id,
                        'company_partner_bonus',
                        $share,
                        compact('customerUsername', 'sellerUsername', 'cartItems')
                    );
                }
            }
            $distributed['company_partner_bonus'] += $companyPartnerBonus;
        }

        // Product Partner Bonus
        foreach ($cartItems as $item) {
            if (!is_array($item)) continue;
            $product = $this->productRepo->getFirstWhere(['id' => $item['id']]);
            if (!$product) continue;
            $quantity = $item['quantity'] ?? 1;
            $bonusPerProduct = $product->product_partner_bonus * $quantity;

            $partners = ProductPartner::whereJsonContains('product_details', ['id' => $product->id])->get();
            $partnerCount = $partners->count();

            if ($partnerCount > 0) {
                $share = $bonusPerProduct / $partnerCount;
                foreach ($partners as $partner) {
                    $partner->product_partner_bonus += $share;
                    $partner->balance += $share;
                    $partner->save();
                    $this->createBonusTransaction($partner, 'product_partner_bonus', $share, compact('customerUsername', 'sellerUsername', 'cartItems'));
                }
                $distributed['product_partner_bonus'] += $bonusPerProduct;
            }
        }

        // Royalty Bonus
        if ($royaltyBonus > 0) {
            $royalty = RoyaltyBonus::find(1) ?? new RoyaltyBonus(['id' => 1]);
            $royalty->current_royalty_bonus += $royaltyBonus;
            $royalty->save();
            $distributed['royalty_bonus'] += $royaltyBonus;
			
			$this->createAdminBonusTransaction(
                'Company',
                'royalty_bonus',
                $royaltyBonus,
                compact('customerUsername', 'sellerUsername', 'cartItems')
            );
        }

        // City Referral Bonus
        $loggedInSeller = auth('seller')->user();
        if ($cityRefBonus > 0) {
            if ($sellerType === 'shop') {
                $franchiseSeller = Seller::find($loggedInSeller->reference_id);
                if ($franchiseSeller) {
                    $franchiseUser = SecondaryUser::where('username', $franchiseSeller->username)->first();
                    if ($franchiseUser) {
                        $franchiseUser->city_ref_bonus += $cityRefBonus;
                        $franchiseUser->balance += $cityRefBonus;
                        $franchiseUser->save();
                        $distributed['city_ref_bonus'] += $cityRefBonus;
                        $this->createBonusTransaction($franchiseUser, 'city_ref_bonus', $cityRefBonus, compact('customerUsername', 'sellerUsername', 'cartItems'));
                    }
                }
            } elseif ($sellerType === 'franchise') {
                $ddsUser = SecondaryUser::where('username', 'dds0001000')->first();
                if ($ddsUser) {
                    $ddsUser->city_ref_bonus += $cityRefBonus;
                    $ddsUser->balance += $cityRefBonus;
                    $ddsUser->save();
                    $distributed['city_ref_bonus'] += $cityRefBonus;
                    $this->createBonusTransaction($ddsUser, 'city_ref_bonus', $cityRefBonus, compact('customerUsername', 'sellerUsername', 'cartItems'));
                }
            }
        }

        $this->logBonusDistribution($customerUsername, $totals, $cartItems, Auth::guard('seller')->user(), $distributed);

        // Deduct from Bonus Wallet
        $wallet = BonusWallet::find(1) ?? new BonusWallet(['id' => 1]);
        foreach ($distributed as $field => $amount) {
            if ($amount > 0 && isset($wallet->$field)) {
                $wallet->$field = max($wallet->$field - $amount, 0);
            }
        }
        $wallet->save();
    }

    private function logBonusDistribution(
    string $customerUsername,
    array $totals,
    array $cartItems,
    Seller $seller,
    array $distributedBonuses
): void {
    $customer = User::where('username', $customerUsername)->first();
    $vendor = $seller;
    $products = [];

    foreach ($cartItems as $item) {
        if (!is_array($item)) continue;
        $product = $this->productRepo->getFirstWhere(['id' => $item['id']]);
        if ($product) {
            // Add the product's budget to the JSON data
            $products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $item['quantity'] ?? 1,
                'budget' => $product->budget ?? 0 
            ];
        }
    }

    $bonusFields = [
        'bv', 'pv', 'dds_ref_bonus', 'shop_bonus', 'shop_reference',
        'franchise_bonus', 'franchise_ref_bonus', 'city_ref_bonus',
        'leadership_bonus', 'promo', 'user_promo', 'seller_promo',
        'shipping_expense', 'bilty_expense', 'office_expense',
        'event_expense', 'fuel_expense', 'visit_expense',
        'company_partner_bonus', 'product_partner_bonus',
        'budget_promo', 'product_partner_ref_bonus',
        'vendor_ref_bonus', 'royalty_bonus'
    ];

    $bonusLog = [];
    foreach ($bonusFields as $field) {
        $bonusLog[$field] = [
            'distributed' => $distributedBonuses[$field] ?? 0,
            'remaining' => $totals[$field] - ($distributedBonuses[$field] ?? 0)
        ];
    }

    $type = ($vendor->vendor_type === 'shop' ? 'Shop (POS)' : ($vendor->vendor_type === 'franchise' ? 'Franchise (POS)' : 'N/A'));

    WalletsHistory::create([
        'username' => $customerUsername,
        'customer_name' => $customer?->f_name . ' ' . $customer?->l_name,
        'type' => $type,
        'vendor_id' => $vendor->vendor_id,
        'vendor_username' => $vendor->username,
        'vendor_name' => $vendor->f_name . ' ' . $vendor->l_name,
        'shop_name' => $vendor->shop->name ?? '',
        'city' => $vendor->city ?? '',
        'products' => $products,
        'bonus_types' => $bonusLog,
        'created_at' => now()
    ]);
}
	
  private function createBonusTransaction($user, string $bonusType, float $amount, array $data): void
{
    $bonusRecord = DB::connection('mysql')->table('bonus_management')
        ->where('bonus_type', $bonusType)
        ->first();

    if (!$bonusRecord || empty($bonusRecord->statement)) return;

    // Initialize variables
    $customerName = '';
    $customerUsername = '';
    $customerRefName = '';
    $customerRefUsername = '';
    $shopRefName = '';
    $shopRefUsername = '';
    $franchiseRefName = '';
    $franchiseRefUsername = '';
    $cityRefName = '';
    $cityRefUsername = '';
    $vendorName = '';
    $vendorUsername = '';
    $vendorId = '';
    $shopName = '';
    $city = '';
    $productNames = [];
    $totalQty = 0;

    // Fetch seller details
    if (!empty($data['sellerUsername'])) {
        $seller = Seller::where('username', $data['sellerUsername'])->first();
        if ($seller) {
            $vendorName = $seller->f_name . ' ' . $seller->l_name;
            $vendorUsername = $seller->username;
            $vendorId = $seller->vendor_id;
            $shopName = $seller->shop?->name ?? '';
            $city = $seller->city ?? '';
        }
    }

    // Fetch customer details
    if (!empty($data['customerUsername'])) {
        $customer = SecondaryUser::where('username', $data['customerUsername'])->first();
        if ($customer) {
            $customerName = $customer->f_name . ' ' . $customer->l_name;
            $customerUsername = $customer->username;

            if ($customer->ref_by) {
                $referrer = SecondaryUser::find($customer->ref_by);
                if ($referrer) {
                    $customerRefName = $referrer->f_name . ' ' . $referrer->l_name;
                    $customerRefUsername = $referrer->username;
                }
            }
        }
    }

    // Extract product names and total quantity
    if (!empty($data['cartItems']) && is_array($data['cartItems'])) {
        foreach ($data['cartItems'] as $item) {
            if (!is_array($item)) continue;
            $product = $this->productRepo->getFirstWhere(['id' => $item['id']]);
            if ($product) {
                $productNames[] = $product->name;
                $totalQty += $item['quantity'] ?? 1;
            }
        }
    }

    // Attempt to infer shop/franchise referrer info (context-dependent)
    if ($bonusType === 'shop_reference') {
        // In referral context, $user is likely the shop referrer
        if ($user instanceof SecondaryUser) {
            $shopRefName = $user->f_name . ' ' . $user->l_name;
            $shopRefUsername = $user->username;
        }
    }

    if ($bonusType === 'franchise_ref_bonus') {
        // In referral context, $user is likely the franchise referrer
        if ($user instanceof SecondaryUser) {
            $franchiseRefName = $user->f_name . ' ' . $user->l_name;
            $franchiseRefUsername = $user->username;
        }
    }

    if ($bonusType === 'city_ref_bonus') {
        // $user is likely the city referrer
        if ($user instanceof SecondaryUser) {
            $cityRefName = $user->f_name . ' ' . $user->l_name;
            $cityRefUsername = $user->username;
        }
    }

    // Build replacements
    $replacements = [
        '{username}' => $user->username ?? '',
        '{customer_name}' => $customerName,
        '{customer_username}' => $customerUsername,
        '{customer_ref_name}' => $customerRefName,
        '{customer_ref_username}' => $customerRefUsername,
        '{shop_ref_name}' => $shopRefName,
        '{shop_ref_username}' => $shopRefUsername,
        '{franchise_ref_name}' => $franchiseRefName,
        '{franchise_ref_username}' => $franchiseRefUsername,
        '{city_ref_name}' => $cityRefName,
        '{city_ref_username}' => $cityRefUsername,
        '{vendor_username}' => $vendorUsername,
        '{vendor_name}' => $vendorName,
        '{vendor_id}' => $vendorId,
        '{shop_name}' => $shopName,
        '{city}' => $city,
        '{products}' => implode(', ', $productNames),
        '{qty}' => (string)$totalQty,
        '{amount}' => number_format($amount, 2),
        '{date}' => now()->toDateString(),
        '{month_name}' => now()->format('F'),
        '{year}' => now()->year,
    ];

    // Replace tags in statement
    $details = $bonusRecord->statement;
    foreach ($replacements as $tag => $value) {
        $details = str_replace($tag, $value, $details);
    }

    // Generate transaction ID and insert
    $trx = $this->generateTransactionId();
    DB::connection('mysql2')->table('transactions')->insert([
        'user_id' => $user->id,
        'amount' => $amount,
        'trx_type' => '+',
        'trx' => $trx,
        'remark' => $bonusType,
        'details' => $details,
        'created_at' => now()
    ]);
}
	
	private function createAdminBonusTransaction(string $userId, string $bonusType, float $amount, array $data): void
{
    $bonusRecord = DB::connection('mysql')->table('bonus_management')
        ->where('bonus_type', $bonusType)
        ->first();

    if (!$bonusRecord || empty($bonusRecord->statement)) return;

    // Initialize partner-related fields
    $companyPartnerName = '';
    $companyPartnerUsername = '';
    $productPartnerName = '';
    $productPartnerUsername = '';

    // Try to fetch company partner details
    if ($bonusType === 'company_partner_bonus') {
        $partner = CompanyPartner::find($userId);
        if ($partner) {
            $companyPartnerName = $partner->name ?? '';
            $companyPartnerUsername = $partner->username ?? '';
        }
    }

    // Try to fetch product partner details (if $userId is a ProductPartner ID)
    if ($bonusType === 'product_partner_bonus') {
        $partner = ProductPartner::find($userId);
        if ($partner) {
            $productPartnerName = $partner->name ?? '';
            $productPartnerUsername = $partner->username ?? '';
        }
    }

    // Fetch customer and seller info
    $customerName = '';
    $customerUsername = '';
    if (!empty($data['customerUsername'])) {
        $customer = SecondaryUser::where('username', $data['customerUsername'])->first();
        if ($customer) {
            $customerName = $customer->f_name . ' ' . $customer->l_name;
            $customerUsername = $customer->username;
        }
    }

    $vendorUsername = $data['sellerUsername'] ?? '';

    // Build replacements
    $replacements = [
        '{username}' => $userId,
        '{customer_name}' => $customerName,
        '{customer_username}' => $customerUsername,
        '{vendor_username}' => $vendorUsername,
        '{company_partner_name}' => $companyPartnerName,
        '{company_partner_username}' => $companyPartnerUsername,
        '{product_partner_name}' => $productPartnerName,
        '{product_partner_username}' => $productPartnerUsername,
        '{amount}' => number_format($amount, 2),
        '{date}' => now()->toDateString(),
        '{month_name}' => now()->format('F'),
        '{year}' => now()->year,
    ];

    // Replace tags in statement
    $details = $bonusRecord->statement;
    foreach ($replacements as $tag => $value) {
        $details = str_replace($tag, $value, $details);
    }

    // Generate transaction ID and insert
    $trx = $this->generateTransactionId();
    DB::connection('mysql2')->table('admin_transactions')->insert([
        'user_id' => $userId,
        'amount' => $amount,
        'trx_type' => '+',
        'remark' => $bonusType,
        'details' => $details,
        'created_at' => now()
    ]);
}
    
	private function generateTransactionId($length = 12)
{
    $characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
}