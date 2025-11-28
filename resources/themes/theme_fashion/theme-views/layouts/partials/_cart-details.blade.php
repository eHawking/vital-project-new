@php
    $shippingMethod = getWebConfig(name: 'shipping_method');
    $cart = \App\Models\Cart::whereHas('product', function ($query) {
                return $query->active();
            })->where(['customer_id' => (auth('customer')->check() ? auth('customer')->id() : session('guest_id'))])->with(['seller','allProducts.category'])->get()->groupBy('cart_group_id');
@endphp

@if( $cart->count() > 0)
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8 pe-lg-0">
                <div class="cart-title-area text-capitalize mb-2">
                    <h6 class="title">{{translate('all_cart_product_list')}}
                        <span class="btn-status">({{count(\App\Utils\CartManager::getCartListQuery())}})</span>
                    </h6>
                    <span type="button" class="text-text-2 route_alert_function"
                          data-routename="{{ route('cart.remove-all') }}"
                          data-message="{{ translate('want_to_clear_all_cart?') }}"
                          data-typename="">{{translate('remove_all')}}</span>
                </div>

                <div class="table-responsive d-none d-md-block overflow-hidden">
                    <table class="table __table vertical-middle cart-list-table-custom">
                        <thead class="word-nobreak">
                            <tr>
                                <th>
                                    <label class="form-check m-0">
                                        <span class="form-check-label">{{translate('product')}}</span>
                                    </label>
                                </th>
                               
                                <th class="text-center">
                                    {{translate('quantity')}}
                                </th>
                                <th class="text-center">
                                    {{translate('total')}}
                                </th>
                            </tr>
                        </thead>
                    </table>

                    @foreach($cart as $group_key => $group)
                        @php
                            $physical_product = false;
                            foreach ($group as $row) {
                                if ($row->product_type == 'physical' && $row->is_checked) {
                                    $physical_product = true;
                                }
                            }
                        @endphp

                        <?php
                            $productNullStatus = 0;
                            $total_amount = 0
                        ?>
                        <div class="cart_information">
                        @foreach($group as $cart_key => $cartItem)

                            @if($cart_key==0)
                                <div class="--bg-6 border-0 rounded py-2 px-2 px-sm-3 ">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between ">

                                        @php
                                            $verify_status = \App\Utils\OrderManager::verifyCartListMinimumOrderAmount($request, $group_key);
                                        @endphp

                                        <div class="min-w-180 d-flex">
                                            @if($cartItem->seller_is=='admin')
                                                <div class="d-flex gap-3 align-items-center">
                                                    <input type="checkbox" class="shop-head-check shop-head-check-desktop w-auto">
                                                    <a href="{{route('shopView',['id'=>0])}}" class="cart-shop">
                                                        <img loading="lazy" alt="{{ translate('logo') }}"
                                                             src="{{ getStorageImages(path: $web_config['fav_icon'], type: 'shop') }}">
                                                        <h6 class="text-base">{{$web_config['company_name']}}</h6>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="d-flex gap-3 align-items-center">
                                                    <input type="checkbox" class="shop-head-check shop-head-check-desktop w-auto">
                                                    <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}"
                                                       class="cart-shop">
                                                        <img loading="lazy" alt="{{ translate('shop') }}"
                                                             src="{{ getStorageImages(path: $cartItem->seller->shop->image_full_url, type: 'shop') }}">
                                                        <h6 class="text-base">
                                                            {{ $cartItem->seller?->shop?->name ?? translate('vendor_not_available') }}
                                                        </h6>
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                <span class="ps-2 text-danger pulse-button minimum_Order_Amount_message"
                                                      data-bs-toggle="tooltip" data-bs-placement="right"
                                                      data-bs-custom-class="custom-tooltip"
                                                      data-bs-title="{{ translate('minimum_Order_Amount') }} {{ webCurrencyConverter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{ getWebConfig(name: 'company_name') }} @else {{ \App\Utils\get_shop_name($cartItem['seller_id']) }} @endif">
                                                <i class="bi bi-info-circle"></i>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php($product = $cartItem->allProducts)

                            @if (!$product)
                                @php($productNullStatus = 1)
                            @endif

                            <?php
                            $checkProductStatus = $cartItem->allProducts?->status ?? 0;
                            if($cartItem->seller_is == 'admin') {
                                $inhouseTemporaryClose = getWebConfig(name: 'temporary_close') ? getWebConfig(name: 'temporary_close')['status'] : 0;
                                $inhouseVacation = getWebConfig(name: 'vacation_add');
                                $vacationStartDate = $inhouseVacation['vacation_start_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_start_date'])) : null;
                                $vacationEndDate = $inhouseVacation['vacation_end_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_end_date'])) : null;
                                $vacationStatus = $inhouseVacation['status'] ?? 0;
                                if ($inhouseTemporaryClose || ($vacationStatus && (date('Y-m-d') >= $vacationStartDate) && (date('Y-m-d') <= $vacationEndDate))) {
                                    $checkProductStatus = 0;
                                }
                            }else{
                                if (!isset($cartItem->allProducts->seller) || (isset($cartItem->allProducts->seller) && $cartItem->allProducts->seller->status != 'approved')) {
                                    $checkProductStatus = 0;
                                }
                                if (!isset($cartItem->allProducts->seller->shop) || $cartItem->allProducts->seller->shop->temporary_close) {
                                    $checkProductStatus = 0;
                                }
                                if(isset($cartItem->allProducts->seller->shop) && ($cartItem->allProducts->seller->shop->vacation_status && (date('Y-m-d') >= $cartItem->allProducts->seller->shop->vacation_start_date) && (date('Y-m-d') <= $cartItem->allProducts->seller->shop->vacation_end_date))) {
                                    $checkProductStatus = 0;
                                }
                            }
                            ?>

                            <form class="cart add_to_cart_form{{$cartItem['id']}}"
                                      id="add_to_cart_form_web{{$cartItem['id']}}"
                                      action="{{route('cart.update-variation')}}"
                                      data-errormessage="{{translate('please_choose_all_the_options')}}"
                                      data-outofstock="{{translate('sorry').', '.translate('out_of_stock')}}.">
                                    @csrf
                                    <table class="table __table vertical-middle cart-list-table-custom">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="id" value="{{ $cartItem->id }}" hidden>
                                                @if($product)
                                                    <input type="text" name="product_id" value="{{ $product->id }}" hidden>
                                                @endif
                                                <div class="d-flex gap-3 align-items-center">
                                                    <input type="checkbox" class="shop-item-check w-auto shop-item-check-desktop" value="{{ $cartItem['id'] }}" {{ $cartItem['is_checked'] ? 'checked' : '' }}>
                                                    <div class="cart-product  align-items-center">
                                                        <label class="form-check position-relative overflow-hidden">
                                                            @if ($product && $product->status == 1)
                                                                <img loading="lazy" alt="{{ translate('product') }}"
                                                                     src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
                                                            @elseif($product && $product->status == 0)
                                                                <img loading="lazy" alt="{{ translate('product') }}"
                                                                     src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
                                                                <span
                                                                    class="temporary-closed position-absolute text-center p-2">
                                                                        <span>{{translate('not_available')}}</span>
                                                                    </span>
                                                            @else
                                                                <img loading="lazy"
                                                                     src="{{ theme_asset('assets/img/image-place-holder.png') }}"
                                                                     alt="{{ translate('product') }}">
                                                                <span
                                                                    class="temporary-closed position-absolute text-center p-2">
                                                                        <span>{{translate('not_available')}}</span>
                                                                    </span>
                                                            @endif
                                                        </label>


                                                        <div class="cont {{ $checkProductStatus == 0 ? 'custom-cart-opacity-50':'' }}">
                                                            <a href="{{ $checkProductStatus ? route('product',$product['slug']) : 'javascript:' }}"
                                                               class="name text-title">
                                                                {{ $checkProductStatus ? $product->name : $cartItem->name}}
                                                            </a>
                                                          
                                                            @if ($product)
                                                                    <?php
                                                                    $getProductCurrentStock = $product->current_stock;
                                                                    if(!empty($product->variation)) {
                                                                        foreach(json_decode($product->variation, true) as $productVariantSingle) {
                                                                            if($productVariantSingle['type'] == $cartItem->variant) {
                                                                                $getProductCurrentStock = $productVariantSingle['qty'];
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                @if ($product->product_type == "physical")
                                                                    <div>
                                                                        @if (!empty(json_decode($product->colors)))
                                                                            <div>
                                                                                <span>{{ translate('color') }} </span>
                                                                                <span>:</span>
                                                                                <select
                                                                                    class="no-border-select variants-class{{$cart_key}} update_add_to_cart_by_variation_web"
                                                                                    data-id="{{$cartItem['id']}}"
                                                                                    name="color" {{ $checkProductStatus == 0 ? 'disabled':'' }}>
                                                                                    @foreach (json_decode($product->colors) as $k=>$value)
                                                                                        <option
                                                                                            value="{{ $value }}" {{ $cartItem->color == $value ? 'selected':'' }}>{{getColorNameByCode(code: $value)}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        @endif

                                                                        @php($variations = json_decode($cartItem->variations,true))
                                                                        @foreach (json_decode($product->choice_options) as $k => $choice)
                                                                            <div>
                                                                                <span> {{ translate( $choice->title )}} </span>
                                                                                <span>:</span>
                                                                                <select
                                                                                    class="no-border-select variants-class{{$cart_key}} update_add_to_cart_by_variation_web"
                                                                                    data-id="{{$cartItem['id']}}"
                                                                                    name="{{$choice->name}}" {{ $checkProductStatus == 0 ? 'disabled':'' }}>
                                                                                    @foreach ($choice->options as $value)
                                                                                        <option
                                                                                            value="{{ trim($value) }}" {{in_array(trim($value),$variations,true) ? 'selected' : ''}}>{{ ucwords($value) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                    @if($getProductCurrentStock < $cartItem['quantity'])
                                                                        <div class="d-flex text-danger font-bold">
                                                                            <span>{{ translate('Out_Of_Stock') }}</span>
                                                                        </div>
                                                                    @endif
                                                                @endif

                                                                @php($extensionIndex=0)
                                                                <input hidden name="current_variant_key" value="{{ $cartItem['variant'] }}">
                                                                @if($product['product_type'] == 'digital' && $product['digital_product_file_types'] && count($product['digital_product_file_types']) > 0 && $product['digital_product_extensions'])
                                                                    <div class="d-flex column-gap-1">
                                                                        <span> {{ translate('Select_an_extension')}} </span>
                                                                        <span>:</span>
                                                                        <select class="no-border-select variants-class{{$cart_key}} update_add_to_cart_by_variation_web"
                                                                            data-id="{{ $cartItem['id'] }}" name="variant_key" {{ $checkProductStatus == 0 ? 'disabled':'' }}
                                                                            {{ $extensionIndex == 0 ? 'checked' : ''}}>
                                                                        @foreach($product['digital_product_extensions'] as $extensionKey => $extensionGroup)
                                                                            @foreach($extensionGroup as $index => $extension)
                                                                                <option value="{{ $extensionKey.'-'. preg_replace('/\s+/', '-', $extension) }}"
                                                                                    {{ $cartItem['variant'] == $extensionKey.'-'. preg_replace('/\s+/', '-', $extension) ? 'selected' : '' }}
                                                                                >{{ ucwords($extension) }}</option>
                                                                                @php($extensionIndex++)
                                                                            @endforeach
                                                                        @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endif

                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php($minimum_order=\App\Utils\ProductManager::get_product($cartItem['product_id']))

                                                @if($minimum_order && $checkProductStatus)
                                                    <div class="quantity __quantity">
                                                        <input type="number"
                                                               class="quantity__qty cart-qty-input cart-quantity-web{{$cartItem['id']}} form-control cartQuantity{{$cartItem['id']}} updateCartQuantityList_cart_data"
                                                               value="{{$cartItem['quantity']}}" name="quantity"
                                                               id="cartQuantityWeb{{$cartItem['id']}}"
                                                               data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                               data-cart="{{ $cartItem['id'] }}" data-value="0"
                                                               data-action=""
                                                               data-current-stock="{{ $getProductCurrentStock }}"
                                                               data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">
                                                        <div>
                                                            <div
                                                                class="quantity__plus cart-qty-btn updateCartQuantityList_cart_data"
                                                                data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                                data-cart="{{ $cartItem['id'] }}" data-value="1"
                                                                data-action=""
                                                            >
                                                                <i class="bi bi-plus "></i>
                                                            </div>
                                                            <div
                                                                class="quantity__minus cart-qty-btn updateCartQuantityList_cart_data"
                                                                data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                                data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                                data-action="{{ $cartItem['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}"
                                                            >
                                                                @if( $getProductCurrentStock < $cartItem['quantity'] || $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1))
                                                                    <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                                @else
                                                                    <i class="bi bi-dash-lg"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="quantity __quantity">
                                                        <input type="text"
                                                               class="quantity__qty cart-qty-input cart-quantity-web{{$cartItem['id']}} form-control cartQuantity{{$cartItem['id']}}"
                                                               name="quantity" id="cartQuantity{{$cartItem['id']}}"
                                                               data-min="{{$cartItem['quantity']}}"
                                                               value="{{$cartItem['quantity']}}" readonly>
                                                        <div>
                                                            <div class="cart-qty-btn disabled"
                                                                 title="{{ translate('product_not_available') }}">
                                                                <i class="bi bi-exclamation-circle text-danger"></i>
                                                            </div>
                                                            <div class="cart-qty-btn updateCartQuantityList_cart_data"
                                                                 data-minorder="{{$cartItem['quantity']+1}}"
                                                                 data-cart="{{ $cartItem['id'] }}"
                                                                 data-value="-{{$cartItem['quantity']}}"
                                                                 data-action="delete">
                                                                <i class="bi bi-trash3-fill text-danger fs-10}"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            @php($total_amount = $total_amount + ($cartItem['price'] * $cartItem['quantity']))
                                            <td class="text-center">{{ webCurrencyConverter($cartItem['price'] * $cartItem['quantity']) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                        @endforeach
                        </div>
                        @endforeach
                </div>

                <div class="d-flex d-md-none flex-column mt-4 gap-3">
                    @foreach($cart as $group_key=>$group)
                        <div class="cart_information">
                        @foreach($group as $cartKeyMobile => $cartItem)
                            @if($cartKeyMobile==0)
                                <div class="--bg-6 border-0 rounded py-2 px-2 px-sm-3 ">
                                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between ">
                                        <div class="flex-grow-1">
                                            <div class="d-flex gap-2">
                                                <input type="checkbox" class="shop-head-check shop-head-check-mobile w-auto">
                                                @if($cartItem->seller_is=='admin')
                                                    <a href="{{route('shopView',['id'=>0])}}" class="cart-shop">
                                                        <img loading="lazy" alt="{{ translate('shop') }}"
                                                             src="{{ getStorageImages(path: $web_config['fav_icon'], type: 'logo')}}">
                                                        <h6 class="name text-base text-nowrap w-100">{{$web_config['company_name']}}</h6>
                                                    </a>
                                                @else
                                                    <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}"
                                                       class="cart-shop">
                                                        <img loading="lazy" alt="{{ translate('shop') }}"
                                                             src="{{ getStorageImages(path: $cartItem->seller->shop->image_full_url, type: 'shop') }}">
                                                        <h6 class="name text-base text-nowrap w-100">
                                                            {{ $cartItem->seller?->shop?->name ?? translate('vendor_not_available') }}
                                                        </h6>
                                                    </a>
                                                @endif

                                                    <?php
                                                    $verify_status = \App\Utils\OrderManager::verifyCartListMinimumOrderAmount($request, $group_key);
                                                    ?>

                                                @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                    <span
                                                        class="ps-2 text-danger pulse-button minimum_Order_Amount_message"
                                                        data-bs-title="{{ translate('minimum_Order_Amount') }} {{ webCurrencyConverter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{ getWebConfig(name: 'company_name') }} @else {{ \App\Utils\get_shop_name($cartItem['seller_id']) }} @endif">
                                                <i class="bi bi-info-circle"></i>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php($product = $cartItem->product)

                                <?php
                                $checkProductStatus = $cartItem->allProducts?->status ?? 0;
                                if($cartItem->seller_is == 'admin') {
                                    $inhouseTemporaryClose = getWebConfig(name: 'temporary_close') ? getWebConfig(name: 'temporary_close')['status'] : 0;
                                    $inhouseVacation = getWebConfig(name: 'vacation_add');
                                    $vacationStartDate = $inhouseVacation['vacation_start_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_start_date'])) : null;
                                    $vacationEndDate = $inhouseVacation['vacation_end_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_end_date'])) : null;
                                    $vacationStatus = $inhouseVacation['status'] ?? 0;
                                    if ($inhouseTemporaryClose || ($vacationStatus && (date('Y-m-d') >= $vacationStartDate) && (date('Y-m-d') <= $vacationEndDate))) {
                                        $checkProductStatus = 0;
                                    }
                                }else{
                                    if (!isset($cartItem->allProducts->seller) || (isset($cartItem->allProducts->seller) && $cartItem->allProducts->seller->status != 'approved')) {
                                        $checkProductStatus = 0;
                                    }
                                    if (!isset($cartItem->allProducts->seller->shop) || $cartItem->allProducts->seller->shop->temporary_close) {
                                        $checkProductStatus = 0;
                                    }
                                    if(isset($cartItem->allProducts->seller->shop) && ($cartItem->allProducts->seller->shop->vacation_status && (date('Y-m-d') >= $cartItem->allProducts->seller->shop->vacation_start_date) && (date('Y-m-d') <= $cartItem->allProducts->seller->shop->vacation_end_date))) {
                                        $checkProductStatus = 0;
                                    }
                                }
                                ?>

                            <form class="cart add_to_cart_form{{$cartItem['id']}}"
                                  id="add_to_cart_form_mobile{{$cartItem['id']}}"
                                  action="{{route('cart.update-variation')}}"
                                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                                  data-outofstock="{{translate('sorry').', '.translate('out_of_stock')}}.">
                                @csrf
                                <div class="d-flex gap-3 pt-3 pb-3 border-bottom justify-content-between align-items-center">
                                    <input type="text" name="id" value="{{ $cartItem->id }}" hidden>
                                    <input type="text" name="product_id"
                                           value="{{ isset($product) ? $product->id : ''}}" hidden>

                                    <div class="d-flex align-item-center gap-2">
                                        <input type="checkbox" class="shop-item-check w-auto shop-item-check-mobile" value="{{ $cartItem['id'] }}" {{ $cartItem['is_checked'] ? 'checked' : '' }}>
                                        <div class="cart-product align-items-center">
                                            <label class="form-check">

                                                @if (isset($product))
                                                    <img loading="lazy" alt="{{ translate('product') }}"
                                                         src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
                                                @else
                                                    <img loading="lazy"
                                                         src="{{ theme_asset('assets/img/image-place-holder.png') }}"
                                                         alt="{{ translate('product') }}">

                                                @endif
                                            </label>
                                            <div class="cont   {{$checkProductStatus == 0 ? 'custom-cart-opacity-50' : '' }}">
                                                <a href="{{ isset($product) ? route('product',$product['slug']) : 'javascript:'}}"
                                                   class="name text-title">{{ isset($product) ? $product->name : $cartItem->name }}</a>
                                                <div>
                                                    <span>{{ translate('price') }}</span> <span>:</span> <strong
                                                        class="product_price{{$cartItem['id']}}">{{ webCurrencyConverter($cartItem->price) }}</strong>
                                                </div>
                                                
                                                @if (isset($product))
                                                   

                                                    @if ($product->product_type == "physical")
                                                        <div>
                                                            @if (!empty(json_decode($product->colors)))
                                                                <div>
                                                                    <span>{{ translate('color') }} </span> <span>:</span>
                                                                    <select
                                                                        class="no-border-select text-title variants-class{{$cartKeyMobile}} update_add_to_cart_by_variation_mobile"
                                                                        data-id="{{$cartItem['id']}}" name="color">
                                                                        @foreach (json_decode($product->colors) as $k=>$value)
                                                                            <option
                                                                                value="{{ $value }}"{{ $cartItem->color == $value ? 'selected':'' }}>{{getColorNameByCode(code: $value)}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                            @php($variations = json_decode($cartItem->variations,true))
                                                            @foreach (json_decode($product->choice_options) as $k => $choice)
                                                                <div>
                                                                    <span> {{ translate( $choice->title )}} </span>
                                                                    <span>:</span>
                                                                    <select
                                                                        class="no-border-select text-title variants-class{{$cartKeyMobile}} update_add_to_cart_by_variation_mobile"
                                                                        data-id="{{$cartItem['id']}}"
                                                                        name="{{$choice->name}}">
                                                                        @foreach ($choice->options as $value)
                                                                            <option
                                                                                value="{{ trim($value) }}" {{in_array(trim($value),$variations,true) ? 'selected' : ''}}>{{ ucwords($value) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        @if($getProductCurrentStock < $cartItem['quantity'])
                                                            <div class="d-flex text-danger font-bold">
                                                                <span>{{ translate('Out_Of_Stock') }}</span>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    @php($minimum_order=\App\Utils\ProductManager::get_product($cartItem['product_id']))
                                    @if($minimum_order && $checkProductStatus)
                                        <div class="quantity quantity--style-two d-flex flex-column align-items-center">
                                            <div
                                                class="quantity__minus cart-qty-btn updateCartQuantityListMobile_cart_data"
                                                data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                data-action="{{ $cartItem['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}"
                                            >
                                                @if($getProductCurrentStock < $cartItem['quantity'] || $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1))
                                                    <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                @else
                                                    <i class="bi bi-dash-lg"></i>
                                                @endif
                                            </div>
                                            <input type="text"
                                                   class="updateCartQuantityListMobile_cart_data quantity__qty cart-qty-input form-control cart-quantity-mobile{{$cartItem['id']}} cartQuantity{{$cartItem['id']}}"
                                                   value="{{$cartItem['quantity']}}" name="quantity"
                                                   id="cartQuantityMobile{{$cartItem['id']}}"
                                                   data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                   data-current-stock="{{ $getProductCurrentStock }}"
                                                   data-cart="{{ $cartItem['id'] }}" data-value="0" data-action=""
                                                   data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">

                                            <div
                                                class="quantity__plus cart-qty-btn updateCartQuantityListMobile_cart_data"
                                                data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                data-cart="{{ $cartItem['id'] }}" data-value="1" data-action="">
                                                <i class="bi bi-plus "></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="quantity quantity--style-two d-flex flex-column align-items-center">
                                            <div class="cart-qty-btn updateCartQuantityList_cart_data"
                                                 data-minorder="{{ $cartItem['quantity']+1 }}"
                                                 data-cart="{{ $cartItem['id'] }}"
                                                 data-value="-{{$cartItem['quantity']}}" data-action="delete">
                                                <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                            </div>
                                            <input type="text"
                                                   class="quantity__qty cart-qty-input form-control cart-quantity-mobile{{$cartItem['id']}} cartQuantity{{$cartItem['id']}} updateCartQuantityList_cart_data"
                                                   data-minorder="{{ $minimum_order->minimum_order_qty ?? 1 }}"
                                                   data-cart="{{ $cartItem['id'] }}" data-value="0" data-action=""
                                                   value="{{$cartItem['quantity']}}" name="quantity"
                                                   id="cartQuantityMobile{{$cartItem['id']}}"
                                                   data-current-stock="{{ $getProductCurrentStock }}"
                                                   data-min="{{$cartItem['quantity']}}" disabled>
                                            <div class="cart-qty-btn" disabled
                                                 title="{{ translate('product_not_available') }}">
                                                <i class="bi bi-exclamation-circle text-danger"></i>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </form>

                        @endforeach
                        </div>

                    @endforeach
                </div>


                <div>
                    <form method="get">
                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-12">
                                    <label for="order_note" class="form--label mb-2">{{translate('order_note')}} <span
                                            class="form-label">({{translate('optional')}})</span></label>
                                    <textarea class="form-control w-100" rows="5" id="order_note"
                                              name="order_note">{{ session('order_note')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-lg-4 ps-lg-4 ps-xl-5">
                @include('theme-views.partials._total-cost', ['isProductNullStatus'=>$productNullStatus])
            </div>

        </div>
    </div>
@else
    <div class="d-flex justify-content-center align-items-center w-100">
        <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
            <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-cart.svg') }}" alt="">
            <h5 class="text-center text-muted">
                {{ translate('You_have_not_added_anything_to_your_cart_yet') }}!
            </h5>
        </div>
    </div>
@endif

@push('script')
    <script src="{{ theme_asset('assets/js/cart-details.js') }}"></script>
@endpush