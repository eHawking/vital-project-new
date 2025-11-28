@php($shippingMethod=getWebConfig(name: 'shipping_method'))
@php($product_price_total=0)
@php($total_tax=0)
@php($total_shipping_cost=0)
@php($order_wise_shipping_discount=\App\Utils\CartManager::order_wise_shipping_discount())
@php($total_discount_on_product=0)
@php($cart=\App\Utils\CartManager::getCartListQuery(type: 'checked'))
@php($cart_group_ids=\App\Utils\CartManager::get_cart_group_ids())
@php($shipping_cost=\App\Utils\CartManager::get_shipping_cost(type: 'checked'))
@php($get_shipping_cost_saved_for_free_delivery=\App\Utils\CartManager::getShippingCostSavedForFreeDelivery(type: 'checked'))
@php($coupon_dis=0)
@php($total_pv=0)
@php($total_bv=0)
@if($cart->count() > 0)
    @foreach($cart as $key => $cartItem)
        @php($product_price_total+=$cartItem['price']*$cartItem['quantity'])
        @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
        @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
        @if(isset($cartItem->product))
            @php($total_pv += $cartItem->product->pv * $cartItem['quantity'])
            @php($total_bv += $cartItem->product->bv * $cartItem['quantity'])
        @endif
    @endforeach

    @php($total_shipping_cost=$shipping_cost - $get_shipping_cost_saved_for_free_delivery)
@else
    <span>{{ translate('empty_cart') }}</span>
@endif
<div class="total-cost-wrapper">
    <div class="total-cost-area text-capitalize">
        <h5 class="mb-4">{{translate('order_summary')}} <small
                    class="text-base font-regular text-small">({{count(\App\Utils\CartManager::getCartListQuery(type: 'checked'))}} {{translate('items')}}
                )</small></h5>

        <div class="overflow-y-auto h--28rem">
            @if(auth('customer')->check())
                @php($cart_list = \App\Models\Cart::whereHas('product', function ($query) {
                    return $query->active();
                })->where(['customer_id' => auth('customer')->id(), 'is_guest' => 0,'is_checked'=>1])->get()->groupBy('cart_group_id'))
            @elseif(getWebConfig(name: 'guest_checkout') && session()->has('guest_id') && session('guest_id'))
                @php($cart_list = \App\Models\Cart::whereHas('product', function ($query) {
                    return $query->active();
                })->where(['customer_id' => session('guest_id'), 'is_guest' => 1,'is_checked'=>1])->get()->groupBy('cart_group_id'))
            @endif
            @foreach($cart_list as $group_key=>$group)
                @foreach($group as $cart_key=>$cartItem)
                    @if ($cart_key == 0)
                        @if($cartItem->seller_is=='admin')
                            <h6 class="font-bold letter-spacing-0">{{ getWebConfig(name: 'company_name') }}</h6>
                        @else
                            <h6 class="font-bold letter-spacing-0">{{ \App\Utils\get_shop_name($cartItem['seller_id']) }}</h6>
                        @endif
                    @endif
                @endforeach
                <ul class="total-cost-info mt-20px mb-20px">
                    @php($isProductNullStatus = 0)

                    {{-- MODIFICATION START --}}
                    @foreach($group as $key=>$cartItem)
                        @php($product = $cartItem->product)
                        @if (!$product)
                            @php($isProductNullStatus = 1)
                        @endif
                        <li>
                            <span>
                                {{ isset($product) ? Str::limit($product->name, 25) : translate('product_not_available') }}
                                @if($cartItem['quantity'] > 1)
                                    <small class="text-muted fw-bold">x {{$cartItem['quantity']}}</small>
                                @endif
                            </span>
                            <span>{{ webCurrencyConverter($cartItem['price'] * $cartItem['quantity']) }}</span>
                        </li>
                    @endforeach
                    {{-- MODIFICATION END --}}

                </ul>
            @endforeach
            <ul class="total-cost-info">
               
                <li>
                    <span>{{ translate('sub_total') }}</span>
                    <span>{{webCurrencyConverter($product_price_total - $total_discount_on_product)}}</span>
                </li>
                <li>
                    <span>{{ translate('shipping') }}</span>
                    <span>{{webCurrencyConverter($total_shipping_cost)}}</span>
                </li>
                <li>
                    <span>{{ translate('PV') }}</span>
                    <span>{{$total_pv}}</span>
                </li>
                <li>
                    <span>{{ translate('BV') }}</span>
                    <span>{{$total_bv}}</span>
                </li>
            </ul>
        </div>
        <div class="ps-sm-4">
            <hr class="d-none d-sm-block"/>
        </div>
        <div class="d-block d-md-none">
            <h6 class="d-flex justify-content-center gap-2 mb-2 justify-content-sm-between letter-spacing-0 font-semibold text-normal">
                <span>{{translate('total')}}</span>
                <span>{{webCurrencyConverter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</span>
            </h6>
        </div>

        <div class="proceed-cart-btn">
            <h6 class="d-flex justify-content-center gap-2 mb-2 justify-content-sm-between letter-spacing-0 font-semibold text-normal">
                <span>{{translate('total')}}</span>
                <span>{{webCurrencyConverter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</span>
            </h6>
            <div class="ps-sm-4">
                <hr class="d-none d-sm-block"/>
            </div>

            @if (str_contains(request()->url(), 'checkout-payment'))
                <button class="btn btn-base w-100 justify-content-center form-control mt-1 mb-1 h-42px text-capitalize custom-disabled"
                    id="proceed-to-payment-action" data-gotocheckout="{{route('customer.choose-shipping-address-other')}}"
                    data-route="{{ route('checkout-payment') }}"
                    data-type="{{ 'checkout-payment' }}"
                    {{ (isset($isProductNullStatus) && $isProductNullStatus == 1) ? 'disabled':''}}
                    type="button">
                        {{ translate('proceed_to_payment') }}
                </button>
            @else
                <button class="btn btn-base w-100 justify-content-center form-control mt-1 mb-1 h-42px text-capitalize"
                    id="proceed_to_next_action" data-gotocheckout="{{route('customer.choose-shipping-address-other')}}"
                    data-checkoutpayment="{{ route('checkout-payment') }}"
                    {{ (isset($isProductNullStatus) && $isProductNullStatus == 1) ? 'disabled':''}}
                    type="button">
                        {{ translate('proceed_to_next') }}
                </button>
            @endif
        </div>
    </div>
</div>