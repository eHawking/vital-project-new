<?php
    $customerManualLogin = $web_config['customer_login_options']['manual_login'] ?? 0;
    $customerOTPLogin = $web_config['customer_login_options']['otp_login'] ?? 0;
    $customerSocialLogin = $web_config['customer_login_options']['social_login'] ?? 0;

    if (!$customerOTPLogin && $customerManualLogin && $customerSocialLogin) {
        $multiColumn = 1;
    } elseif ($customerOTPLogin && !$customerManualLogin && $customerSocialLogin) {
        $multiColumn = 1;
    } elseif ($customerOTPLogin && $customerManualLogin && !$customerSocialLogin) {
        $multiColumn = 1;
    } elseif ($customerOTPLogin && $customerManualLogin && $customerSocialLogin) {
        $multiColumn = 1;
    } else {
        $multiColumn = 0;
    }
?>


<div class="modal fade" id="SignInModal" tabindex="-1" aria-labelledby="SignInModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $multiColumn ? 'modal-lg' : '' }}">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 px-sm-5">
                <div class="d-flex justify-content-center">
                    <a href="javascript:">
                        <img loading="lazy" alt="{{ translate('logo') }}" class="h-70px"
                             src="{{ getStorageImages(path: $web_config['footer_logo'], type:'logo') }}">
                    </a>
                </div>

                <div class="mb-4">
                    <h2 class="mb-2">{{ translate('sign_in') }}</h2>
                    <p class="text-muted">
                        {{ translate('sign_in_to_your_account.') }}
                    </p>
                </div>

                <div class="{{ $multiColumn ? 'row align-items-center or-sign-in-with-row' : ''}}">
                    <div class="{{ $multiColumn ? 'col-md-6' : ''}}">
                        @if($customerOTPLogin && !$customerManualLogin && !$customerSocialLogin)
                            <form action="{{route('customer.auth.login')}}" method="post"
                                  class="customer-centralize-login-form"
                                  autocomplete="off">
                                @csrf
                                <input type="hidden" name="login_type" value="otp-login">
                                @include('theme-views.layouts.auth-partials._phone')
                                @include("theme-views.layouts.auth-partials._remember-me", ['forgotPassword' => false])

                                <div class="d-flex justify-content-center mb-3">
                                    <button type="submit" class="fs-16 btn btn-base text-capitalize px-5 w-100">
                                        {{ translate('Get_OTP') }}
                                    </button>
                                </div>
                            </form>
                        @elseif(!$customerOTPLogin && $customerManualLogin && !$customerSocialLogin)
                            <form action="{{route('customer.auth.login')}}" method="post"
                                  class="customer-centralize-login-form"
                                  autocomplete="off">
                                @csrf
                                <input type="hidden" name="login_type" value="manual-login">
                                @include("theme-views.layouts.auth-partials._email")
                                @include("theme-views.layouts.auth-partials._password")
                                @include("theme-views.layouts.auth-partials._remember-me", ['forgotPassword' => true])
                                <div class="d-flex justify-content-center mb-3">
                                    <button type="submit" class="fs-16 btn btn-base text-capitalize px-5 w-100">
                                        {{ translate('login') }}
                                    </button>
                                </div>
                                @if(!$multiColumn)
                                    @include("theme-views.layouts.auth-partials._sign-up-instruction")
                                @endif
                            </form>
                        @elseif(!$customerOTPLogin && $customerManualLogin && $customerSocialLogin)
                            <form action="{{route('customer.auth.login')}}" method="post"
                                  class="customer-centralize-login-form"
                                  autocomplete="off">
                                @csrf
                                <input type="hidden" name="login_type" value="manual-login">
                                @include("theme-views.layouts.auth-partials._email")
                                @include("theme-views.layouts.auth-partials._password")
                                @include("theme-views.layouts.auth-partials._remember-me", ['forgotPassword' => true])
                                <div class="d-flex justify-content-center mb-3">
                                    <button type="submit" class="fs-16 btn btn-base text-capitalize px-5 w-100">
                                        {{ translate('login') }}
                                    </button>
                                </div>
                                @if(!$multiColumn)
                                    @include("theme-views.layouts.auth-partials._sign-up-instruction")
                                @endif

                            </form>
                        @elseif($customerOTPLogin && !$customerManualLogin && $customerSocialLogin)
                            <form action="{{route('customer.auth.login')}}" method="post"
                                  class="customer-centralize-login-form"
                                  autocomplete="off">
                                @csrf
                                <input type="hidden" name="login_type" value="otp-login">
                                @include("theme-views.layouts.auth-partials._phone")
                                @include("theme-views.layouts.auth-partials._remember-me", ['forgotPassword' => false])
                                <div class="d-flex justify-content-center mb-3">
                                    <button type="submit" class="fs-16 btn btn-base text-capitalize px-5 w-100">
                                        {{ translate('Get_OTP') }}
                                    </button>
                                </div>
                            </form>
                        @elseif($customerOTPLogin && $customerManualLogin)
                            <div class="manual-login-container">
                                <form action="{{route('customer.auth.login')}}" method="post"
                                      class="customer-centralize-login-form"
                                      autocomplete="off">
                                    @csrf

                                    <input type="hidden" name="login_type" class="auth-login-type-input" value="manual-login">

                                    <div class="manual-login-items">
                                        @include("theme-views.layouts.auth-partials._email")
                                        @include("theme-views.layouts.auth-partials._password")
                                        @include("theme-views.layouts.auth-partials._remember-me", ['forgotPassword' => true])
                                    </div>

                                    <div class="otp-login-items d-none">
                                        @include("theme-views.layouts.auth-partials._phone")
                                    </div>

                                    <div class="manual-login-items">
                                        <div class="d-flex justify-content-center mb-3">
                                            <button type="submit" class="fs-16 btn btn-base text-capitalize px-5 w-100">
                                                {{ translate('login') }}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="otp-login-items d-none">
                                        <div class="d-flex justify-content-center mb-3 w-100">
                                            <button type="submit" class="fs-16 btn btn-base text-capitalize px-5 w-100">
                                                {{ translate('Get_OTP') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>

                    @if($multiColumn)
                        <div class="or-sign-in-with"><span>{{translate('Or Sign in with')}}</span></div>
                    @endif

                    @if($multiColumn || $customerSocialLogin || $customerManualLogin)
                        <div class="{{ $multiColumn ? 'col-md-6' : '' }}">

                            <div class="d-flex justify-content-center flex-column align-items-center my-3 gap-3">
                                @if($customerSocialLogin)
                                    @foreach ($web_config['customer_social_login_options'] as $socialLoginServiceKey => $socialLoginService)
                                        @if ($socialLoginService && $socialLoginServiceKey != 'apple')
                                            <a class="social-media-login-btn"
                                               href="{{ route('customer.auth.service-login', $socialLoginServiceKey) }}">
                                                <img alt=""
                                                     src="{{ theme_asset('assets/img/svg/'.$socialLoginServiceKey.'.svg') }}">
                                                <span class="text">
                                                    {{ translate($socialLoginServiceKey) }}
                                                </span>
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                                @if($customerOTPLogin && $customerManualLogin)
                                    <a class="social-media-login-btn otp-login-btn" href="javascript:">
                                        <img alt="" src="{{ theme_asset('assets/img/svg/otp-login-icon.svg') }}">
                                        <span class="text">{{ translate('OTP_Sign_in') }}</span>
                                    </a>

                                    <a class="social-media-login-btn manual-login-btn d-none" href="javascript:">
                                        <img alt="" src="{{ theme_asset('assets/img/svg/otp-login-icon.svg') }}">
                                        <span class="text">{{ translate('Manual_Login') }}</span>
                                    </a>
                                @endif
                            </div>
                           
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($multiColumn)
    <script>
        "use strict";
        function resizeFunc() {
            $('.or-sign-in-with').css('width', $('.or-sign-in-with-row').height())
        }
        $('#SignInModal').on('show.bs.modal', function () {
            resizeFunc();
            const resizeObserver = new ResizeObserver(resizeFunc);
            resizeObserver.observe(document.querySelector('.or-sign-in-with-row'));
        });
    </script>
@endif


<script>
        "use strict";

    try {
        initializePhoneInput(".phone-input-with-country-picker-login", ".country-picker-phone-number-login");


        document.querySelector('.phone-input-with-country-picker-login').addEventListener('input', function(e) {
            let inputValue = e.target.value;

            let digitsOnly = inputValue.replace(/\D/g, '');

       
            if (digitsOnly.length > 10) {
                e.target.value = digitsOnly.slice(-10);
            } else {
                e.target.value = digitsOnly;
            }
        });

    } catch (e) {
        console.log(e)
    }
	
	// Refined JavaScript for manual login "Email / Phone" input
    document.querySelector('.auth-email-input').addEventListener('input', function(e) {
        const inputField = e.target;
        let inputValue = inputField.value.trim();
        const loginType = document.querySelector('.auth-login-type-input').value;
        const countryCode = '92'; // Your country code

        // Only apply phone formatting if it's manual login and the input looks like a phone number
        if (loginType === 'manual-login' && (inputValue.startsWith('+') || /^\d/.test(inputValue))) {

            let digitsOnly = inputValue.replace(/\D/g, ''); // Remove all non-digits

            // Remove country code if it's explicitly present at the beginning of digitsOnly
            // This is to prevent slicing the '92' from the *phone number* digits if they entered it
            if (digitsOnly.startsWith(countryCode)) {
                digitsOnly = digitsOnly.substring(countryCode.length);
            }

            // Always take the last 10 digits from the remaining digitsOnly
            // This ensures we always get the actual 10-digit phone number part
            let formattedNumberPart = digitsOnly.slice(-10);
            
            // Reconstruct the value with +countryCode and the 10 digits
            inputField.value = '+' + countryCode + formattedNumberPart;

        } else if (loginType === 'manual-login' && inputValue === '') {
            // If the input is cleared in manual login, allow typing anything (email/username)
            // By doing nothing here, we allow the input to be empty and for the user to type non-digits.
        }
        // For all other cases (e.g., if loginType is not manual-login, or if the input is not phone-like
        // but also not empty, indicating an email or username), we do nothing, letting the user type freely.
    });
	
    $('.customer-centralize-login-form').on('submit', async function (event) {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (response) {
                responseManager(response)
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    });

    $('.otp-login-btn').on('click', function () {
        $(this).addClass('d-none');
        $('.manual-login-btn').removeClass('d-none');
        $('.manual-login-items').addClass('d-none');
        $('.otp-login-items').removeClass('d-none');
        $('.phone-input-with-country-picker-login').attr('required', true);
        $('.auth-email-input').attr('required', false);
        $('.auth-password-input').attr('required', false);
        $('.auth-login-type-input').val('otp-login');
    })

    $('.manual-login-btn').on('click', function () {
        $(this).addClass('d-none');
        $('.otp-login-btn').removeClass('d-none');
        $('.otp-login-items').addClass('d-none');
        $('.manual-login-items').removeClass('d-none');
        $('.phone-input-with-country-picker-login').attr('required', false);
        $('.auth-email-input').attr('required', true);
        $('.auth-password-input').attr('required', true);
        $('.auth-login-type-input').val('manual-login');
    })

</script>