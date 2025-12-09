@if ($bannerTypeMainBanner->count() > 0)
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/> 

    <style>
        /* Premium Banner Section */
        .premium-banner-section {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        }
        body[theme="light"] .premium-banner-section {
            background: linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
        }
        .premium-banner-section .swiper {
            border-radius: 0 0 30px 30px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .premium-banner-section .swiper-slide {
            position: relative;
        }
        .premium-banner-section .swiper-slide::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 100%);
            pointer-events: none;
            z-index: 2;
        }
        .premium-banner-section .swiper-slide a {
            display: block;
            position: relative;
        }
        .premium-banner-section .swiper-slide img {
            width: 100%;
            height: auto;
            min-height: 300px;
            object-fit: cover;
            transition: transform 8s ease;
        }
        .premium-banner-section .swiper-slide-active img {
            transform: scale(1.05);
        }
        .premium-banner-section .swiper-pagination {
            bottom: 20px !important;
        }
        .premium-banner-section .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: rgba(255,255,255,0.5);
            opacity: 1;
            transition: all 0.3s ease;
        }
        .premium-banner-section .swiper-pagination-bullet-active {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            width: 35px;
            border-radius: 6px;
        }
        .premium-banner-section .swiper-button-next,
        .premium-banner-section .swiper-button-prev {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .premium-banner-section .swiper-button-next:hover,
        .premium-banner-section .swiper-button-prev:hover {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        }
        .premium-banner-section .swiper-button-next::after,
        .premium-banner-section .swiper-button-prev::after {
            font-size: 18px;
            font-weight: bold;
            color: #1E293B;
            transition: color 0.3s ease;
        }
        .premium-banner-section .swiper-button-next:hover::after,
        .premium-banner-section .swiper-button-prev:hover::after {
            color: #fff;
        }
        @media (max-width: 768px) {
            .premium-banner-section .swiper {
                border-radius: 0 0 20px 20px;
            }
            .premium-banner-section .swiper-slide img {
                min-height: 200px;
            }
            .premium-banner-section .swiper-button-next,
            .premium-banner-section .swiper-button-prev {
                width: 36px;
                height: 36px;
            }
            .premium-banner-section .swiper-button-next::after,
            .premium-banner-section .swiper-button-prev::after {
                font-size: 14px;
            }
        }
    </style>

    <section class="premium-banner-section custom-height">
        <div class="swiper main-banner-slider">
            <div class="swiper-wrapper">
                @foreach ($bannerTypeMainBanner as $banner)
                    <div class="swiper-slide">
                        <a href="{{ $banner['url'] }}" class="d-block" target="_blank">
                            <img class="w-100 __slide-img __slide-img-170"
                                 src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}"
                                 alt="Banner">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> 

    <!-- Initialize Swiper -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.main-banner-slider', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                speed: 1200,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                allowTouchMove: true,
            });
        });
    </script>
@else
    <section class="promo-page-header">
        <div class="product_blank_banner" style="background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%); height: 200px; border-radius: 0 0 30px 30px;"></div>
    </section>
@endif