<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\FranchiseOrder;
use App\Models\ShopOrder;
use App\Models\User;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BuyProductsController extends Controller
{
    /**
     * Display the list of products for buying.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::query()
            ->where('status', 1) // Fetch only active products
            ->where('added_by', 'admin') // Filter for products added by 'admin'
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->view('vendor-views.buy-products.partials.product-list', compact('products'));
        }

        return view('vendor-views.buy-products.index', compact('products'));
    }

    /**
     * Handle creating an order.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'variation' => 'nullable|array',
            'variation.*' => 'nullable|string',
        ], [
            'product_id.required' => 'Please add at least one product to your cart.',
            'quantity.*.min' => 'Product quantity must be at least 1.',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $seller = Auth::guard('seller')->user();
        if (!in_array($seller->vendor_type, ['franchise', 'shop'])) {
            ToastMagic::error(translate('Only franchise or shop vendors can place orders.'));
            return redirect()->back();
        }

        $user = User::where('username', $seller->username)->first();
        if (!$user) {
            ToastMagic::error(translate('User not found.'));
            return redirect()->back();
        }

        $productIds = $request->input('product_id');
        $quantities = $request->input('quantity');
        $variations = $request->input('variation', []);
        
        $products = Product::find($productIds);

        $totalAmount = 0;
        $orderDetails = [];

        foreach ($products as $index => $product) {
            $productIndex = array_search($product->id, $productIds);
            $quantity = $quantities[$productIndex];
            $selectedVariation = $variations[$productIndex] ?? null;
            
            // Get price from variation if selected, otherwise use base price
            $unitPrice = $product->unit_price;
            $variationType = null;
            $variationSku = null;
            
            if ($selectedVariation && $product->variation) {
                $productVariations = json_decode($product->variation, true);
                foreach ($productVariations as $var) {
                    if ($var['type'] == $selectedVariation) {
                        $unitPrice = $var['price'];
                        $variationType = $var['type'];
                        $variationSku = $var['sku'] ?? null;
                        
                        // Check variation stock
                        if (isset($var['qty']) && $var['qty'] < $quantity) {
                            ToastMagic::error(translate('Insufficient stock for variation: ') . $variationType);
                            return redirect()->back();
                        }
                        break;
                    }
                }
            }
            
            // Calculate final price with discount
            $discount = $product->discount ?? 0;
            $discountType = $product->discount_type ?? 'amount';
            
            if ($discount > 0) {
                if ($discountType == 'percent') {
                    $finalPrice = $unitPrice - ($unitPrice * $discount / 100);
                } else {
                    $finalPrice = $unitPrice - $discount;
                }
                $finalPrice = max(0, $finalPrice); // Ensure non-negative
            } else {
                $finalPrice = $unitPrice;
            }
            
            $subtotal = $finalPrice * $quantity;
            $totalAmount += $subtotal;

            $orderDetails[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $finalPrice, // Store discounted price
                'original_price' => $unitPrice,
                'discount' => $discount,
                'discount_type' => $discountType,
                'subtotal' => $subtotal,
                'variation' => $variationType,
                'variation_sku' => $variationSku,
            ];
        }

        if ($user->wallet_balance < $totalAmount) {
            ToastMagic::error(translate('Insufficient wallet balance.'));
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $user->decrement('wallet_balance', $totalAmount);

            // Prepare the base order data without order_details
            $orderData = [
                'ordered_by' => $seller->id,
                'order_amount' => $totalAmount,
                'payment_status' => 'paid',
                'status' => 'pending',
            ];

            if ($seller->vendor_type == 'franchise') {
                $orderData['franchise_name'] = $seller->shop?->name ?? 'N/A';
                // For FranchiseOrder, encode to JSON string as it has no cast
                $orderData['order_details'] = json_encode($orderDetails);
                $order = FranchiseOrder::create($orderData);
            } else {
                $orderData['shop_name'] = $seller->shop?->name ?? 'N/A';
                // For ShopOrder, pass the raw PHP array and let the model's cast handle encoding
                $orderData['order_details'] = $orderDetails;
                $order = ShopOrder::create($orderData);
            }

            $transactionId = Str::uuid()->toString();
            $vendorType = ucfirst($seller->vendor_type);

            // --- Smart Alternative for Transaction Details ---
            $totalItemCount = array_sum($request->input('quantity'));
            $distinctProductCount = count($products);

            // Use Str::plural() for correct grammar and format the summary string
            $productSummary = sprintf(
                '%d %s and total Quantity %d %s',
                $distinctProductCount,
                Str::plural('Product', $distinctProductCount),
                $totalItemCount,
                Str::plural('item', $totalItemCount)
            );
            
            $remark = "{$vendorType} Order";
            $details = "{$seller->f_name} {$seller->l_name} purchased {$productSummary} from Dewdropskin for shop: {$seller->shop->name}. Order ID: #{$order->id}";

            // Main wallet transaction
            DB::table('wallet_transactions')->insert([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'credit' => 0,
                'debit' => $totalAmount,
                'admin_bonus' => 0,
                'balance' => $user->wallet_balance,
                'transaction_type' => $seller->vendor_type == 'franchise' ? 'franchise_order_payment' : 'shop_order_payment',
                'payment_method' => 'wallet',
                'reference' => $order->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Secondary DB transaction
            $secondaryUser = DB::connection('mysql2')->table('users')->where('username', $seller->username)->first();
            if ($secondaryUser) {
                DB::connection('mysql2')->table('transactions')->insert([
                    'user_id' => $secondaryUser->id,
                    'amount' => $totalAmount,
                    'charge' => 0,
                    'post_balance' => $user->wallet_balance,
                    'trx_type' => '-',
                    'trx' => $transactionId,
                    'remark' => $remark,
                    'details' => $details,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            ToastMagic::success(translate('Order placed successfully!'));
            return redirect()->route('vendor.order.history');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the exception for debugging
            Log::error('Order creation failed: ' . $e->getMessage());
            ToastMagic::error(translate('Order failed. Please try again.'));
            return redirect()->back();
        }
    }
}