<style>
    /* Premium Recommended Section */
    .premium-recommended-section {
        padding: 60px 0;
        position: relative;
    }
    .premium-recommended-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 35px;
    }
    .premium-recommended-title {
        font-size: 28px;
        font-weight: 800;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }
    .premium-tabs {
        display: flex;
        background: var(--premium-card, #1E293B);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.08));
        border-radius: 16px;
        padding: 6px;
        list-style: none;
        margin: 0;
    }
    body[theme="light"] .premium-tabs {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }
    .premium-tabs li {
        margin: 0;
    }
    .premium-tabs li a {
        display: block;
        padding: 12px 24px;
        color: var(--premium-muted, #94A3B8);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    body[theme="light"] .premium-tabs li a {
        color: #64748B;
    }
    .premium-tabs li a.active,
    .premium-tabs li a:hover {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        color: #fff;
    }
    .premium-nav-controls {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .premium-nav-controls .premium-nav-btn {
        width: 48px;
        height: 48px;
        background: var(--premium-card, #1E293B);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--premium-text, #F1F5F9);
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    body[theme="light"] .premium-nav-controls .premium-nav-btn {
        background: #ffffff;
        border-color: rgba(0,0,0,0.1);
        color: #1E293B;
    }
    .premium-nav-controls .premium-nav-btn:hover {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        border-color: transparent;
        color: #fff;
        transform: scale(1.1);
    }
    .premium-see-all-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
    }
    .premium-see-all-link i {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: transform 0.3s ease;
    }
    .premium-see-all-link:hover i {
        transform: translateX(5px);
    }
    @media (max-width: 992px) {
        .premium-recommended-header {
            flex-direction: column;
            text-align: center;
        }
        .premium-tabs {
            order: 2;
        }
        .premium-nav-controls {
            order: 1;
        }
    }
    @media (max-width: 576px) {
        .premium-recommended-section {
            padding: 40px 0;
        }
        .premium-recommended-title {
            font-size: 22px;
        }
        .premium-tabs li a {
            padding: 10px 16px;
            font-size: 13px;
        }
    }
</style>

<section class="premium-recommended-section section-gap pb-0">
    <div class="container">
        <div class="premium-recommended-header single_section_dual_tabs">
            <h2 class="premium-recommended-title">{{ translate('recommended_for_you') }}</h2>
            
            <ul class="premium-tabs nav nav-tabs single_section_dual_btn">
                <li data-targetbtn="0">
                    <a href="#latest" class="active" data-bs-toggle="tab">{{ translate('latest_product') }}</a>
                </li>
                <li data-targetbtn="1">
                    <a href="#most-searching" data-bs-toggle="tab">{{ translate('most_searching') }}</a>
                </li>
            </ul>
            
            <div class="premium-nav-controls">
                <div class="premium-nav-btn recommended-prev">
                    <i class="bi bi-chevron-left"></i>
                </div>
                <div class="premium-nav-btn recommended-next">
                    <i class="bi bi-chevron-right"></i>
                </div>
                <div class="single_section_dual_target">
                    <a href="{{route('products',['data_from'=>'latest','page'=>1])}}" class="premium-see-all-link">
                        {{ translate('see_all') }} <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{route('products',['data_from'=>'best-selling','page'=>1])}}" class="premium-see-all-link d-none">
                        {{ translate('see_all') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="overflow-hidden">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="latest">
                    <div class="recommended-slider-wrapper">
                        <div class="recommended-slider owl-theme owl-carousel">
                            @foreach($latestProductsList as $product)
                                @if($product)
                                    @include('theme-views.partials._product-medium-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="most-searching">
                    <div class="recommended-slider-wrapper">
                        <div class="recommended-slider owl-theme owl-carousel">
                            @foreach($mostSearchingProducts as $product)
                                @if($product)
                                    @include('theme-views.partials._product-medium-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

