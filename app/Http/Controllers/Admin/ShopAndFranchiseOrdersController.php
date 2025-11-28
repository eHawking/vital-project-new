<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FranchiseOrder;
use App\Models\Product;
use App\Models\Seller;
use App\Models\ShopOrder;
use App\Models\User;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShopAndFranchiseOrdersController extends Controller
{
    /**
     * Display a listing of shop orders with search.
     */
    public function shopOrders(Request $request)
    {
        $query = ShopOrder::query();
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('shop_name', 'like', "%{$searchTerm}%")
                ->orWhere('id', 'like', "%{$searchTerm}%");
        }
        $shopOrders = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());
        return view('admin-views.shop-orders.index', compact('shopOrders'));
    }

    /**
     * Display a listing of franchise orders with search.
     */
    public function franchiseOrders(Request $request)
    {
        $query = FranchiseOrder::query();
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('franchise_name', 'like', "%{$searchTerm}%")
                ->orWhere('id', 'like', "%{$searchTerm}%");
        }
        $franchiseOrders = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());
        return view('admin-views.franchise-orders.index', compact('franchiseOrders'));
    }

    /**
     * Approve a franchise order.
     */
    public function approveFranchiseOrder($id): RedirectResponse
    {
        $order = FranchiseOrder::findOrFail($id);
        return $this->processOrderApproval($order, 'franchise');
    }

    /**
     * Cancel a franchise order and process refund.
     */
    public function cancelFranchiseOrder($id): RedirectResponse
    {
        $order = FranchiseOrder::findOrFail($id);
        return $this->processOrderCancellation($order, 'franchise');
    }

    /**
     * Approve a shop order.
     */
    public function approveShopOrder($id): RedirectResponse
    {
        $order = ShopOrder::findOrFail($id);
        return $this->processOrderApproval($order, 'shop');
    }

    /**
     * Cancel a shop order and process refund.
     */
    public function cancelShopOrder($id): RedirectResponse
    {
        $order = ShopOrder::findOrFail($id);
        return $this->processOrderCancellation($order, 'shop');
    }

    /**
     * Process the approval for any order type (franchise or shop).
     */
    private function processOrderApproval($order, string $vendorType): RedirectResponse
    {
        DB::beginTransaction();
        try {
            if ($order->status == 'approved') {
                ToastMagic::error(translate('This order has already been approved.'));
                return redirect()->back();
            }

            $orderDetails = is_string($order->order_details) ? json_decode($order->order_details, true) : $order->order_details;

            if (empty($orderDetails) || !is_array($orderDetails)) {
                ToastMagic::error(translate('Order details are invalid.'));
                DB::rollBack();
                return redirect()->back();
            }

            foreach ($orderDetails as $item) {
                if (!isset($item['product_id'], $item['quantity'])) continue;

                $mainProduct = Product::find($item['product_id']);
                if (!$mainProduct) {
                    ToastMagic::error(translate('Product not found for ID: ') . $item['product_id']);
                    DB::rollBack();
                    return redirect()->back();
                }

                $variation = $item['variation'] ?? null;

                // Handle variation-based stock
                if ($variation && $mainProduct->variation) {
                    $productVariations = json_decode($mainProduct->variation, true);
                    $variationFound = false;
                    $updatedVariations = [];

                    foreach ($productVariations as &$var) {
                        if ($var['type'] == $variation) {
                            $variationFound = true;
                            
                            // Check stock
                            if (!isset($var['qty']) || $var['qty'] < $item['quantity']) {
                                ToastMagic::error(translate('Insufficient stock for variation: ') . $variation . ' in product: ' . $mainProduct->name);
                                DB::rollBack();
                                return redirect()->back();
                            }

                            // Deduct from admin variation stock
                            $var['qty'] -= $item['quantity'];
                        }
                        $updatedVariations[] = $var;
                    }

                    if (!$variationFound) {
                        ToastMagic::error(translate('Variation not found: ') . $variation);
                        DB::rollBack();
                        return redirect()->back();
                    }

                    // Update admin product variations
                    $mainProduct->variation = json_encode($updatedVariations);
                    $mainProduct->save();

                    // Handle vendor product with variation
                    $vendorProduct = Product::where('admin_id', $mainProduct->id)
                        ->where('user_id', $order->ordered_by)
                        ->first();

                    if ($vendorProduct) {
                        // Update existing vendor product variation
                        $vendorVariations = json_decode($vendorProduct->variation, true) ?: [];
                        $vendorVariationFound = false;

                        foreach ($vendorVariations as &$vendorVar) {
                            if ($vendorVar['type'] == $variation) {
                                $vendorVar['qty'] = ($vendorVar['qty'] ?? 0) + $item['quantity'];
                                $vendorVariationFound = true;
                                break;
                            }
                        }

                        // If variation doesn't exist in vendor product, add it
                        if (!$vendorVariationFound) {
                            foreach ($productVariations as $var) {
                                if ($var['type'] == $variation) {
                                    $vendorVariations[] = [
                                        'type' => $var['type'],
                                        'price' => $var['price'],
                                        'sku' => $var['sku'] ?? null,
                                        'qty' => $item['quantity'],
                                    ];
                                    break;
                                }
                            }
                        }

                        $vendorProduct->variation = json_encode($vendorVariations);
                        $vendorProduct->increment('current_stock', $item['quantity']);
                        $vendorProduct->save();
                    } else {
                        // Create new vendor product with variation
                        $newProduct = $mainProduct->replicate();
                        $newProduct->added_by = 'seller';
                        $newProduct->vendor_type = $vendorType;
                        $newProduct->admin_id = $mainProduct->id;
                        $newProduct->user_id = $order->ordered_by;
                        $newProduct->current_stock = $item['quantity'];
                        $newProduct->status = 1;

                        // Set only the purchased variation
                        $newVariations = [];
                        foreach ($productVariations as $var) {
                            if ($var['type'] == $variation) {
                                $newVariations[] = [
                                    'type' => $var['type'],
                                    'price' => $var['price'],
                                    'sku' => $var['sku'] ?? null,
                                    'qty' => $item['quantity'],
                                ];
                                break;
                            }
                        }
                        $newProduct->variation = json_encode($newVariations);
                        $newProduct->save();
                    }
                } else {
                    // Handle regular product without variation
                    if ($mainProduct->current_stock < $item['quantity']) {
                        ToastMagic::error(translate('Insufficient stock for product: ') . $mainProduct->name);
                        DB::rollBack();
                        return redirect()->back();
                    }

                    $mainProduct->decrement('current_stock', $item['quantity']);

                    $vendorProduct = Product::where('admin_id', $mainProduct->id)
                        ->where('user_id', $order->ordered_by)
                        ->first();

                    if ($vendorProduct) {
                        $vendorProduct->increment('current_stock', $item['quantity']);
                    } else {
                        $newProduct = $mainProduct->replicate();
                        $newProduct->added_by = 'seller';
                        $newProduct->vendor_type = $vendorType;
                        $newProduct->admin_id = $mainProduct->id;
                        $newProduct->user_id = $order->ordered_by;
                        $newProduct->current_stock = $item['quantity'];
                        $newProduct->status = 1;
                        $newProduct->save();
                    }
                }
            }

            $order->status = 'approved';
            $order->payment_status = 'paid';
            $order->save();

            DB::commit();
            ToastMagic::success(ucfirst($vendorType) . translate(' order approved successfully.'));
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(ucfirst($vendorType) . ' order approval failed: ' . $e->getMessage());
            ToastMagic::error(translate('Failed to approve order. Please try again.'));
            return redirect()->back();
        }
    }

    /**
     * Process the cancellation and refund for any order type (franchise or shop).
     */
    private function processOrderCancellation($order, string $vendorType): RedirectResponse
    {
        if ($order->status == 'approved') {
            ToastMagic::error(translate('Cannot cancel an order that has already been approved.'));
            return redirect()->back();
        }

        if ($order->status == 'cancelled') {
            ToastMagic::info(translate('This order has already been cancelled.'));
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $seller = Seller::find($order->ordered_by);
            if (!$seller) {
                throw new \Exception('Seller not found for this order.');
            }
            $user = User::where('username', $seller->username)->first();
            if (!$user) {
                throw new \Exception('User wallet account not found for this seller.');
            }

            // Refund the amount to the user's wallet
            $refundAmount = $order->order_amount;
            $user->increment('wallet_balance', $refundAmount);

            // Create wallet transaction records
            $transactionId = Str::uuid()->toString();
            $transactionType = $vendorType . '_order_refund';
            $remark = 'order_refund';
            $details = "Refund for cancelled {$vendorType} Order ID: #{$order->id}";

            // Main wallet transaction (credit)
            DB::table('wallet_transactions')->insert([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'credit' => $refundAmount,
                'debit' => 0,
                'admin_bonus' => 0,
                'balance' => $user->wallet_balance,
                'transaction_type' => $transactionType,
                'payment_method' => 'wallet_refund',
                'reference' => $order->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Secondary DB transaction
            $secondaryUser = DB::connection('mysql2')->table('users')->where('username', $seller->username)->first();
            if ($secondaryUser) {
                DB::connection('mysql2')->table('transactions')->insert([
                    'user_id' => $secondaryUser->id,
                    'amount' => $refundAmount,
                    'charge' => 0,
                    'post_balance' => $user->wallet_balance,
                    'trx_type' => '+',
                    'trx' => $transactionId,
                    'remark' => $remark,
                    'details' => $details,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update order status
            $order->status = 'cancelled';
            $order->payment_status = 'refunded';
            $order->save();

            DB::commit();
            ToastMagic::success(ucfirst($vendorType) . translate(' order cancelled and amount refunded successfully.'));
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(ucfirst($vendorType) . ' order cancellation failed: ' . $e->getMessage());
            ToastMagic::error(translate('Failed to cancel order. Please try again.'));
            return redirect()->back();
        }
    }
}