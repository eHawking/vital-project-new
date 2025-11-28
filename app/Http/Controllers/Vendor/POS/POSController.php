<?php

namespace App\Http\Controllers\Vendor\POS;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\DeliveryZipCodeRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\SessionKey;
use App\Enums\ViewPaths\Vendor\POS;
use App\Http\Controllers\BaseController;
use App\Services\CartService;
use App\Services\POSService;
use App\Traits\CalculatorTrait;
use App\Traits\CommonTrait;
use App\Traits\CustomerTrait;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class POSController extends BaseController
{
    use CalculatorTrait, CommonTrait, CustomerTrait;

    /**
     * @param VendorRepositoryInterface $vendorRepo
     * @param CategoryRepositoryInterface $categoryRepo
     * @param ProductRepositoryInterface $productRepo
     * @param CustomerRepositoryInterface $customerRepo
     * @param ShopRepositoryInterface $shopRepo
     * @param CouponRepositoryInterface $couponRepo
     * @param OrderRepositoryInterface $orderRepo
     * @param CartService $cartService
     * @param POSService $POSService
     * @param DeliveryZipCodeRepositoryInterface $deliveryZipCodeRepo
     */
    public function __construct(
        private readonly VendorRepositoryInterface          $vendorRepo,
        private readonly CategoryRepositoryInterface        $categoryRepo,
        private readonly ProductRepositoryInterface         $productRepo,
        private readonly CustomerRepositoryInterface        $customerRepo,
        private readonly ShopRepositoryInterface            $shopRepo,
        private readonly CouponRepositoryInterface          $couponRepo,
        private readonly OrderRepositoryInterface           $orderRepo,
        private readonly CartService                        $cartService,
        private readonly POSService                         $POSService,
        private readonly DeliveryZipCodeRepositoryInterface $deliveryZipCodeRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|JsonResponse|null
     */
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse|JsonResponse
    {
        return $this->getPOSView(request: $request);
    }

    /**
     * @param object $request
     * @return View|JsonResponse
     */
    public function getPOSView(object $request): View|JsonResponse
    {
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params: ['id' => $vendorId]);
        $getPOSStatus = getWebConfig('seller_pos');
        if ($vendor['pos_status'] == 0 || $getPOSStatus == 0) {
            ToastMagic::warning(translate('access_denied!!'));
        }

        $shop = $this->shopRepo->getFirstWhere(params: ['id' => $vendorId]);
        $categoryId = $request['category_id'];
        $categories = $this->categoryRepo->getListWhere(orderBy: ['id' => 'desc'], filters: ['position' => 0], dataLimit: 'all');
        $searchValue = $request['searchValue'] ?? null;
        $products = $this->productRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue: $searchValue,
            filters: [
                'added_by' => 'seller',
                'seller_id' => $vendorId,
                'category_id' => $categoryId,
                'code' => $searchValue,
                'status' => 1,
                'in_stock' => true,  // Only show in-stock products
            ],
            relations: ['clearanceSale' => function ($query) {
                return $query->active();
            }],
            dataLimit: getWebConfig('pagination_limit'),
        );
        
        // If AJAX request, return only the product list partial
        if ($request->ajax() || $request->has('ajax')) {
            try {
                \Log::info('POS AJAX Request detected', [
                    'ajax' => $request->ajax(),
                    'has_ajax_param' => $request->has('ajax'),
                    'products_count' => $products->count(),
                    'current_page' => $products->currentPage(),
                    'total' => $products->total()
                ]);
                
                $html = view('vendor-views.pos.partials._product-list', compact('products'))->render();
                
                \Log::info('AJAX HTML generated', ['length' => strlen($html)]);
                
                // Return JsonResponse with HTML content
                return response()->json([
                    'success' => true,
                    'html' => $html
                ]);
            } catch (\Exception $e) {
                \Log::error('POS AJAX Error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        
        // No longer auto-create walking customer - require customer selection
        $cartId = session(SessionKey::CURRENT_USER);
        if (!$cartId) {
            // Start with empty cart, customer must be selected first
            $cartId = 'temp-cart-' . rand(10, 1000);
            session([SessionKey::CURRENT_USER => $cartId]);
        }
        $customers = $this->customerRepo->getListWhereNotIn(ids: [0]);
        $getCurrentCustomerData = $this->getCustomerDataFromSessionForPOS();
        $summaryData = array_merge($this->POSService->getSummaryData(), $getCurrentCustomerData);
        $cartItems = $this->getCartData(cartName: session(SessionKey::CURRENT_USER));
        $order = $this->orderRepo->getFirstWhere(params: ['id' => session(SessionKey::LAST_ORDER)]);
        $totalHoldOrder = $summaryData['totalHoldOrders'];
        $countries = getWebConfig(name: 'delivery_country_restriction') ? $this->get_delivery_country_array() : COUNTRIES;
        $zipCodes = getWebConfig(name: 'delivery_zip_code_area_restriction') ? $this->deliveryZipCodeRepo->getListWhere(dataLimit: 'all') : 0;
        return view(POS::INDEX[VIEW], compact(
            'categories',
            'categoryId',
            'products',
            'cartId',
            'customers',
            'shop',
            'searchValue',
            'summaryData',
            'cartItems',
            'order',
            'totalHoldOrder',
            'countries',
            'zipCodes'
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeCustomer(Request $request): JsonResponse
    {
        // Only allow registered customers, no walking customer
        if ($request['user_id'] == 0) {
            return response()->json([
                'error' => true,
                'message' => translate('Please select a registered customer to continue')
            ], 400);
        }
        
        $cartId = 'saved-customer-' . $request['user_id'];
        $this->POSService->UpdateSessionWhenCustomerChange(cartId: $cartId);
        $getCurrentCustomerData = $this->getCustomerDataFromSessionForPOS();
        $summaryData = array_merge($this->POSService->getSummaryData(), $getCurrentCustomerData);
        $cartItems = $this->getCartData(cartName: $cartId);

        return response()->json([
            'view' => view(POS::SUMMARY[VIEW], compact('summaryData', 'cartItems'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDiscount(Request $request): JsonResponse
    {
        $cartId = session(SessionKey::CURRENT_USER);
        if ($request['type'] == 'percent' && ($request['discount'] < 0 || $request['discount'] > 100)) {
            $cartItems = $this->getCartData(cartName: $cartId);
            $text = $request['discount'] > 0 ? 'Extra_discount_can_not_be_less_than_0_percent' :
                'Extra_discount_can_not_be_more_than_100_percent';
            ToastMagic::error(translate($text));
            return response()->json([
                'extraDiscount' => "amount_low",
                'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
            ]);
        }
        $cart = session($cartId, collect());
        if ($cart) {
            $totalProductPrice = 0;
            $productDiscount = 0;
            $productTax = 0;
            $couponDiscount = $cart['coupon_discount'] ?? 0;
            $includeTax = 0;

            foreach ($cart as $item) {
                if (is_array($item)) {
                    $product = $this->productRepo->getFirstWhere(params: ['id' => $item['id']], relations: ['clearanceSale' => function ($query) {
                        return $query->active();
                    }]);
                    $totalProductPrice += $item['price'] * $item['quantity'];
                    $productDiscount += $item['discount'] * $item['quantity'];
                    $productTax += $this->getTaxAmount($item['price'], $product['tax']) * $item['quantity'];
                    if ($product['tax_model'] == 'include') {
                        $includeTax += $productTax;
                    }
                }
            }
            if ($request['type'] == 'percent') {
                $extraDiscount = (($totalProductPrice - $includeTax) / 100) * $request['discount'];
            } else {
                $extraDiscount = currencyConverter(amount: $request['discount']);
            }
            $total = $totalProductPrice - $productDiscount + $productTax - $couponDiscount - $extraDiscount - $includeTax;
            if ($total < 0) {
                $cartItems = $this->getCartData(cartName: $cartId);
                return response()->json([
                    'extraDiscount' => "amount_low",
                    'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
                ]);
            } else {
                $cart['ext_discount'] = $request['type'] == 'percent' ? $request['discount'] : currencyConverter(amount: $request['discount']);
                $cart['ext_discount_type'] = $request['type'];
                session()->put($cartId, $cart);
                $cartItems = $this->getCartData(cartName: $cartId);
                return response()->json([
                    'extraDiscount' => "success",
                    'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
                ]);
            }
        } else {
            $cartItems = $this->getCartData(cartName: $cartId);
            return response()->json([
                'extraDiscount' => "empty",
                'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCouponDiscount(Request $request): JsonResponse
    {
        $cartId = session(SessionKey::CURRENT_USER);
        $userId = $this->cartService->getUserId();
        if ($userId != 0) {
            $usedCoupon = $this->orderRepo->getListWhere(filters: ['customer_type' => 'customer', 'coupon_code' => $request['coupon_code']])->count();
            $coupon = $this->couponRepo->getFirstWhereFilters(
                filters: [
                    'code' => $request['coupon_code'],
                    'coupon_bearer' => 'seller',
                    'limit' => $usedCoupon,
                    'start_date' => now(),
                    'expire_date' => now(),
                    'status' => 1
                ]
            );

        } else {
            $coupon = $this->couponRepo->getFirstWhereFilters(
                filters: [
                    'code' => $request['coupon_code'],
                    'coupon_bearer' => 'seller',
                    'start_date' => now(),
                    'expire_date' => now(),
                    'status' => 1
                ]
            );
        }
        if (!$coupon || $coupon['coupon_type'] == 'free_delivery' || $coupon['coupon_type'] == 'first_order') {
            $cartItems = $this->getCartData(cartName: $cartId);
            return response()->json([
                'coupon' => 'coupon_invalid',
                'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
            ]);
        }

        $carts = session($cartId);
        $totalProductPrice = 0;
        $productDiscount = 0;
        $productTax = 0;
        $includeTax = 0;

        if (($coupon['seller_id'] == '0' || $coupon['seller_id'] == auth('seller')->id()) && ($coupon['customer_id'] == '0' || $coupon['customer_id'] == $userId)) {
            if ($carts != null) {
                foreach ($carts as $cart) {
                    if (is_array($cart)) {
                        $product = $this->productRepo->getFirstWhere(params: ['id' => $cart['id']], relations: ['clearanceSale' => function ($query) {
                            return $query->active();
                        }]);
                        $totalProductPrice += $cart['price'] * $cart['quantity'];
                        $productDiscount += $cart['discount'] * $cart['quantity'];
                        $productTax += ($this->getTaxAmount($cart['price'], $product['tax'])) * $cart['quantity'];
                        if ($product['tax_model'] == 'include') {
                            $includeTax += $productTax;
                        }
                    }
                }
                if ($totalProductPrice >= $coupon['min_purchase']) {
                    $calculation = $this->POSService->getCouponCalculation(coupon: $coupon, totalProductPrice: $totalProductPrice, productDiscount: $productDiscount, productTax: $productTax);
                    $couponDiscount = $calculation['discount'];

                    $extraDiscount = 0;
                    if (isset($carts['ext_discount_type']) && isset($carts['ext_discount'])) {
                        $extraDiscountType = $carts['ext_discount_type'];
                        if ($extraDiscountType == 'percent') {
                            $extraDiscount = (($totalProductPrice - $includeTax) / 100) * $carts['ext_discount'];
                        } else {
                            $extraDiscount = $carts['ext_discount'];
                        }
                    }

                    $total = $totalProductPrice - $productDiscount + $productTax - $couponDiscount - $extraDiscount - $includeTax;
                    if ($total < 0) {
                        $cartItems = $this->getCartData(cartName: $cartId);
                        return response()->json([
                            'coupon' => "amount_low",
                            'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
                        ]);
                    }

                    $this->POSService->putCouponDataOnSession(
                        cartId: $cartId,
                        discount: $couponDiscount,
                        couponTitle: $coupon['title'],
                        couponBearer: $coupon['coupon_bearer'],
                        couponCode: $request['coupon_code']
                    );
                    $cartItems = $this->getCartData(cartName: $cartId);
                    return response()->json([
                        'coupon' => 'success',
                        'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
                    ]);
                }
            } else {
                $cartItems = $this->getCartData(cartName: $cartId);
                return response()->json([
                    'coupon' => 'cart_empty',
                    'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
                ]);
            }
        }
        $cartItems = $this->getCartData(cartName: $cartId);
        return response()->json([
            'coupon' => 'coupon_invalid',
            'view' => view(POS::CART[VIEW], compact('cartId', 'cartItems'))->render()
        ]);
    }

    public function getQuickView(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhereWithCount(
            params: ['id' => $request['product_id']],
            withCount: ['reviews'],
            relations: ['brand', 'category', 'rating', 'tags', 'digitalVariation', 'clearanceSale' => function ($query) {
                return $query->active();
            }],
        );
        return response()->json([
            'success' => 1,
            'view' => view(POS::QUICK_VIEW[VIEW], compact('product'))->render(),
        ]);
    }

    /**
     * @return array
     */
    protected function getCustomerDataFromSessionForPOS(): array
    {
        $cartId = session(SessionKey::CURRENT_USER);
        
        // Check if customer is selected (no more walking customer)
        if (!$cartId || Str::contains($cartId, 'temp-cart')) {
            return [
                'currentCustomer' => null,
                'currentCustomerData' => null
            ];
        }
        
        if (Str::contains($cartId, 'saved-customer')) {
            $userId = explode('-', $cartId)[2];
            $currentCustomerData = $this->customerRepo->getFirstWhere(params: ['id' => $userId]);
            $currentCustomerInfo = $this->cartService->getCustomerInfo(currentCustomerData: $currentCustomerData, customerId: $userId);
            
            return [
                'currentCustomer' => $currentCustomerInfo['customerName'],
                'currentCustomerData' => $currentCustomerData
            ];
        }
        
        return [
            'currentCustomer' => null,
            'currentCustomerData' => null
        ];
    }

    /**
     * @param string $cartName
     * @return array
     */
    protected function getCustomerCartData(string $cartName): array
    {
        $customerCartData = [];
        
        // No more walking customer - must be a saved customer
        if (Str::contains($cartName, 'saved-customer')) {
            $customerId = explode('-', $cartName)[2];
            $currentCustomerData = $this->customerRepo->getFirstWhere(params: ['id' => $customerId]);
            $currentCustomerInfo = $this->cartService->getCustomerInfo(currentCustomerData: $currentCustomerData, customerId: $customerId);
            
            $customerCartData[$cartName] = [
                'customerName' => $currentCustomerInfo['customerName'],
                'customerPhone' => $currentCustomerInfo['customerPhone'],
                'customerId' => $customerId,
            ];
        } else {
            // Temp cart - no customer selected
            $customerCartData[$cartName] = [
                'customerName' => null,
                'customerPhone' => null,
                'customerId' => 0,
            ];
        }
        
        return $customerCartData;
    }

    protected function calculateCartItemsData(string $cartName, array $customerCartData): array
    {
        $cartItemValue = [];
        $subTotalCalculation = [
            'countItem' => 0,
            'totalQuantity' => 0,
            'taxCalculate' => 0,
            'totalTaxShow' => 0,
            'totalTax' => 0,
            'totalIncludeTax' => 0,
            'subtotal' => 0,
            'discountOnProduct' => 0,
            'productSubtotal' => 0,
        ];
        if (session()->get($cartName)) {
            foreach (session()->get($cartName) as $cartItem) {
                if (is_array($cartItem)) {
                    $product = $this->productRepo->getFirstWhere(params: ['id' => $cartItem['id']], relations: ['clearanceSale' => function ($query) {
                        return $query->active();
                    }]);
                    if ($product) {
                        $cartSubTotalCalculation = $this->cartService->getCartSubtotalCalculation(
                            product: $product,
                            cartItem: $cartItem,
                            calculation: $subTotalCalculation
                        );
                        if ($cartItem['customerId'] == $customerCartData[$cartName]['customerId']) {
                            $cartItem['productSubtotal'] = $cartSubTotalCalculation['productSubtotal'];
                            $cartItemValue[] = $cartItem;
                            $subTotalCalculation['customerOnHold'] = $cartItem['customerOnHold'];

                            $subTotalCalculation['countItem'] += $cartSubTotalCalculation['countItem'];
                            $subTotalCalculation['totalQuantity'] += $cartSubTotalCalculation['totalQuantity'];
                            $subTotalCalculation['taxCalculate'] += $cartSubTotalCalculation['taxCalculate'];
                            $subTotalCalculation['totalTaxShow'] += $cartSubTotalCalculation['totalTaxShow'];
                            $subTotalCalculation['totalTax'] += $cartSubTotalCalculation['totalTax'];
                            $subTotalCalculation['totalIncludeTax'] += $cartSubTotalCalculation['totalIncludeTax'];
                            $subTotalCalculation['productSubtotal'] += $cartSubTotalCalculation['productSubtotal'];
                            $subTotalCalculation['subtotal'] += $cartSubTotalCalculation['subtotal'];
                            $subTotalCalculation['discountOnProduct'] += $cartSubTotalCalculation['discountOnProduct'];
                        }
                    }
                }
            }
        }
        $totalCalculation = $this->cartService->getTotalCalculation(
            subTotalCalculation: $subTotalCalculation, cartName: $cartName
        );
        return [
            'countItem' => $subTotalCalculation['countItem'],
            'total' => $totalCalculation['total'],
            'subtotal' => $subTotalCalculation['subtotal'],
            'taxCalculate' => $subTotalCalculation['taxCalculate'],
            'totalTaxShow' => $subTotalCalculation['totalTaxShow'],
            'totalTax' => $subTotalCalculation['totalTax'],
            'discountOnProduct' => $subTotalCalculation['discountOnProduct'],
            'productSubtotal' => $subTotalCalculation['productSubtotal'],
            'cartItemValue' => $cartItemValue,
            'customerOnHold' => $subTotalCalculation['customerOnHold'] ?? false,
            'couponDiscount' => $totalCalculation['couponDiscount'],
            'extraDiscount' => $totalCalculation['extraDiscount'],
        ];
    }

    protected function getCartData(string $cartName): array
    {
        $customerCartData = $this->getCustomerCartData(cartName: $cartName);
        $cartItemData = $this->calculateCartItemsData(cartName: $cartName, customerCartData: $customerCartData);
        return array_merge($customerCartData[$cartName], $cartItemData);
    }

    public function getSearchedProductsView(Request $request): JsonResponse
    {
        $searchTerm = $request['name'];
        
        // Search by complete product name (title) or SKU - only in-stock products
        $products = $this->productRepo->getListWhere(
            searchValue: $searchTerm,
            filters: [
                'added_by' => 'seller',
                'seller_id' => auth('seller')->id(),
                'status' => 1,
                'name' => $searchTerm,  // Search by complete product name
                'code' => $searchTerm,  // Search by SKU (code field)
                'in_stock' => true,  // Only show in-stock products in search
            ],
            relations: ['clearanceSale' => function ($query) {
                return $query->active();
            }],
            dataLimit: 'all'
        );

        $data = [
            'count' => $products->count(),
            'result' => view(POS::SEARCH[VIEW], compact('products'))->render()
        ];
        if ($products->count() > 0) {
            $data += ['id' => $products[0]->id];
        }

        return response()->json($data);
    }

    /**
     * Search customer by exact username or phone number
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function searchCustomer(Request $request): JsonResponse
    {
        $searchTerm = trim($request->input('search'));
        
        if (empty($searchTerm)) {
            return response()->json([
                'success' => false,
                'message' => translate('Please enter username or phone number')
            ]);
        }

        // Search by exact username or phone number
        $customer = $this->customerRepo->getFirstWhere(
            params: [
                'username' => $searchTerm,
            ]
        );

        // If not found by username, try phone number
        if (!$customer) {
            $customer = $this->customerRepo->getFirstWhere(
                params: [
                    'phone' => $searchTerm,
                ]
            );
        }

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->f_name . ' ' . $customer->l_name,
                    'username' => $customer->username,
                    'phone' => $customer->phone,
                    'email' => $customer->email ?? 'N/A',
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => translate('User not found')
        ]);
    }
}
