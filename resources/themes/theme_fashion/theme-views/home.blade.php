@extends('theme-views.layouts.app')

@section('title', $web_config['company_name'].' '.translate('online_shopping').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@push('css_or_js')
    <meta property="og:image" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="og:title" content="Welcome To {{$web_config['company_name']}} Home"/>
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta name="description" content="{{ $web_config['meta_description'] }}">
    <meta property="og:description" content="{{ $web_config['meta_description'] }}">
    <meta property="twitter:card" content="{{$web_config['web_logo']['path']}}"/>
    <meta property="twitter:title" content="Welcome To {{$web_config['company_name']}} Home"/>
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:description" content="{{ $web_config['meta_description'] }}">

    <!-- Premium Marketplace Styles -->
    <style>
        /* ===== PREMIUM MARKETPLACE THEME ===== */
        :root {
            --premium-primary: #8B5CF6;
            --premium-secondary: #EC4899;
            --premium-accent: #10B981;
            --premium-gold: #F59E0B;
            --premium-dark: #0F172A;
            --premium-card: #1E293B;
            --premium-border: rgba(255,255,255,0.08);
            --premium-text: #F1F5F9;
            --premium-muted: #94A3B8;
            --premium-gradient: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            --premium-gradient-accent: linear-gradient(135deg, #10B981 0%, #059669 100%);
            --premium-gradient-gold: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            --premium-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            --premium-glow: 0 0 40px rgba(139, 92, 246, 0.3);
        }

        /* Light Theme Override */
        body[theme="light"] {
            --premium-dark: #F8FAFC;
            --premium-card: #FFFFFF;
            --premium-border: rgba(0,0,0,0.08);
            --premium-text: #1E293B;
            --premium-muted: #64748B;
            --premium-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        /* Premium Background */
        body {
            background: var(--premium-dark);
        }

        /* Section Styling */
        .section-gap {
            padding-top: 60px;
            padding-bottom: 0;
        }

        /* Premium Section Titles */
        .section-title .title,
        .section-title-3 .title,
        h2.title {
            font-size: 28px;
            font-weight: 700;
            background: var(--premium-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
        }

        .section-title .title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--premium-gradient);
            border-radius: 2px;
        }

        /* Premium Cards - Product Cards */
        .product-card,
        .product-medium-card,
        .product__card,
        .flash-deal-slider .owl-item > div,
        .recommended-slider .owl-item > div {
            background: var(--premium-card) !important;
            border: 1px solid var(--premium-border) !important;
            border-radius: 16px !important;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .product-card:hover,
        .product-medium-card:hover,
        .product__card:hover {
            transform: translateY(-8px) !important;
            box-shadow: var(--premium-shadow) !important;
            border-color: var(--premium-primary) !important;
        }

        /* Product Image Container */
        .product-card .img-wrap,
        .product-medium-card .img-wrap,
        .product__card__img {
            position: relative;
            overflow: hidden;
            border-radius: 16px 16px 0 0;
        }

        .product-card .img-wrap img,
        .product-medium-card .img-wrap img {
            transition: transform 0.5s ease !important;
        }

        .product-card:hover .img-wrap img,
        .product-medium-card:hover .img-wrap img {
            transform: scale(1.08) !important;
        }

        /* Product Price Styling */
        .product-card .current-price,
        .product-medium-card .current-price,
        .product__price .current {
            color: var(--premium-accent) !important;
            font-weight: 700 !important;
            font-size: 18px !important;
        }

        .product-card .old-price,
        .product-medium-card .old-price,
        .product__price .old {
            color: var(--premium-muted) !important;
            text-decoration: line-through;
        }

        /* Premium Discount Badge */
        .discount-badge,
        .product__tag--discount,
        .discount-tag {
            background: var(--premium-gradient) !important;
            color: #fff !important;
            padding: 6px 14px !important;
            border-radius: 20px !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
        }

        /* Premium Add to Cart Button */
        .btn-base,
        .btn-primary,
        .add-to-cart-btn,
        button[type="submit"].btn {
            background: var(--premium-gradient) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            color: #fff !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3);
        }

        .btn-base:hover,
        .btn-primary:hover,
        .add-to-cart-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.5) !important;
        }

        /* Secondary Buttons */
        .btn-secondary,
        .btn-outline {
            background: transparent !important;
            border: 2px solid var(--premium-primary) !important;
            color: var(--premium-primary) !important;
            border-radius: 12px !important;
        }

        .btn-secondary:hover,
        .btn-outline:hover {
            background: var(--premium-gradient) !important;
            color: #fff !important;
        }

        /* Categories Section - Premium Grid */
        .most-visited-category {
            background: linear-gradient(180deg, transparent 0%, rgba(139, 92, 246, 0.03) 100%);
            padding: 60px 0;
        }

        .most-visited-item {
            background: var(--premium-card) !important;
            border: 1px solid var(--premium-border) !important;
            border-radius: 20px !important;
            overflow: hidden;
            transition: all 0.4s ease !important;
            position: relative;
        }

        .most-visited-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--premium-gradient);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
        }

        .most-visited-item:hover::before {
            opacity: 0.8;
        }

        .most-visited-item:hover {
            transform: translateY(-10px) scale(1.02) !important;
            box-shadow: var(--premium-glow) !important;
        }

        .most-visited-item .cont {
            z-index: 2;
            position: relative;
        }

        /* Flash Deals Section */
        .flash-deals-slider {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
            border-radius: 24px;
            padding: 40px 0;
            margin: 20px 0;
        }

        .flash-deals-wrapper {
            background: var(--premium-gradient) !important;
            border-radius: 16px !important;
            padding: 20px 30px !important;
            box-shadow: var(--premium-glow);
        }

        .flash-deals-wrapper .countdown li {
            background: rgba(255,255,255,0.15) !important;
            backdrop-filter: blur(10px);
            border-radius: 12px !important;
            padding: 10px 15px !important;
            margin: 0 5px !important;
        }

        .flash-deals-wrapper .countdown h6 {
            font-size: 24px !important;
            font-weight: 700 !important;
            color: #fff !important;
        }

        /* See All Link */
        .see-all {
            background: var(--premium-gradient) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            font-weight: 600 !important;
            position: relative;
            padding-right: 20px;
        }

        .see-all::after {
            content: '→';
            position: absolute;
            right: 0;
            background: var(--premium-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: transform 0.3s ease;
        }

        .see-all:hover::after {
            transform: translateX(5px);
        }

        /* Navigation Arrows */
        .owl-prev,
        .owl-next,
        .flash-prev,
        .flash-next,
        .recommended-prev,
        .recommended-next {
            width: 48px !important;
            height: 48px !important;
            background: var(--premium-card) !important;
            border: 1px solid var(--premium-border) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: var(--premium-text) !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .owl-prev:hover,
        .owl-next:hover,
        .flash-prev:hover,
        .flash-next:hover,
        .recommended-prev:hover,
        .recommended-next:hover {
            background: var(--premium-gradient) !important;
            border-color: transparent !important;
            color: #fff !important;
            transform: scale(1.1);
        }

        /* Tab Navigation */
        .nav--tabs {
            background: var(--premium-card);
            border: 1px solid var(--premium-border);
            border-radius: 12px;
            padding: 5px;
        }

        .nav--tabs li a {
            color: var(--premium-muted) !important;
            border-radius: 8px !important;
            padding: 10px 20px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .nav--tabs li a.active,
        .nav--tabs li a:hover {
            background: var(--premium-gradient) !important;
            color: #fff !important;
        }

        /* Promo Banners */
        .promotional-banner,
        .promo-1,
        .promo-banner img,
        .img1 img,
        .img2 img,
        .img3 img {
            border-radius: 20px !important;
            overflow: hidden;
            box-shadow: var(--premium-shadow);
            transition: transform 0.4s ease !important;
        }

        .promotional-banner:hover,
        .promo-1:hover,
        .img1:hover img,
        .img2:hover img,
        .img3:hover img {
            transform: scale(1.02) !important;
        }

        /* Deal of the Day */
        .deal-of-the-day {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%);
            border-radius: 24px;
            padding: 40px;
        }

        /* Top Stores */
        .top-stores-section,
        .other-stores-section {
            background: linear-gradient(180deg, transparent 0%, rgba(139, 92, 246, 0.02) 100%);
        }

        .store-card,
        .vendor-card {
            background: var(--premium-card) !important;
            border: 1px solid var(--premium-border) !important;
            border-radius: 20px !important;
            overflow: hidden;
            transition: all 0.4s ease !important;
        }

        .store-card:hover,
        .vendor-card:hover {
            transform: translateY(-8px) !important;
            box-shadow: var(--premium-shadow) !important;
            border-color: var(--premium-primary) !important;
        }

        /* Featured Products */
        .featured-product-section {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.03) 0%, rgba(217, 119, 6, 0.03) 100%);
        }

        /* All Products Grid */
        .all-products-home .product-card {
            margin-bottom: 24px;
        }

        /* How To Section */
        .how-to-section {
            background: var(--premium-card);
            border-radius: 24px;
            padding: 60px 40px;
            margin: 40px 0;
            border: 1px solid var(--premium-border);
        }

        .how-to-section .step-item {
            text-align: center;
            padding: 30px;
        }

        .how-to-section .step-icon {
            width: 80px;
            height: 80px;
            background: var(--premium-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
        }

        .how-to-section .step-icon i,
        .how-to-section .step-icon img {
            font-size: 32px;
            color: #fff;
            filter: brightness(0) invert(1);
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--premium-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--premium-gradient);
            border-radius: 4px;
        }

        /* Wishlist & Compare Icons */
        .wishlist-btn,
        .compare-btn,
        .quick-view-btn {
            width: 40px !important;
            height: 40px !important;
            background: var(--premium-card) !important;
            border: 1px solid var(--premium-border) !important;
            border-radius: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
        }

        .wishlist-btn:hover,
        .compare-btn:hover,
        .quick-view-btn:hover {
            background: var(--premium-gradient) !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        /* Rating Stars */
        .rating i,
        .star-rating i {
            color: var(--premium-gold) !important;
        }

        /* Footer Enhancement */
        footer,
        .footer-section {
            background: var(--premium-dark) !important;
            border-top: 1px solid var(--premium-border);
        }

        /* Search Form */
        .search-form input,
        .search-input {
            background: var(--premium-card) !important;
            border: 1px solid var(--premium-border) !important;
            border-radius: 12px !important;
            color: var(--premium-text) !important;
            padding: 15px 20px !important;
        }

        .search-form input:focus,
        .search-input:focus {
            border-color: var(--premium-primary) !important;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1) !important;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .section-gap {
                padding-top: 40px;
            }

            .section-title .title,
            h2.title {
                font-size: 22px;
            }

            .flash-deals-wrapper {
                padding: 15px 20px !important;
            }

            .flash-deals-wrapper .countdown li {
                padding: 8px 10px !important;
                margin: 0 3px !important;
            }

            .flash-deals-wrapper .countdown h6 {
                font-size: 18px !important;
            }

            .how-to-section {
                padding: 30px 20px;
            }
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
            50% { box-shadow: 0 0 40px rgba(139, 92, 246, 0.6); }
        }

        .featured-badge {
            animation: pulse-glow 2s infinite;
        }

        /* Premium Loading Skeleton */
        .skeleton-loading {
            background: linear-gradient(90deg, var(--premium-card) 25%, rgba(255,255,255,0.1) 50%, var(--premium-card) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
@endpush

@section('content')

    @include('theme-views.partials._banner-section')

    <div class="container d-none d-xl-block">
        @include('theme-views.layouts.partials._search-form-partials')
    </div>

    @if ($categories->count() > 0)
        @include('theme-views.partials._categories')
    @endif

    @if ($bannerTypePromoBannerMiddleTop)
        <div class="container d-sm-none mt-3">
            <a href="{{ $bannerTypePromoBannerMiddleTop['url'] }}" target="_blank" class="img1 promo-1">
                <img loading="lazy" class="img-fluid" alt="{{ translate('banner') }}" src="{{ getStorageImages(path: $bannerTypePromoBannerMiddleTop['photo_full_url'], type: 'banner') }}">
            </a>
        </div>
    @endif

    @if ($flashDeal['flashDeal'] && $flashDeal['flashDealProducts']  && count($flashDeal['flashDealProducts']) > 0)
        @include('theme-views.partials._flash-deals')
    @endif

    @include('theme-views.partials._clearance-sale')

    @if ($bannerTypePromoBannerLeft)
        <div class="container d-sm-none overflow-hidden pt-4">
            <a href="{{ $bannerTypePromoBannerLeft['url'] }}" target="_blank" class="img3 img-fluid">
                <img loading="lazy" src="{{ getStorageImages(path: $bannerTypePromoBannerLeft['photo_full_url'], type:'banner') }}"
                class="img-fluid" alt="{{ translate('banner') }}">
            </a>
        </div>
    @endif

    @include('theme-views.partials._recommended-product')

    @if ($bannerTypePromoBannerLeft && $bannerTypePromoBannerMiddleTop && $bannerTypePromoBannerMiddleBottom && $bannerTypePromoBannerRight)
        @include('theme-views.partials._promo-banner')
    @endif

    @include('theme-views.partials._deal-of-the-day')

    @if ($bannerTypePromoBannerMiddleBottom)
        <div class="container d-sm-none overflow-hidden pt-4">
            <a href="{{ $bannerTypePromoBannerMiddleBottom['url'] }}" target="_blank" class="img2">
                <img loading="lazy" src="{{ getStorageImages(path: $bannerTypePromoBannerMiddleBottom['photo_full_url'], type:'banner') }}"
                class="img-fluid" alt="{{ translate('banner') }}">
            </a>
        </div>
    @endif

    @include('theme-views.partials.__featured-product')

    @include('theme-views.partials._all-products-home')

    @include('theme-views.partials._signature-product')

    @if ($web_config['business_mode'] == 'multi' && count($topVendorsList) > 0)
        @include('theme-views.partials._top-stores')
    @endif

    @if ($bannerTypePromoBannerRight)
        <div class="container d-sm-none overflow-hidden pt-4">
            <a href="{{ $bannerTypePromoBannerRight['url'] }}" target="_blank" class="d-block promotional-banner">
                <img loading="lazy" src="{{ getStorageImages(path: $bannerTypePromoBannerRight['photo_full_url'], type:'banner') }}"
                class="w-100 img-fluid" alt="{{ translate('banner') }}">
            </a>
        </div>
    @endif

    @include('theme-views.partials._most-demanded-product')

    @if ($web_config['business_mode'] == 'multi' && getCustomerFromQuery() && count($recentOrderShopList)>0)
        @include('theme-views.partials._recent-ordered-shops')
    @endif

    @if ($bannerTypePromoBannerBottom)
        <div class="container">
            <div class="mt-32px">
                <a href="{{ $bannerTypePromoBannerBottom->url }}" target="_blank" class="d-block promotional-banner">
                    <img loading="lazy" class="w-100 rounded aspect-ratio-8-1" alt="{{ translate('banner') }}"
                         src="{{ getStorageImages(path: $bannerTypePromoBannerBottom['photo_full_url'], type:'banner') }}">
                </a>
            </div>
        </div>
    @endif

    @if ($web_config['business_mode'] == 'multi')
        @include('theme-views.partials._other-stores')
    @endif

    @include('theme-views.partials._how-to-section')

@endsection

@if ($bannerTypeMainBanner->count() <= 1)
@push('script')
    <script src="{{ theme_asset('assets/js/home-blade.js') }}"></script>
@endpush
@endif
