<?php

namespace App\Services;

use App\Models\BonusWallet;
use App\Contracts\Repositories\ProductRepositoryInterface;

class BonusService
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function calculateBonuses(array $cartItems, string $sellerType): array
{
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

        $totals = array_fill_keys($bonusFields, 0);

        foreach ($cartItems as $item) {
            if (!is_array($item)) continue;

            $product = $this->productRepo->getFirstWhere(['id' => $item['id']]);
            if (!$product) continue;

            $quantity = $item['quantity'] ?? 1;

            foreach ($bonusFields as $field) {
                $totals[$field] += ($product->$field ?? 0) * $quantity;
            }
        }
		
        return $totals;
    }

    public function updateBonusWallet(array $totals): void
    {
        $wallet = BonusWallet::find(1) ?? new BonusWallet(['id' => 1]);
        
        foreach ($totals as $field => $value) {
            $wallet->$field = ($wallet->$field ?? 0) + $value;
        }

        $wallet->save();
    }
}