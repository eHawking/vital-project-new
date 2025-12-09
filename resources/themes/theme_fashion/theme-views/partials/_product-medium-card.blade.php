@php($overallRating = getOverallRating($product->reviews))
<style>
    /* Premium Product Card Styling */
    .premium-product-card {
        background: var(--premium-card, #1E293B);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.08));
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    body[theme="light"] .premium-product-card {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }
    .premium-product-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 60px rgba(139, 92, 246, 0.25);
        border-color: #8B5CF6;
    }
    .premium-product-card .premium-img-wrap {
        position: relative;
        overflow: hidden;
        aspect-ratio: 1;
    }
    .premium-product-card .premium-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .premium-product-card:hover .premium-img-wrap img {
        transform: scale(1.1);
    }
    .premium-product-card .premium-badge-new {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        color: #fff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 5;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
    }
    .premium-product-card .premium-discount-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: #fff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }
    .premium-product-card .premium-actions {
        position: absolute;
        bottom: -60px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 15px;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        transition: bottom 0.4s ease;
        z-index: 5;
    }
    .premium-product-card:hover .premium-actions {
        bottom: 0;
    }
    .premium-product-card .premium-action-btn {
        width: 42px;
        height: 42px;
        background: rgba(255,255,255,0.95);
        border: none;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1E293B;
        font-size: 18px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .premium-product-card .premium-action-btn:hover {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        color: #fff;
        transform: scale(1.1);
    }
    .premium-product-card .premium-content {
        padding: 20px;
    }
    .premium-product-card .premium-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0 0 12px;
        line-height: 1.4;
    }
    .premium-product-card .premium-title a {
        color: var(--premium-text, #F1F5F9);
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }
    body[theme="light"] .premium-product-card .premium-title a {
        color: #1E293B;
    }
    .premium-product-card .premium-title a:hover {
        color: #8B5CF6;
    }
    .premium-product-card .premium-price-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .premium-product-card .premium-price {
        font-size: 20px;
        font-weight: 700;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .premium-product-card .premium-old-price {
        font-size: 14px;
        color: var(--premium-muted, #94A3B8);
        text-decoration: line-through;
    }
    .premium-product-card .premium-stock {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }
    .premium-product-card .premium-stock.in-stock {
        background: rgba(16, 185, 129, 0.15);
        color: #10B981;
    }
    .premium-product-card .premium-stock.out-of-stock {
        background: rgba(239, 68, 68, 0.15);
        color: #EF4444;
    }
    .premium-product-card .premium-stock.limited {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }
    .premium-product-card .premium-rating {
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .premium-product-card .premium-rating i {
        font-size: 13px;
        color: #94A3B8;
    }
    .premium-product-card .premium-rating i.filled {
        color: #F59E0B;
    }
    .premium-product-card .premium-rating-count {
        font-size: 12px;
        color: var(--premium-muted, #94A3B8);
        margin-left: 6px;
    }
</style>

<div class="premium-product-card product-cart-option-container">
    <div class="premium-img-wrap">
        <a href="{{route('product',$product->slug)}}" class="d-block h-100">
            <img loading="lazy" alt="{{ translate('product') }}"
                 src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
        </a>
        
        @if (isset($product->created_at) && $product->created_at->diffInMonths(\Carbon\Carbon::now()) < 1)
            <span class="premium-badge-new">{{ translate('new') }}</span>
        @endif

        @php($discount = getProductPriceByType(product: $product, type: 'discount', result: 'value'))
        @if($discount > 0)
            <span class="premium-discount-badge">
                @if($product->discount_type == 'percent')
                    -{{ round($product->discount) }}%
                @else
                    -{{ webCurrencyConverter($discount) }}
                @endif
            </span>
        @endif

        <div class="premium-actions">
            <a href="javascript:" class="premium-action-btn addWishlist_function_view_page" 
               data-id="{{$product->id}}" title="{{ translate('add_to_wishlist') }}">
                <i class="wishlist_{{$product->id}} bi {{ isProductInWishList($product->id) ?'bi-heart-fill text-danger':'bi-heart' }}"></i>
            </a>
            <a href="javascript:" class="premium-action-btn addCompareList_view_page" 
               data-id="{{$product['id']}}" title="{{ translate('compare') }}">
                <i class="bi bi-shuffle compare_list_icon-{{$product['id']}}"></i>
            </a>
            <a href="javascript:" class="premium-action-btn quick-view" 
               data-product-id="{{$product->id}}" title="{{ translate('quick_view') }}">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </div>

    <div class="premium-content">
        <h3 class="premium-title">
            <a href="{{route('product', $product->slug)}}" title="{{ $product['name'] }}">
                {{ $product['name'] }}
            </a>
        </h3>
        
        <div class="premium-price-row">
            {{-- GUEST PRICE LOGIC START --}}
            @if(!auth('customer')->check() && isset($product->guest_price))
                <span class="premium-price">{{ webCurrencyConverter($product->guest_price) }}</span>
                @if($product->guest_price < $product->unit_price)
                    <del class="premium-old-price">{{ webCurrencyConverter($product->unit_price) }}</del>
                @endif
            @else
                <span class="premium-price">{{ getProductPriceByType(product: $product, type: 'discounted_unit_price', result: 'string') }}</span>
                @if($discount > 0)
                    <del class="premium-old-price">{{ webCurrencyConverter($product->unit_price) }}</del>
                @endif
            @endif
            {{-- GUEST PRICE LOGIC END --}}
        </div>

        <div class="d-flex align-items-center justify-content-between">
            @if(($product['product_type'] == 'physical'))
                @if ($product['current_stock'] <= 0)
                    <span class="premium-stock out-of-stock">{{ translate('out_of_stock') }}</span>
                @elseif ($product['current_stock'] <= $web_config['products_stock_limit'])
                    <span class="premium-stock limited">{{ translate('limited_Stock') }}</span>
                @else
                    <span class="premium-stock in-stock">{{ translate('in_stock') }}</span>
                @endif
            @else
                <span class="premium-stock in-stock">{{ translate('in_stock') }}</span>
            @endif

            <div class="premium-rating">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= (int)$overallRating[0])
                        <i class="bi bi-star-fill filled"></i>
                    @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                        <i class="bi bi-star-half filled"></i>
                    @else
                        <i class="bi bi-star-fill"></i>
                    @endif
                @endfor
                <span class="premium-rating-count">({{ $overallRating[1] }})</span>
            </div>
        </div>
    </div>
</div>