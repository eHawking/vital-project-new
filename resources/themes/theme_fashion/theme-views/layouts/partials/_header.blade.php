@php
    
    $user = auth('customer')->user();

    
    $isSeller = false;

    if ($user) {
        $isSeller = \DB::table('sellers')->where('username', $user->username)->exists();
    }
@endphp
@if (isset($web_config['announcement']) && $web_config['announcement']['status']==1)
    <div class="offer-bar d--none" data-bg-img="{{theme_asset('assets/img/media/top-offer-bg.png')}}">
        <div class="d-flex py-2 gap-2 align-items-center">
            <div class="offer-bar-close px-2">
                <i class="bi bi-x-lg"></i>
            </div>
            <div class="top-offer-text flex-grow-1 d-flex justify-content-center fw-semibold text-center">
                {{ $web_config['announcement']['announcement'] }}
            </div>
        </div>
    </div>
@endif
<style>
	.mobile-search-form-wrapper .input-group
 {
    padding-inline-start: 0px;
}
.mobile-search-form-wrapper .input-group::before {
    display: none;
}
</style>
<header class="header-section bg-base">
    <div class="container">
        <div class="header-wrapper">
            <a href="{{route('home')}}" class="logo me-xl-5">
                <img class="d-sm-none mobile-logo-cs"
                     src="{{ getStorageImages(path: $web_config['web_logo'], type: 'logo') }}" alt="{{ translate('logo') }}">
                <img class="d-none d-sm-block"
                     src="{{ getStorageImages(path: $web_config['web_logo'], type: 'logo') }}" alt="{{ translate('logo') }}">
            </a>
            <div class="menu-area text-capitalize flex-grow-1 ps-xl-4">
                <ul class="menu me-xl-4">
                    <li>
                        <a href="{{route('home')}}"
                           class="{{ Request::is('/')?'active':'' }}">{{ translate('home') }}</a>
                    </li>
                    @php($categories = \App\Utils\CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting())
                    <li>
                        <a href="javascript:">{{ translate('all_categories')}}</a>
                        <ul class="submenu">
                            @foreach($categories as $key => $category)
                                @if ($key <= 10)
                                    <li>
                                        <a class="py-2"
                                           href="{{route('products',['category_id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{$category['name']}}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($categories->count() > 10)
                                <li>
                                    <a href="{{route('products')}}" class="btn-text">{{ translate('view_all') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if($web_config['brand_setting'])
                        @php($brandList = \App\Utils\BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting())
                        @if(count($brandList) > 0)
                            <li>
                                <a href="javascript:">{{ translate('all_brand')}}</a>
                                <ul class="submenu">
                                    @foreach($brandList as $brandKey => $brandItem)
                                        @if ($brandKey <= 10)
                                            <li>
                                                <a class="py-2"
                                                   href="{{route('products',['brand_id' => $brandItem['id'],'data_from'=>'brand','brand_name'=>str_replace(' ', '_', $brandItem->name),'page'=>1])}}">
                                                    {{ $brandItem['name'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach

                                    @if ($brandItem->count() > 10)
                                        <li>
                                            <a href="{{ route('brands') }}" class="btn-text">
                                                {{ translate('view_all') }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('brands') }}"
                                   class="{{ Request::is('brands')?'active':'' }}">{{ translate('all_brand') }}</a>
                            </li>
                        @endif
                    @endif
                    <li>
                        <a href="{{route('products',['offer_type'=>'discounted','page'=>1])}}"
                           class="{{ request('data_from')=='discounted'?'active':'' }}">
                            {{ translate('offers') }}
                            <div class="offer-count flower-bg d-flex justify-content-center align-items-center offer-count-custom ">
                                {{ ($web_config['total_discount_products'] < 100 ? $web_config['total_discount_products']:'99+') }}
                            </div>
                        </a>
                    </li>

                  

                    @if($web_config['business_mode'] == 'multi')
                        <li>
                            <a href="{{route('vendors')}}"
                               class="{{ Request::is('vendors')?'active':'' }}">{{translate('vendors')}}</a>
                        </li>

                        @if ($web_config['seller_registration'])
                            <li class="d-sm-none">
                                <a href="{{route('vendor.auth.registration.index')}}"
                                   class="{{ Request::is('vendor/auth/registration/index')?'active':'' }}">{{translate('vendor_reg').'.'}}</a>
                            </li>
                        @endif
                    @endif

                </ul>

                <ul class="header-right-icons ms-auto">
				
					 <li class="dropdown">
                    <a href="javascript:" data-bs-toggle="dropdown">
                        <i class="bi bi-translate"></i>
                        <i class="ms-1 text-small bi bi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu __dropdown-menu">
                        <ul class="language">
                            @php( $local = \App\Utils\Helpers::default_lang())
                            @foreach($web_config['language'] as $key =>$data)
                                @if($data['status']==1)
                                    <li class="change-language {{ $data['code'] == $local ? 'active':'' }}" data-action="{{route('change-language')}}" data-language-code="{{$data['code']}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/flags/'.$data['code'].'.png') }}"
                                             alt="{{$data['name']}}">
                                        <span>{{ ucwords($data['name']) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>
					 <div class="darkLight-switcher d-none d-xl-block">
                        <button type="button" title="{{ translate('Dark_Mode') }}" class="dark_button">
                            <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/dark.svg')}}"
                                    alt="{{ translate('dark_Mode') }}">
                        </button>
                        <button type="button" title="{{ translate('Light_Mode') }}" class="light_button">
                            <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/light.svg')}}"
                                    alt="{{ translate('light_Mode') }}">
                        </button>
                    </div>
						<li>
                        <a href="javascript:" class="search-toggle d-none d-xl-block">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <li class="d-none d-xl-block">
                        @if(auth('customer')->check())
                            <a href="{{ route('wishlists') }}">
                                <div class="position-relative mt-1 px-8px">
                                    <i class="bi bi-heart"></i>
                                    <span class="btn-status wishlist_count_status">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                                </div>
                            </a>
                        @else
                            <a href="javascript:" class="customer_login_register_modal">
                                <div class="position-relative mt-1 px-8px">
                                    <i class="bi bi-heart"></i>
                                    <span class="btn-status">{{translate('0')}}</span>
                                </div>
                            </a>
                        @endif
                    </li>
                    <li id="cart_items">
                        @include('theme-views.layouts.partials._cart')
                    </li>
                    <li class="d-xl-none">
                        <a href="javascript:" class="search-toggle">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    @if(auth('customer')->check())
                        <li class="me-2 me-sm-0 dropdown">
                            <a href="javascript:" data-bs-toggle="dropdown">
                                <i class="bi bi-person d-none d-xl-inline-block"></i>
                                <i class="bi bi-person-circle d-xl-none"></i>
                                <span class="mx-1 d-none d-md-block">{{auth('customer')->user()->f_name}}</span>
                                <i class="ms-1 text-small bi bi-chevron-down d-none d-md-block"></i>
                            </a>
                            <div class="dropdown-menu __dropdown-menu">
                                <ul class="language">
									<li> 
                                       <span class="mx-1" style="text-transform: uppercase;">{{ translate('ID:') }} {{auth('customer')->user()->username}}</span>
                                    </li>
									
									 
									<li class="thisIsALinkElement" data-linkpath="/account/user/dashboard">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/profile.svg') }}" 
											 alt="{{ translate('user') }}">
                                        <span>{{ translate('dashboard') }}</span>
                                    </li>
								 <li class="thisIsALinkElement" data-linkpath="{{route('account-oder')}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/shopping-bag.svg') }}" 
											 alt="{{ translate('user') }}">
                                        <span>{{ translate('my_orders') }}</span>
                                    </li>
									<li class="thisIsALinkElement" data-linkpath="{{route('wallet')}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/shopping-bag.svg') }}" 
											 alt="{{ translate('user') }}">
                                        <span>{{ translate('my_wallet') }}</span>
                                    </li>
                                    <li class="thisIsALinkElement" data-linkpath="{{route('user-profile')}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/profile.svg') }}"
                                             alt="{{ translate('user') }}">
                                        <span>{{ translate('my_profile') }}</span>
                                    </li>
									@if($isSeller)
                                        <li class="thisIsALinkElement" data-linkpath="/vendor/dashboard">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/user/profile.svg') }}" 
												 alt="{{ translate('user') }}">
                                            <span>{{ translate('vendor_access') }}</span>
                                        </li>
                                    @endif
                                    <li class="thisIsALinkElement" data-linkpath="{{route('customer.auth.logout')}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/logout.svg') }}"
                                             alt="{{ translate('user') }}">
                                        <span>{{translate('sign_Out')}}</span>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="me-2 me-sm-0">
                            <a href="javascript:" class="customer_login_register_modal">
                                <i class="bi bi-person d-none d-xl-inline-block"></i>
                                <i class="bi bi-person-circle d-xl-none"></i>
                                <span class="mx-1 d-none d-md-block">{{ translate('login') }} / {{ translate('register') }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-toggle d-xl-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                        aria-controls="offcanvasRight">
                        <span></span>
                        <span></span>
                        <span></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-search-form-wrapper">
        <div class="search-form-header bg-base">
            <div class="d-flex w-100 align-items-center justify-content-center">
                <form class="search-form m-0 p-0 sidebar-search-form" action="{{route('products')}}" type="submit">
                    <div class="input-group search_input_group">
                       
                     
                        <input type="search" class="form-control action-global-search-mobile" id="input-value-mobile"
                            placeholder="{{ translate('search_for_items_or_store') }}..." name="name" autocomplete="off">

                        <button class="btn btn-base" type="submit"><i class="bi bi-search"></i></button>
                        <div class="card search-card position-absolute z-99 w-100 bg-white d-none top-100 start-0 search-result-box-mobile"></div>
                    </div>
                    <input name="data_from" value="search" hidden>
                    <input type="hidden" name="global_search_input" value="1">
                    <input name="page" value="1" hidden>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="search-toggle search-togle-overlay"></div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header justify-content-end">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body text-capitalize d-flex flex-column">
        <div>
            <ul class="menu scrollY-60 ">
              
                <li>
                    <a href="javascript:">{{ translate('all_categories') }}</a>
                    <ul class="submenu">
                        @foreach($categories as $key => $category)
                            @if ($key <= 10)
                                <li>
                                    <a class="py-2"
                                       href="{{route('products',['category_id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{$category['name']}}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($categories->count() > 10)
                            <li>
                                <a href="{{route('products')}}" class="btn-text">{{ translate('view_all') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if($web_config['brand_setting'])
                    @php($brandList = \App\Utils\BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting())
                    @if(count($brandList) > 0)
                        <li>
                            <a href="javascript:">{{ translate('all_brand') }}</a>
                            <ul class="submenu">
                                @foreach($brandList as $brandKey => $brandItem)
                                    @if ($brandKey <= 10)
                                        <li>
                                            <a class="py-2"
                                               href="{{route('products',['brand_id'=> $brandItem['id'],'data_from'=>'brand','brand_name'=>str_replace(' ', '_', $brandItem->name),'page'=>1])}}">
                                                {{ $brandItem['name'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($brandItem->count() > 10)
                                    <li>
                                        <a href="{{route('brands')}}" class="btn-text">{{ translate('view_all') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{{route('brands')}}">{{ translate('all_brand') }}</a>
                        </li>
                    @endif
                @endif
                <li>
                    <a class="d-flex align-items-center gap-2"
                       href="{{route('products',['offer_type'=>'discounted','page'=>1])}}">
                        {{ translate('offers') }}
                        <div class="offer-count flower-bg d-flex justify-content-center align-items-center offer-count-custom">
                            {{ ($web_config['total_discount_products'] < 100 ? $web_config['total_discount_products']:'99+') }}
                        </div>
                    </a>
                </li>

                

                @if($web_config['business_mode'] == 'multi')
                    <li>
                        <a href="{{route('vendors')}}">{{translate('vendors')}}</a>
                    </li>

                    @if ($web_config['seller_registration'])
                        <li class="d-sm-none">
                            <a href="{{route('vendor.auth.registration.index')}}">{{translate('vendor_reg').'.'}}</a>
                        </li>
                    @endif
                @endif

            </ul>
        </div>

        <div class="d-flex align-items-center gap-2 justify-content-between py-4 mt-3">
            <span class="text-dark">{{ translate('theme_mode') }}</span>
            <div class="theme-bar">
                <button class="light_button active">
                    <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/light.svg')}}"
                         alt="{{ translate('light_Mode') }}">
                </button>
                <button class="dark_button">
                    <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/dark.svg')}}" alt="{{ translate('dark_Mode') }}">
                </button>
            </div>
        </div>

        @if(auth('customer')->check())
            <div class="d-flex justify-content-center mb-2 pb-3 mt-auto px-4">
                <a href="{{route('customer.auth.logout')}}" class="btn btn-base w-100">{{ translate('logout') }}</a>
            </div>
        @else
            <div class="d-flex justify-content-center mb-2 pb-3 mt-auto px-4">
                <a href="javascript:" class="btn btn-base w-100 customer_login_register_modal">
                    {{ translate('login') }} / {{ translate('register') }}
                </a>
            </div>
        @endif
    </div>
</div>
