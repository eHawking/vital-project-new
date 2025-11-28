@extends('layouts.front-end.app')

@section('title', auth('customer')->user()->f_name.' '.auth('customer')->user()->l_name)

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
    <div class="container py-2 py-md-4 p-0 p-md-2 user-profile-container px-5px">
        <div class="row">
            @include('web-views.partials._profile-aside')
            <section class="col-lg-9 __customer-profile px-0">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                            <h5 class="font-bold m-0 fs-16">{{ translate('profile_Info') }}</h5>
                        </div>

                        <div class="card-inner">
                            <form class="mt-3 px-sm-2 pb-2" action="{{route('user-update')}}" method="post"
                                  id="profile_form"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="profile--info mb-4">
                                    <div class="position-relative profile-img mb-3">
                                        <img id="blah" alt=""
                                             src="{{ getStorageImages(path: $customerDetail->image_full_url, type: 'avatar') }}">
                                        <label class="change-profile-icon m-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                 viewBox="0 0 18 18" fill="none">
                                                <path
                                                    d="M7.3125 9.75C7.3125 9.30245 7.49029 8.87323 7.80676 8.55676C8.12323 8.24029 8.55245 8.0625 9 8.0625C9.44755 8.0625 9.87678 8.24029 10.1932 8.55676C10.5097 8.87323 10.6875 9.30245 10.6875 9.75C10.6875 10.1976 10.5097 10.6268 10.1932 10.9432C9.87678 11.2597 9.44755 11.4375 9 11.4375C8.55245 11.4375 8.12323 11.2597 7.80676 10.9432C7.49029 10.6268 7.3125 10.1976 7.3125 9.75Z"
                                                    fill="white"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M5.6055 5.7315C5.6055 5.42204 5.66646 5.11561 5.78488 4.82971C5.90331 4.5438 6.07689 4.28402 6.29571 4.0652C6.73764 3.62327 7.33702 3.375 7.962 3.375H10.038C10.663 3.375 11.2624 3.62327 11.7043 4.0652C12.1462 4.50713 12.3945 5.10652 12.3945 5.7315C12.3946 5.73685 12.3967 5.74198 12.4002 5.74597C12.4038 5.74996 12.4087 5.75254 12.414 5.75325L14.0865 5.88825C14.8358 5.94975 15.4515 6.50325 15.5918 7.242C15.9483 9.12636 15.9747 11.0584 15.6698 12.9517L15.597 13.4047C15.5303 13.8198 15.3262 14.2005 15.0176 14.4859C14.7089 14.7713 14.3135 14.9449 13.8945 14.979L12.4373 15.0968C10.1495 15.2826 7.85049 15.2826 5.56275 15.0968L4.1055 14.979C3.6864 14.9449 3.29087 14.7711 2.98221 14.4856C2.67355 14.2 2.4696 13.8192 2.403 13.404L2.33025 12.9517C2.025 11.058 2.052 9.12675 2.40825 7.242C2.47637 6.88276 2.66054 6.55582 2.93247 6.31139C3.20441 6.06697 3.54906 5.91857 3.9135 5.889L5.586 5.75325C5.59144 5.75286 5.59652 5.75038 5.60015 5.74632C5.60379 5.74226 5.60571 5.73695 5.6055 5.7315ZM9 6.9375C8.25408 6.9375 7.53871 7.23382 7.01127 7.76126C6.48382 8.28871 6.1875 9.00408 6.1875 9.75C6.1875 10.4959 6.48382 11.2113 7.01127 11.7387C7.53871 12.2662 8.25408 12.5625 9 12.5625C9.74592 12.5625 10.4613 12.2662 10.9887 11.7387C11.5162 11.2113 11.8125 10.4959 11.8125 9.75C11.8125 9.00408 11.5162 8.28871 10.9887 7.76126C10.4613 7.23382 9.74592 6.9375 9 6.9375Z"
                                                      fill="white"/>
                                            </svg>
                                            <input id="files" name="image" hidden type="file" accept="image/*">
                                        </label>
                                    </div>
                                    <h5 class="text-capitalize fs-16 font-bold">
                                        {{ $customerDetail['f_name']. ' '.$customerDetail['l_name'] }}
                                    </h5>
                                </div>
                                <div class="row g-3">
                                    <div class="form-group col-md-6 mb-0">
                                        <label for="firstName"
                                               class="mb-2 text-capitalize">{{translate('first_name')}} </label>
                                        <input type="text" class="form-control" id="f_name" name="f_name"
                                               value="{{$customerDetail['f_name']}}" required>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <label for="lastName"
                                               class="mb-2 text-capitalize"> {{translate('last_name')}} </label>
                                        <input type="text" class="form-control" id="l_name" name="l_name"
                                               value="{{$customerDetail['l_name']}}">
                                    </div>

                                    <div class="form-group col-md-6 mb-0">
                                        <label for="phone" class="mb-2 text-capitalize">
                                            {{ translate('phone_number') }}
                                        </label>
                                        <div class="position-relative d-flex align-items-center">

                                        @php($userCountryAndPhone = ($customerDetail['country_code'] ? '+'.$customerDetail['country_code'] : '').$customerDetail['phone'])
                                        <input class="form-control phone-input-with-country-picker" id="phone" type="text"
                                               value="{{ $userCountryAndPhone }}" placeholder="{{ translate('enter_phone_number') }}" required
                                            {{ $customerDetail['is_phone_verified'] ? 'disabled' : '' }}>

                                        <div class="">
                                            <input type="hidden" class="country-picker-phone-number w-50" name="phone" value="{{ $customerDetail['phone'] }}" readonly>
                                        </div>

                                        @if($customerDetail['phone'] && getLoginConfig(key: 'phone_verification'))
                                            @if($customerDetail['is_phone_verified'])
                                                <span class="position-absolute inset-inline-end-10px cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ translate('Your_phone_is_verified') }}">
                                                    <img width="16"
                                                         src="{{theme_asset('public/assets/front-end/img/icons/verified.svg')}}"
                                                         class="dark-support" alt="">
                                                </span>
                                            @else
                                                <span class="position-absolute inset-inline-end-10px cursor-pointer" data-toggle="tooltip" data-placement="top"
                                                      title="{{ translate('Phone_not_verified.') }} {{ translate('Please_verify_through_the_user_app') }}">
                                                    <img width="16"
                                                         src="{{theme_asset('public/assets/front-end/img/icons/verified-error.svg')}}"
                                                         class="dark-support" alt="">
                                                </span>
                                            @endif
                                        @endif
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 mb-0">
                                        <label for="inputEmail4"
                                               class="mb-2 text-capitalize">{{translate('email')}} </label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input type="email" class="form-control" id="account-email" name="email" value="{{$customerDetail['email']}}">
                                            @if($customerDetail['email'] && getLoginConfig(key: 'email_verification'))
                                                @if($customerDetail['is_email_verified'])
                                                    <span class="position-absolute inset-inline-end-10px cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ translate('Your_email_is_verified') }}">
                                                            <img width="16"
                                                                 src="{{theme_asset('public/assets/front-end/img/icons/verified.svg')}}"
                                                                 class="dark-support" alt="">
                                                    </span>
                                                @else
                                                    <span class="position-absolute inset-inline-end-10px cursor-pointer" data-toggle="tooltip" data-placement="top"
                                                          title="{{ translate('Email_not_verified.') }} {{ translate('Please_verify_through_the_user_app.') }}">
                                                            <img width="16"
                                                                 src="{{theme_asset('public/assets/front-end/img/icons/verified-error.svg')}}"
                                                                 class="dark-support" alt="">
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <label for="si-password"
                                               class="mb-2 text-capitalize">{{translate('new_password')}}</label>
                                        <div class="password-toggle rtl">
                                            <input class="form-control" name="password" type="password"
                                                   placeholder="{{translate('minimum_8_characters_long')}}"
                                                   id="password">
                                            <label class="password-toggle-btn">
                                                <input class="custom-control-input" type="checkbox">
                                                <i class="tio-hidden password-toggle-indicator password check-password-match"></i>
                                                <span class="sr-only">{{translate('show_password')}} </span>
                                            </label>
                                        </div>
                                        <span class="text-danger mx-1 password-error"></span>
                                    </div>

                                    <div class="form-group col-md-6 mb-0">
                                        <label for="newPass"
                                               class="mb-2 text-capitalize">{{translate('confirm_password')}} </label>
                                        <div class="password-toggle rtl">
                                            <input class="form-control" name="confirm_password" type="password"
                                                   placeholder="{{translate('minimum_8_characters_long')}}"
                                                   id="confirm_password">
                                            <div>
                                                <label class="password-toggle-btn">
                                                    <input class="custom-control-input" type="checkbox">
                                                    <i class="tio-hidden password-toggle-indicator check-password-match"></i>
                                                    <span class="sr-only">{{translate('show_password')}} </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div id='message' class="mt-1"></div>
                                    </div>
                                    <div class="col-12 text-end d-none d-md-block">
                                        <button type="submit" class="btn btn--primary px-4 fs-14 font-semi-bold py-2">
                                            {{ translate('update') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="bottom-sticky_offset"></div>
    <div class="bottom-sticky_ele bg-white d-md-none p-3 ">
        <button type="submit" class="btn btn--primary w-100 update-account-info">
            {{translate('update')}}
        </button>
    </div>

@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
@endpush
