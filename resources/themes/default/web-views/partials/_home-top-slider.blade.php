@if(count($bannerTypeMainBanner) > 0)
    <section class="bg-transparent py-3">
        <div class="container position-relative">
            <div class="row no-gutters position-relative rtl">
                <!-- Category Menu (Only visible on XL screens) -->
                @if ($categories->count() > 0)
                    <div class="col-xl-3 position-static d-none d-xl-block __top-slider-cate">
                        <div class="category-menu-wrap position-static">
                            <ul class="category-menu mt-0">
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('products', ['category_id' => $category['id'], 'data_from' => 'category', 'page' => 1]) }}">
                                            <span class="d-flex gap-10px justify-content-start align-items-center">
                                                <img class="aspect-1 rounded-circle" width="20"
                                                    src="{{ getStorageImages(path: $category?->icon_full_url, type: 'category') }}"
                                                    alt="{{ $category['name'] }}">
                                                <span class="line--limit-2">{{ $category->name }}</span>
                                            </span>
                                        </a>

                                        <!-- Subcategories Dropdown -->
                                        @if ($category->childes->count() > 0)
                                            <div class="mega_menu z-2">
                                                @foreach ($category->childes as $sub_category)
                                                    <div class="mega_menu_inner">
                                                        <h6>
                                                            <a href="{{ route('products', ['sub_category_id' => $sub_category['id'], 'data_from' => 'category', 'page' => 1]) }}">
                                                                {{ $sub_category->name }}
                                                            </a>
                                                        </h6>
                                                        <!-- Sub-Subcategories -->
                                                        @if ($sub_category->childes->count() > 0)
                                                            @foreach ($sub_category->childes as $sub_sub_category)
                                                                <div>
                                                                    <a href="{{ route('products', ['sub_sub_category_id' => $sub_sub_category['id'], 'data_from' => 'category', 'page' => 1]) }}">
                                                                        {{ $sub_sub_category->name }}
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </li>
                                @endforeach

                                <!-- View All Categories -->
                                <li class="text-center">
                                    <a href="{{ route('categories') }}" class="text-primary font-weight-bold justify-content-center text-capitalize">
                                        {{ translate('view_all') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Banner Slider -->
                <div class="col-12 col-xl-9 __top-slider-images">
                    <div class="{{ Session::get('direction') === 'rtl' ? 'pr-xl-2' : 'pl-xl-2' }}">
                        <!-- Owl Carousel -->
                        <div class="owl-theme owl-carousel hero-slider"
                            data-loop="{{ count($bannerTypeMainBanner) > 1 ? 1 : 0 }}"
                            data-autoplay="true"
                            data-autoplay-timeout="3000"
                            data-autoplay-hover-pause="true">
                            @foreach ($bannerTypeMainBanner as $banner)
                                <a href="{{ $banner['url'] }}" class="d-block" target="_blank">
                                    <img class="w-100 __slide-img __slide-img-170"
                                        src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}"
                                        alt="">
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<style>
    .owl-carousel .owl-item img {
        transition: opacity 0.8s ease-in-out;
        backface-visibility: hidden;
        will-change: transform, opacity;
    }
</style>

@push('script')
    <script>
        // Initialize Owl Carousel
        $(document).ready(function () {
            $('.hero-slider').owlCarousel({
                loop: $('.hero-slider').data('loop') === 1,
                margin: 10,
                nav: false, 
                dots: false,
                autoplay: $('.hero-slider').data('autoplay'),
                autoplayTimeout: $('.hero-slider').data('autoplay-timeout') || 3000,
                autoplayHoverPause: $('.hero-slider').data('autoplay-hover-pause'),
                smartSpeed: 800, 
                fluidSpeed: 800, 
                animateOut: 'fadeOut', 
                animateIn: 'fadeIn',   
                responsive: {
                    0: { items: 1 },
                    600: { items: 1 },
                    1000: { items: 1 }
                }
            });
        });
    </script>
@endpush