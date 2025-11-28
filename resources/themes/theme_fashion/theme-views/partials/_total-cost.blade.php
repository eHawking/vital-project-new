<div class="total-cost-wrapper">

    @php($shippingMethod = getWebConfig(name: 'shipping_method'))
    @php($product_price_total=0)
    @php($total_tax=0)
    @php($total_shipping_cost=0)
    @php($order_wise_shipping_discount=\App\Utils\CartManager::order_wise_shipping_discount())
    @php($total_discount_on_product=0)
    {{-- New variables for PV and BV --}}
    @php($total_pv = 0)
    @php($total_bv = 0)
    @php($cart=\App\Utils\CartManager::getCartListQuery(type: 'checked'))
    @php($cartAll=\App\Utils\CartManager::getCartListQuery())
    @php($cart_group_ids=\App\Utils\CartManager::get_cart_group_ids())
    @php($shipping_cost=\App\Utils\CartManager::get_shipping_cost(type: 'checked'))
    @php($get_shipping_cost_saved_for_free_delivery=\App\Utils\CartManager::getShippingCostSavedForFreeDelivery(type: 'checked'))
    @php($coupon_dis=0)
    @if($cart->count() > 0)
        @foreach($cart as $key => $cartItem)
            @php($product_price_total+=$cartItem['price']*$cartItem['quantity'])
            @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
            @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
            {{-- Calculate total PV and BV --}}
            @php($total_pv += ($cartItem['product']->pv ?? 0) * $cartItem['quantity'])
            @php($total_bv += ($cartItem['product']->bv ?? 0) * $cartItem['quantity'])
        @endforeach

        @if(session()->missing('coupon_type') || session('coupon_type') !='free_delivery')
            @php($total_shipping_cost=$shipping_cost - $get_shipping_cost_saved_for_free_delivery)
        @else
            @php($total_shipping_cost=$shipping_cost)
        @endif
    @endif


    @if($cartAll->count() > 0 && $cart->count() == 0)
        <p class="mb-2 text-center">{{ translate('Please_checked_items_before_proceeding_to_checkout') }}</p>
    @elseif($cartAll->count() == 0)
        <p class="mb-2 text-center">{{ translate('empty_cart') }}</p>
    @endif

    <h6 class="text-center title font-medium letter-spacing-0 mb-20px text-capitalize">{{ translate('totals_cost') }}</h6>

    <div class="total-cost-area">
       
        <ul class="total-cost-info border-bottom-0 border-bottom-sm text-capitalize">
            <li>
                <span>{{ translate('item_price') }}</span>
                <span>{{webCurrencyConverter($product_price_total)}}</span>
            </li>
           
            <li>
                <span>{{ translate('sub_total') }}</span>
                <span>{{webCurrencyConverter($product_price_total - $total_discount_on_product)}}</span>
            </li>
            <li>
                <span>{{ translate('shipping') }}</span>
                <span>{{webCurrencyConverter($total_shipping_cost)}}</span>
            </li>
           
            {{-- Display Total PV and BV --}}
            <li>
                <span>{{ translate('total_PV') }}</span>
                <span>{{$total_pv}}</span>
            </li>
            <li>
                <span>{{ translate('total_BV') }}</span>
                <span>{{$total_bv}}</span>
            </li>

            @php($free_delivery_status = \App\Utils\OrderManager::getFreeDeliveryOrderAmountArray($group[0]->cart_group_id))

                        @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                            <div class="free-delivery-area">
                                <div class="d-flex align-items-center gap-3">
                                    <img loading="lazy"
                                         src="{{ theme_asset('assets/img/free-shipping.svg') }}"
                                         alt="{{ translate('free_shipping') }}" width="40">
                                    @if ($free_delivery_status['amount_need'] <= 0)
                                        <span class="text-muted fs-16">
                                                                {{ translate('you_Get_Free_Delivery_Bonus') }}
                                                            </span>
                                    @else
                                       
                                    
                                </div>
                                <div class="progress free-delivery-progress">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $free_delivery_status['percentage'] }}%"
                                         aria-valuenow="{{ $free_delivery_status['percentage'] }}" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
			          <div class="mt-2">
			                    <span
                                            class="need-for-free-delivery font-bold">{{ webCurrencyConverter($free_delivery_status['amount_need']) }}</span>
                                        <span class="text-muted fs-16">
                                                                {{ translate('add_more_for_free_delivery') }}
                                                            </span>
				                          </div>
			@endif
                            </div>
                        @endif
        </ul>
        <hr/>
        <div class="d-block d-md-none">
            <h6 class="d-flex justify-content-center gap-2 mb-2 justify-content-sm-between letter-spacing-0 font-semibold text-normal">
                <span>{{ translate('total') }}</span>
                <span>{{webCurrencyConverter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</span>
            </h6>
        </div>
        <div class="proceed-cart-btn">
            <h6 class="d-flex justify-content-center gap-2 mb-2 justify-content-sm-between letter-spacing-0 font-semibold text-normal">
                <span>{{ translate('total') }}</span>
                <span>{{webCurrencyConverter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</span>
            </h6>
           
            <button class="btn btn-base w-100 justify-content-center mt-1 form-control h-42px text-capitalize checkout_action {{$cart->count() <= 0 ? 'custom-disabled' : ''}}"
                    {{ (isset($isProductNullStatus) && $isProductNullStatus == 1) ? 'custom-disabled':''}}
                    type="button">{{ translate('proceed_to_checkout') }}</button>
        </div>
    </div>
</div>