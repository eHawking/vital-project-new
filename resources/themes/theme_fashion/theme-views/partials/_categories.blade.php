<style>
    /* Premium Categories Section */
    .premium-categories-section {
        padding: 60px 0;
        background: linear-gradient(180deg, transparent 0%, rgba(139, 92, 246, 0.03) 50%, transparent 100%);
    }
    .premium-categories-section .premium-section-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .premium-categories-section .premium-section-title {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        display: inline-block;
    }
    .premium-categories-section .premium-section-subtitle {
        color: var(--premium-muted, #94A3B8);
        font-size: 16px;
    }
    body[theme="light"] .premium-categories-section .premium-section-subtitle {
        color: #64748B;
    }
    .premium-categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 24px;
    }
    .premium-category-card {
        position: relative;
        background: var(--premium-card, #1E293B);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.08));
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
    }
    body[theme="light"] .premium-category-card {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }
    .premium-category-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 30px 60px rgba(139, 92, 246, 0.25);
        border-color: #8B5CF6;
    }
    .premium-category-card .premium-cat-img {
        position: relative;
        aspect-ratio: 1;
        overflow: hidden;
    }
    .premium-category-card .premium-cat-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .premium-category-card:hover .premium-cat-img img {
        transform: scale(1.15);
    }
    .premium-category-card .premium-cat-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(139, 92, 246, 0.9) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.4s ease;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding: 20px;
    }
    .premium-category-card:hover .premium-cat-overlay {
        opacity: 1;
    }
    .premium-category-card .premium-cat-overlay i {
        font-size: 24px;
        color: #fff;
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }
    .premium-category-card:hover .premium-cat-overlay i {
        transform: translateY(0);
    }
    .premium-category-card .premium-cat-content {
        padding: 20px;
        text-align: center;
    }
    .premium-category-card .premium-cat-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--premium-text, #F1F5F9);
        margin: 0 0 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    body[theme="light"] .premium-category-card .premium-cat-name {
        color: #1E293B;
    }
    .premium-category-card .premium-cat-count {
        font-size: 13px;
        color: #8B5CF6;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .premium-category-card .premium-cat-count i {
        font-size: 14px;
    }
    /* Featured Category (First one larger) */
    .premium-category-card.featured {
        grid-row: span 2;
        grid-column: span 2;
    }
    .premium-category-card.featured .premium-cat-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
        padding: 30px;
    }
    .premium-category-card.featured .premium-cat-name {
        color: #fff;
        font-size: 22px;
    }
    .premium-category-card.featured .premium-cat-count {
        color: rgba(255,255,255,0.9);
    }
    /* Mobile Carousel */
    .premium-categories-mobile {
        display: none;
    }
    @media (max-width: 768px) {
        .premium-categories-grid {
            display: none;
        }
        .premium-categories-mobile {
            display: block;
        }
        .premium-categories-section {
            padding: 40px 0;
        }
        .premium-categories-section .premium-section-title {
            font-size: 24px;
        }
        .premium-category-card {
            border-radius: 16px;
        }
        .premium-category-card .premium-cat-content {
            padding: 15px;
        }
        .premium-category-card .premium-cat-name {
            font-size: 14px;
        }
    }
</style>

<section class="premium-categories-section section-gap pb-0">
    <div class="container">
        <div class="premium-section-header">
            <h2 class="premium-section-title">{{ translate('most_visited_categories') }}</h2>
            <p class="premium-section-subtitle">{{ translate('explore_our_most_popular_categories') }}</p>
        </div>
        
        <!-- Desktop Grid -->
        <div class="premium-categories-grid d-none d-sm-grid">
            @foreach ($mostVisitedCategories as $key => $item)
                <a href="{{route('products',['category_id'=> $item->id,'data_from'=>'category','page'=>1])}}"
                   class="premium-category-card {{ $key == 0 ? 'featured' : '' }}">
                    <div class="premium-cat-img">
                        <img loading="lazy" alt="{{ translate('category') }}" 
                             src="{{ getStorageImages(path: $item->icon_full_url, type:'category') }}">
                        <div class="premium-cat-overlay">
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </div>
                    </div>
                    @if($key != 0)
                        <div class="premium-cat-content">
                            <h4 class="premium-cat-name">{{ $item->name }}</h4>
                            <span class="premium-cat-count">
                                <i class="bi bi-box"></i>
                                {{ $item->product_count }} {{ translate('products') }}
                            </span>
                        </div>
                    @else
                        <div class="premium-cat-content">
                            <h4 class="premium-cat-name">{{ $item->name }}</h4>
                            <span class="premium-cat-count">
                                <i class="bi bi-box"></i>
                                {{ $item->product_count }} {{ translate('products') }}
                            </span>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- Mobile Carousel -->
        <div class="premium-categories-mobile d-sm-none">
            <div class="categories-slider owl-theme owl-carousel">
                @foreach ($mostVisitedCategories as $key => $item)
                    <a href="{{route('products',['category_id'=> $item->id,'data_from'=>'category','page'=>1])}}"
                       class="premium-category-card">
                        <div class="premium-cat-img">
                            <img loading="lazy" alt="{{ translate('category') }}" 
                                 src="{{ getStorageImages(path: $item->icon_full_url, type:'category') }}">
                        </div>
                        <div class="premium-cat-content">
                            <h4 class="premium-cat-name">{{ $item->name }}</h4>
                            <span class="premium-cat-count">
                                <i class="bi bi-box"></i>
                                {{ $item->product_count }} {{ translate('products') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
