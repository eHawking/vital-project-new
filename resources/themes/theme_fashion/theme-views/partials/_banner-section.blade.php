@if ($bannerTypeMainBanner->count() > 0)
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/> 

    <section class="custom-height">
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
                    delay: 4000,
                    disableOnInteraction: false,
                },
                speed: 1000, // Smooth transition duration (1 second)
                effect: 'fade', // Smooth fade animation
                fadeEffect: {
                    crossFade: true
                },
                allowTouchMove: true, // Optional: disable if you want only autoplay
            });
        });
    </script>
@else
    <section class="promo-page-header">
        <div class="product_blank_banner"></div>
    </section>
@endif