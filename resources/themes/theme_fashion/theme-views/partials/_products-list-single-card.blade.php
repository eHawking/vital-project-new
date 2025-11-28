<div class="product-card product-cart-option-container">
    <div class="product-card-inner">
        <div class="img">
            <a href="{{route('product',$product->slug)}}" class="d-block h-100">
                <img loading="lazy" class="w-100" alt="{{ translate('product') }}"
                     src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
            </a>
            @if (isset($product->created_at) && $product->created_at->diffInMonths(\Carbon\Carbon::now()) < 1)
                <span class="badge badge-title z-2">{{translate('new')}}</span>
            @endif
            <div class="hover-content d-flex justify-content-between">
                <a href="javascript:">{{ \Illuminate\Support\Str::limit(isset($product->category) ? $product->category->name:'', 7) }}</a>
                <div class="d-flex flex-wrap justify-content-between align-items-center column-gap-3">
                   
                    <a href="javascript:" class="d-inline-flex wish-icon addWishlist_function_view_page"
                       data-id="{{$product->id}}">
                        <i class="wishlist_{{$product->id}} bi {{ isProductInWishList($product->id) ?'bi-heart-fill text-danger':'bi-heart' }}"></i>
                    </a>
                    <div class="rating">
                        <i class="bi bi-star-fill text-star"></i>
                        <span>{{round($product->reviews->avg('rating') ?? 0,1)}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="cont">
            <h6 class="title">
                <a href="{{route('product',$product->slug)}}"
                   title="{{ $product['name'] }}">{{ $product['name'] }}</a>
            </h6>
            <div class="d-flex align-items-center justify-content-between column-gap-2">
            
                <h4 class="price flex-wrap">
                    @if(!auth('customer')->check() && isset($product->guest_price))
                        <span>{{ webCurrencyConverter($product->guest_price) }}</span>
                        @if($product->guest_price < $product->unit_price)
                            <del>{{ webCurrencyConverter($product->unit_price) }}</del>
                        @endif
                    @else
                        <span>{{ getProductPriceByType(product: $product, type: 'discounted_unit_price', result: 'string') }}</span>
                        @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                            <del>{{webCurrencyConverter($product->unit_price)}}</del>
                        @endif
                    @endif
                </h4>
                
            </div>
            @if ($product['product_type'] == 'physical')
                <div class="sold-info d-flex">
                    <span>{{ $product->order_details_sum_qty > 0 ? $product->order_details_sum_qty.' '.translate('sold').' /' : '' }}</span>
                    <span>{{$product->order_details_sum_qty + $product->current_stock}} {{translate('item')}}</span>
                </div>
            @else
                <div class="sold-info d-flex">
                    {{ $product->order_details_sum_qty > 0 ? $product->order_details_sum_qty.' '.translate('sold') : '' }}
                </div>
            @endif
        </div>
    </div>
</div>