@php($overallRating = getOverallRating($product->reviews))
<div class="product-card product-cart-option-container">
    <div class="img">
        <a href="{{route('product',$product->slug)}}" class="d-block h-100">
            <img loading="lazy" class="w-100" alt="{{ translate('product') }}"
                 src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
        </a>
        @if (isset($product->created_at) && $product->created_at->diffInMonths(\Carbon\Carbon::now()) < 1)
            <span class="badge badge-title z-2">{{translate('new')}}</span>
        @endif

        <div class="hover-content d-flex gap-2 justify-content-{{ isset($hideQuickView) ? 'between':'end'}}">
            @if (isset($hideQuickView))
                <a href="javascript:" class="text-truncate"
                   title="{{isset($product->category) ? $product->category->name : '' }}">{{ \Illuminate\Support\Str::limit(isset($product->category) ? $product->category->name:'', 16) }}</a>
            @endif
            <div class="d-flex column-gap-3">
               

                <a href="javascript:" class="d-inline-flex wish-icon addWishlist_function_view_page"
                   data-id="{{$product->id}}">
                    <i class="wishlist_{{$product->id}} bi {{ isProductInWishList($product->id) ?'bi-heart-fill text-danger':'bi-heart' }}"></i>
                </a>

                <a href="javascript:" class="d-inline-flex wish-icon addCompareList_view_page"
                   data-id="{{$product['id']}}">
                    <i class="bi bi-shuffle compare_list_icon-{{$product['id']}}"></i>
                </a>

                 
            </div>
        </div>
    </div>

    <div class="cont">
        <h3 class="title h6">
            <a href="{{route('product', $product->slug)}}" class="text-truncate"
               title="{{ $product['name'] }}">{{ $product['name'] }}</a>
        </h3>
        <div class="d-flex flex-wrap row-gap-1 align-items-center column-gap-2 text-capitalize">
            {{-- GUEST PRICE LOGIC START --}}
            <h4 class="price flex-wrap">
                @if(!auth('customer')->check() && isset($product->guest_price))
                    <span>{{ webCurrencyConverter($product->guest_price) }}</span>
                    @if($product->guest_price < $product->unit_price)
                        <del>{{ webCurrencyConverter($product->unit_price) }}</del>
                    @endif
                @else
                    <span>{{ getProductPriceByType(product: $product, type: 'discounted_unit_price', result: 'string') }}</span>
                    @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                        <del>{{ webCurrencyConverter($product->unit_price) }}</del>
                    @endif
                @endif
            </h4>
            {{-- GUEST PRICE LOGIC END --}}

            @if(($product['product_type'] == 'physical'))
                @if ($product['current_stock'] <= 0)
                    <span class="status text-danger">{{ translate('out_of_stock') }}</span>
                @elseif ($product['current_stock'] <= $web_config['products_stock_limit'])
                    <span class="status">{{ translate('limited_Stock') }}</span>
                @else
                    <span class="status">{{ translate('in_stock') }}</span>
                @endif
            @else
                <span class="status">{{ translate('in_stock') }}</span>
            @endif
        </div>
        <div class="rating">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= (int)$overallRating[0])
                    <i class="bi bi-star-fill filled"></i>
                @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                    <i class="bi bi-star-half filled"></i>
                @else
                    <i class="bi bi-star-fill"></i>
                @endif
            @endfor
        </div>
    </div>
</div>