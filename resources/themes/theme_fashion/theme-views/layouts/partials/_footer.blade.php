<footer class="footer">
    <div class="container">
        <div class="newsletter-wrapper">
            <div class="newsletter-wrapper-inner">
                <div class="cont">
                    <h2 class="title h5">{{ translate('newsletter') }}</h2>
                    <p>{{ translate('be_the_first_one_to_know_about_discounts_offers_and_events') }}</p>
                </div>
                <form class="newsletter-form" action="{{ route('subscription') }}" method="post">
                    @csrf
                    <div class="position-relative">
                        <label class="position-relative m-0 d-block">
                            <i class="bi bi-envelope-at envelop-icon"></i>
                            <input type="text" placeholder="{{ translate('enter_your_email') }}" class="form-control"
                                   name="subscription_email" required>
                        </label>
                        <button type="submit" class="btn btn-base">{{ translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="footer-top">
        <div class="container"></div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="pb-3">
                <div class="row">
                    <div class="col-lg-6 border-right-lg">
                        <div class="footer-top-wrapper flex-column">
                            <a href="{{ route('home') }}" class="logo">
                                <img loading="lazy" alt="{{ translate('logo') }}" class="max-height-45px w-auto"
                                     src="{{ getStorageImages(path: $web_config['footer_logo'], type:'logo') }}">
                            </a>
                            <div class="content line-limit w-100">
                                <p class="txt"></p>
                                <?php
                                $aboutUsDescription = $web_config['business_pages']?->firstWhere('slug', 'about-us');
                                $aboutUsDescription = $aboutUsDescription?->description ?? '';
                                ?>
                                {{ Str::limit((strip_tags(str_replace('&nbsp;', ' ', $aboutUsDescription))), 180) }}
                                <span class="get-view-by-onclick link-primary cursor-pointer"
                                      data-link="{{ route('business-page.view', ['slug' => 'about-us']) }}">{{ translate('read_more') }}
                                </span>
								<div class="info-section">
        <p>NTN Number: A109273-4</p>
        <p>SECP Corporate No: 
            <a href="https://leap.secp.gov.pk/#/verify-company-info/0267422" target="_blank">
                0267422
            </a>
        </p>
        <p>SCCI Member ID: 
            <a href="https://scci.com.pk/member-details/?id=A-65138" target="_blank">
                A-65138
            </a>
        </p>
    </div>
                            </div>
                        </div>
                        <div class="footer-address">
                            <div class="footer-address-card d-flex gap-3">
                                <img loading="lazy" src="{{ theme_asset('assets/img/footer/address/pin.png') }}"
                                     alt="{{ translate('address') }}">
                                <div>
                                    <h6 class="name">{{ translate('address') }}</h6>
                                    <a href="https://www.google.com/maps/place/{{ getWebConfig(name: 'shop_address') }}"
                                       target="_blank" class="text-dark">
                                        {{ getWebConfig(name: 'shop_address') }}
                                    </a>
                                </div>
                            </div>
                            <div class="footer-address-card d-flex gap-3">
                                <img loading="lazy" src="{{ theme_asset('assets/img/footer/address/envelop.png') }}"
                                     alt="{{ translate('address') }}">
                                <div>
                                    <h6 class="name">{{ translate('email') }}</h6>
                                    <a class="text-dark" href="mailto:{{ $web_config['email'] }}">
                                        {{ $web_config['email'] }}
                                    </a>
                                </div>
                            </div>
                            <div class="footer-address-card d-flex gap-3">
                                <img loading="lazy" src="{{ theme_asset('assets/img/footer/address/phone.png') }}"
                                     alt="{{ translate('address') }}">
                                <div>
                                    <h6 class="name">{{ translate('hotline') }}</h6>
                                    <div>
                                        <a href="tel:{{ $web_config['phone'] }}" class="text-dark direction-ltr">
                                            {{ $web_config['phone'] }}
                                        </a>
										<br>
  <a href="tel:+923076194295" class="text-dark direction-ltr">
    +923076194295
  </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ps-xl-5">
                            <div class="footer-bottom-wrapper text-capitalize">
                                <div class="footer-widget">
                                    <h5 class="title">{{ translate('accounts') }}</h5>
                                    <ul class="links">
                                        <li>
                                            @if(auth('customer')->check())
                                                <a href="{{ route('user-profile') }}">
                                                    {{ translate('profile_info') }}
                                                </a>
                                            @else
                                                <a href="javascript:" class="customer_login_register_modal">
                                                    {{ translate('profile_info') }}
                                                </a>
                                            @endif
                                        </li>
                                        <li>
                                            @if(auth('customer')->check())
                                                <a href="{{ route('account-oder') }}">
                                                    {{ translate('orders') }}
                                                </a>
                                            @else
                                                <a href="javascript:"
                                                   class="customer_login_register_modal">
                                                    {{ translate('orders') }}
                                                </a>
                                            @endif
                                        </li>

                                        @if ($web_config['ref_earning_status'])
                                            <li>
                                                @if(auth('customer')->check())
                                                    <a href="{{ route('refer-earn') }}">
                                                        {{ translate('refer_&_earn') }}
                                                    </a>
                                                @else
                                                    <a href="javascript:"
                                                       class="customer_login_register_modal">
                                                        {{ translate('refer_&_earn') }}
                                                    </a>
                                                @endif
                                            </li>
                                        @endif

                                        <li>
                                            <a href="{{ route('helpTopic') }}">
                                                {{ translate('FAQs') }}
                                            </a>
                                        </li>

                                        @if(Route::has('frontend.blog.index') && getWebConfig(name: 'blog_feature_active_status'))
                                            <li>
                                                <a href="{{ route('frontend.blog.index') }}">
                                                    {{ translate('blogs') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if($web_config['business_mode'] == 'multi')
                                            <li>
                                                <a href="{{ route('vendor.auth.registration.index') }}">
                                                    {{ translate('sell_on') }} {{ $web_config['company_name'] }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="footer-widget">
                                    <h5 class="title">{{ translate('support') }}</h5>
                                    <ul class="links">
                                        <li>
                                            @if(auth('customer')->check())
                                                <a href="{{ route('chat', ['type' => 'vendor']) }}">
                                                    {{ translate('live_chat') }}
                                                </a>
                                            @else
                                                <a href="javascript:"
                                                   class="customer_login_register_modal">
                                                    {{ translate('live_chat') }}
                                                </a>
                                            @endif
                                        </li>
                                        <li>
                                            @if(auth('customer')->check())
                                                <a href="{{ route('account-tickets') }}">
                                                    {{ translate('support_ticket') }}
                                                </a>
                                            @else
                                                <a href="javascript:" class="customer_login_register_modal">
                                                    {{ translate('support_ticket') }}
                                                </a>
                                            @endif
                                        </li>
                                        <li>
                                            <a href="{{ route('track-order.index') }}">
                                                {{ translate('track_order') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('contacts') }}">
                                                {{ translate('contact_us') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="footer-widget ">
                                    <h5 class="title">{{ translate('quick_links') }}</h5>
                                    <ul class="links">
                                        @foreach($web_config['business_pages']->where('default_status', 1) as $businessPage)
                                            <li>
                                                <a href="{{ route('business-page.view', ['slug' => $businessPage['slug']]) }}">
                                                    {{ Str::limit($businessPage['title'], 25, '...') }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-top py-20px">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5 class="footer-tags-title text-capitalize">{{ translate('popular_tags') }}</h5>
                        <ul class="tags">

                            @foreach ($web_config['tags'] as $item)
                                <li>
                                    <a href="{{ route('products') }}?search_category_value=all&name={{ str_replace(' ','+', trim($item->tag)) }}&data_from=search&page=1">
                                        {{ Str::limit($item->tag, 25, '...') }}
                                    </a>
                                </li>
                            @endforeach

                            @if ($web_config['tags']->count() == 0)
                                <li>
                                    <a href="javascript:">{{ translate('no_Data_Found') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="">
                            @if(isset($web_config['android']['status']) && $web_config['android']['status'] || isset($web_config['ios']['status']) && $web_config['ios']['status'])
                                <h5 class="footer-tags-title text-capitalize">{{ translate('download_our_app') }}</h5>
                                <div class="app-btns">
                                    @if(isset($web_config['android']['status']) && $web_config['android']['status'])
                                        <a href="{{ $web_config['android']['link'] }}">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/app-btn/google.png') }}"
                                                 alt="{{ translate('google') }}">
                                        </a>
                                    @endif

                                    @if(isset($web_config['ios']['status']) && $web_config['ios']['status'])
                                        <a href="{{ $web_config['ios']['link'] }}">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/app-btn/apple.png') }}"
                                                 alt="{{ translate('apple') }}">
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ps-xl-5">
                            <h5 class="footer-tags-title text-capitalize">{{ translate('social_media') }}</h5>
                            @if($web_config['social_media'])
                                <ul class="social-icons">
                                    @foreach ($web_config['social_media'] as $item)
                                        <li>
                                            @if ($item->name == "twitter")
                                                <a href="{{ $item->link}}" target="_blank" class="font-bold">
                                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="18"
                                                         height="18" viewBox="0 0 24 24">
                                                        <g opacity=".3">
                                                            <polygon fill="#fff" fill-rule="evenodd"
                                                                     points="16.002,19 6.208,5 8.255,5 18.035,19"
                                                                     clip-rule="evenodd"></polygon>
                                                            <polygon
                                                                points="8.776,4 4.288,4 15.481,20 19.953,20 8.776,4"></polygon>
                                                        </g>
                                                        <polygon fill-rule="evenodd"
                                                                 points="10.13,12.36 11.32,14.04 5.38,21 2.74,21"
                                                                 clip-rule="evenodd"></polygon>
                                                        <polygon fill-rule="evenodd"
                                                                 points="20.74,3 13.78,11.16 12.6,9.47 18.14,3"
                                                                 clip-rule="evenodd"></polygon>
                                                        <path
                                                            d="M8.255,5l9.779,14h-2.032L6.208,5H8.255 M9.298,3h-6.93l12.593,18h6.91L9.298,3L9.298,3z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ $item->link}}" target="_blank">
                                                    <i class="{{ $item->icon}}"></i>
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-base py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="d-flex flex-column gap-3">
                            <div class="text-center text-white">
                                {{ $web_config['copyright_text'] }}
                            </div>

                            @if(count($web_config['business_pages']->where('default_status', 0)) > 0)
                                <ul class="links d-flex flex-wrap justify-content-center align-content-center flex-column flex-sm-row column-gap-1 row-gap-2 m-0">
                                    @foreach($web_config['business_pages']->where('default_status', 0) as $businessPage)
                                        <li class="opacity-75 text-absolute-white list-style-unset">
                                            <a href="{{ route('business-page.view', ['slug' => $businessPage['slug']]) }}"
                                               class="text-white">
                                                {{ Str::limit($businessPage['title'], 25, '...') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<style>
    .read-more-link {
        margin-right: 15px; 
    }

    .info-section {
        display: flex;
        flex-direction: row;
        gap: 20px; 
    }

    .info-section p {
        margin-right: 15px; 
    }
</style>