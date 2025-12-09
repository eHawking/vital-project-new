<style>
    /* Premium Top Stores Section */
    .premium-top-stores {
        padding: 60px 0;
        background: linear-gradient(180deg, transparent 0%, rgba(16, 185, 129, 0.03) 50%, transparent 100%);
    }
    .premium-stores-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 35px;
    }
    .premium-stores-title-wrap {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .premium-stores-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .premium-stores-icon i {
        font-size: 24px;
        color: #fff;
    }
    .premium-stores-title {
        font-size: 28px;
        font-weight: 800;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }
    .premium-stores-nav {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .premium-stores-nav .premium-nav-btn {
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
    body[theme="light"] .premium-stores-nav .premium-nav-btn {
        background: #ffffff;
        border-color: rgba(0,0,0,0.1);
        color: #1E293B;
    }
    .premium-stores-nav .premium-nav-btn:hover {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        border-color: transparent;
        color: #fff;
        transform: scale(1.1);
    }
    .premium-stores-see-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
    }
    .premium-stores-see-all i {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: transform 0.3s ease;
    }
    .premium-stores-see-all:hover i {
        transform: translateX(5px);
    }
    .premium-store-card {
        background: var(--premium-card, #1E293B);
        border: 1px solid var(--premium-border, rgba(255,255,255,0.08));
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s ease;
        cursor: pointer;
    }
    body[theme="light"] .premium-store-card {
        background: #ffffff;
        border-color: rgba(0,0,0,0.08);
    }
    .premium-store-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 60px rgba(16, 185, 129, 0.2);
        border-color: #10B981;
    }
    .premium-store-banner {
        position: relative;
        height: 120px;
        overflow: hidden;
    }
    .premium-store-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .premium-store-card:hover .premium-store-banner img {
        transform: scale(1.1);
    }
    .premium-store-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 100%);
    }
    .premium-store-logo {
        position: absolute;
        bottom: -35px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 80px;
        border-radius: 20px;
        border: 4px solid var(--premium-card, #1E293B);
        overflow: hidden;
        z-index: 2;
        background: var(--premium-card, #1E293B);
    }
    body[theme="light"] .premium-store-logo {
        border-color: #ffffff;
        background: #ffffff;
    }
    .premium-store-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .premium-store-closed-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(239, 68, 68, 0.9);
        color: #fff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        z-index: 3;
    }
    .premium-store-content {
        padding: 50px 20px 20px;
        text-align: center;
    }
    .premium-store-stats {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .premium-store-stat {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #10B981;
    }
    .premium-store-stat i {
        font-size: 14px;
    }
    .premium-store-stat.rating {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }
    .premium-store-visit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 24px;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: #fff;
        border: none;
        border-radius: 14px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .premium-store-visit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        color: #fff;
    }
    @media (max-width: 768px) {
        .premium-top-stores {
            padding: 40px 0;
        }
        .premium-stores-header {
            flex-direction: column;
            text-align: center;
        }
        .premium-stores-title {
            font-size: 22px;
        }
    }
</style>

<section class="premium-top-stores section-gap pb-0">
    <div class="container">
        <div class="premium-stores-header">
            <div class="premium-stores-title-wrap">
                <div class="premium-stores-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <h2 class="premium-stores-title">{{ translate('top_Fashion_House') }}</h2>
            </div>
            
            <div class="premium-stores-nav">
                <div class="premium-nav-btn fashion-prev">
                    <i class="bi bi-chevron-left"></i>
                </div>
                <div class="premium-nav-btn fashion-next">
                    <i class="bi bi-chevron-right"></i>
                </div>
                <a href="{{ route('vendors', ['filter'=>'top-vendors']) }}" class="premium-stores-see-all">
                    {{ translate('see_all') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="overflow-hidden">
            <div class="fashion-house-slider-wrapper">
                <div class="fashion-house-slider owl-theme owl-carousel">
                    @foreach($topVendorsList as $vendorData)
                        <div class="premium-store-card thisIsALinkElement"
                             data-linkpath="{{route('shopView',['id'=> $vendorData['id']])}}">
                            <div class="premium-store-banner">
                                <img loading="lazy" alt="{{ translate('banner') }}"
                                     src="{{ getStorageImages(path: $vendorData->banner_full_url, type: 'shop-banner') }}">
                                
                                <div class="premium-store-logo">
                                    <img loading="lazy" alt="{{ translate('shop') }}" title="{{ $vendorData->name }}"
                                         src="{{ getStorageImages(path: $vendorData->image_full_url, type: 'shop') }}">
                                </div>
                                
                                @if($vendorData->temporary_close)
                                    <span class="premium-store-closed-badge">
                                        {{translate('Temporary_OFF')}}
                                    </span>
                                @elseif(($vendorData->vacation_status && ($currentDate >= $vendorData->vacation_start_date) && ($currentDate <= $vendorData->vacation_end_date)))
                                    <span class="premium-store-closed-badge">
                                        {{translate('closed_now')}}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="premium-store-content">
                                <div class="premium-store-stats">
                                    <span class="premium-store-stat rating">
                                        <i class="bi bi-star-fill"></i> {{ round($vendorData->average_rating ,1) }}
                                    </span>
                                    <span class="premium-store-stat">
                                        <i class="bi bi-box"></i> {{ $vendorData->products_count > 99 ? '99+' : $vendorData->products_count }}
                                    </span>
                                </div>
                                <a href="{{route('shopView',['id'=>$vendorData['id']])}}"
                                   class="premium-store-visit-btn">
                                    {{ translate('visit_store') }} <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
