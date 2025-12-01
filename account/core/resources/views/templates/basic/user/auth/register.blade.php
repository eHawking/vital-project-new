@extends($activeTemplate . 'layouts.app')
@php
    $registerContent = getContent('register.content', true);
    $privacyAndPolicyContents = getContent('policy_pages.element');
@endphp
@section('panel')
    <style>
        /* Premium Register Theme */
        :root {
            --register-bg: #0f172a;
            --register-card-bg: rgba(30, 41, 59, 0.7);
            --register-input-bg: rgba(255, 255, 255, 0.03);
            --register-border: rgba(255, 255, 255, 0.1);
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --primary-color: #8b5cf6;
        }

        .account-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #1e1b4b, #0f172a);
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }
        
        /* Background Shapes */
        .shape {
            position: absolute;
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }
        .shape3 { top: 10%; left: 5%; animation: float 6s infinite ease-in-out; }
        .shape4 { bottom: 10%; right: 5%; animation: float 8s infinite ease-in-out reverse; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .account-wrapper {
            background: var(--register-card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--register-border);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 1100px;
            position: relative;
            z-index: 1;
        }

        .account-thumb {
            height: 100%;
            background-size: cover;
            background-position: center;
            position: relative;
            min-height: 600px;
        }

        .account-thumb::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.3), rgba(15, 23, 42, 0.9));
        }

        .account-thumb-content {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            z-index: 2;
        }

        .account-form-wrapper {
            padding: 50px;
        }

        .logo img {
            height: 50px;
            margin-bottom: 30px;
        }

        .form--label {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form--control {
            background: var(--register-input-bg);
            border: 1px solid var(--register-border);
            color: #fff;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s;
        }

        .form--control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
            color: white;
            outline: none;
        }

        /* Select2 Overrides */
        .select2-container--default .select2-selection--single {
            background: var(--register-input-bg) !important;
            border: 1px solid var(--register-border) !important;
            border-radius: 12px !important;
            height: 50px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
            padding-left: 16px !important;
            line-height: normal !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
            right: 10px !important;
        }
        .select2-dropdown {
            background: #1e293b !important;
            border: 1px solid var(--register-border) !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background: var(--primary-color) !important;
        }
        .select2-results__option {
            color: #fff !important;
            padding: 10px 16px !important;
        }

        .account--btn {
            background: var(--primary-grad);
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            position: relative;
            overflow: hidden;
        }

        .account--btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
            color: white;
        }
        
        .account--btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .custom--btn {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .custom--btn:hover {
            color: white;
        }

        .account-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        
        .account-subtitle {
            color: #94a3b8;
            margin-bottom: 30px;
        }

        /* Checkbox Styling */
        .form-check-input {
            background-color: var(--register-input-bg);
            border-color: var(--register-border);
        }
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .form-check-label {
            color: #94a3b8;
        }

        @media (max-width: 991px) {
            .account-thumb-wrapper { display: none; }
            .account-form-wrapper { padding: 30px; }
        }
    </style>

    <section class="account-section">
        <div class="shape shape3"><img src="{{ asset($activeTemplateTrue . 'images/shape/08.png') }}" alt="shape"></div>
        <div class="shape shape4"><img src="{{ asset($activeTemplateTrue . 'images/shape/waves.png') }}" alt="shape"></div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="account-wrapper">
                        <div class="row g-0">
                            <div class="col-lg-5 d-none d-lg-block account-thumb-wrapper">
                                <div class="account-thumb" style="background-image: url('{{ frontendImage('register', @$registerContent->data_values->register_image, '1100x750') }}');">
                                    <div class="account-thumb-content">
                                        <h3 class="text-white mb-2">{{ __(@$registerContent->data_values->heading) }}</h3>
                                        <p class="text-white-50">{{ __(@$registerContent->data_values->sub_heading) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="account-form-wrapper @if (!gs('registration')) form-disabled @endif">
                                    @if (!gs('registration'))
                                        <span class="form-disabled-text">
                                            <svg class="" style="enable-background:new 0 0 512 512" xmlns="http://www.w3.org/2000/svg" version="1.1" width="80" height="80" viewBox="0 0 512 512">
                                                <g>
                                                    <path d="M255.999 0c-79.044 0-143.352 64.308-143.352 143.353v70.193c0 4.78 3.879 8.656 8.659 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193c0-42.998 34.981-77.98 77.979-77.98s77.979 34.982 77.979 77.98v70.193c0 4.78 3.88 8.656 8.661 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193C399.352 64.308 335.044 0 255.999 0zM382.04 204.89h-30.748v-61.537c0-52.544-42.748-95.292-95.291-95.292s-95.291 42.748-95.291 95.292v61.537h-30.748v-61.537c0-69.499 56.54-126.04 126.038-126.04 69.499 0 126.04 56.541 126.04 126.04v61.537z" fill="#f99f0b"></path>
                                                    <path d="M410.63 204.89H101.371c-20.505 0-37.188 16.683-37.188 37.188v232.734c0 20.505 16.683 37.188 37.188 37.188H410.63c20.505 0 37.187-16.683 37.187-37.189V242.078c0-20.505-16.682-37.188-37.187-37.188zm19.875 269.921c0 10.96-8.916 19.876-19.875 19.876H101.371c-10.96 0-19.876-8.916-19.876-19.876V242.078c0-10.96 8.916-19.876 19.876-19.876H410.63c10.959 0 19.875 8.916 19.875 19.876v232.733z" fill="#f99f0b"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    @endif

                                    <div class="logo text-center text-lg-start">
                                        <a href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="logo"></a>
                                    </div>
                                    
                                    <h3 class="account-title text-center text-lg-start">@lang('Create Account')</h3>
                                    <p class="account-subtitle text-center text-lg-start">@lang('Join our community today')</p>

                                    <form class="account-form verify-gcaptcha disableSubmission" method="POST" action="{{ route('user.register') }}">
                                        @csrf
                                        <div class="row">
                                            @if ($refUser == null)
                                                <div class="col-md-6">
                                                    <div class="form--group mb-3">
                                                        <label class="form--label">@lang('Referral Username')</label>
                                                        <input class="referral form-control form--control" id="referenceBy" name="referBy" type="text" value="{{ old('referBy') }}" placeholder="@lang('Enter referral username')" required>
                                                        <div id="ref" class="mt-1"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form--group mb-3">
                                                        <label class="form--label">@lang('Position')</label>
                                                        <select class="position form--control form-select select2" id="position" name="position" required data-minimum-results-for-search="-1">
                                                            <option value="" selected disabled>@lang('Select position')</option>
                                                            @foreach (mlmPositions() as $k => $v)
                                                                <option value="{{ $k }}">@lang($v)</option>
                                                            @endforeach
                                                        </select>
                                                        <span id="position-test" class="mt-1 d-block"><span class="text-danger"></span></span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    <div class="form--group mb-3">
                                                        <label class="form--label">@lang('Referral Username')</label>
                                                        <input class="referral form-control form--control" value="{{ $refUser->username }}" id="ref_name" name="referBy" type="text" placeholder="@lang('Enter referral username')*" required readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form--group mb-3">
                                                        <label class="form--label">@lang('Position')</label>
                                                        <select class="position form--control form-select" id="position" required disabled>
                                                            <option value="" selected hidden>@lang('Select position')</option>
                                                            @foreach (mlmPositions() as $k => $v)
                                                                <option value="{{ $k }}" @if ($position == $k) selected @endif>@lang($v)</option>
                                                            @endforeach
                                                        </select>
                                                        <input name="position" type="hidden" value="{{ $position }}">
                                                        @php echo $joining; @endphp
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form--group mb-3">
                                                    <label class="form--label">@lang('First Name')</label>
                                                    <input class="form-control form--control" name="firstname" type="text" value="{{ old('firstname') }}" required placeholder="@lang('Enter Your First Name')">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form--group mb-3">
                                                    <label class="form--label">@lang('Last Name')</label>
                                                    <input class="form-control form--control" name="lastname" type="text" value="{{ old('lastname') }}" required placeholder="@lang('Enter Your Last Name')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form--group mb-3">
                                                    <label class="form--label">@lang('Email')</label>
                                                    <input class="form-control form--control checkUser" name="email" type="email" required placeholder="@lang('Enter Your Email')">
                                                    <small class="text--danger emailExist"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form--group hover-input-popup mb-3">
                                                    <label class="form--label">@lang('Password')</label>
                                                    <input class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password" type="password" required placeholder="@lang('Enter Password')">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form--group mb-3">
                                                    <label class="form--label">@lang('Re-Password')</label>
                                                    <input class="form-control form--control" name="password_confirmation" type="password" required placeholder="@lang('Confirm Password')">
                                                </div>
                                            </div>
                                        </div>

                                        @php $custom = true; @endphp
                                        <x-captcha :custom="$custom" />

                                        @if (gs('agree'))
                                            @php
                                                $policyPages = getContent('policy_pages.element', false, orderById: true);
                                            @endphp

                                            <div class="form-group mb-3">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-2" id="agree" name="agree" type="checkbox" @checked(old('agree')) required>
                                                    <label class="form-check-label" for="agree">
                                                        @lang('I agree with')
                                                        @foreach ([
                                                            ['title' => 'Privacy Policy', 'link' => 'https://dewdropskin.com/privacy-policy'],
                                                            ['title' => 'Terms and Conditions', 'link' => 'https://dewdropskin.com/terms-and-condition'],
                                                            ['title' => 'Refund Policy', 'link' => 'https://dewdropskin.com/refund-policy'],
                                                            ['title' => 'Commission Policy', 'link' => 'https://dewdropskin.com/commission-policy']
                                                        ] as $policy)
                                                            <a class="text-primary" href="{{ $policy['link'] }}" target="_blank">{{ __($policy['title']) }}</a>
                                                            @if (!$loop->last), @endif
                                                        @endforeach
                                                    </label>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form--group button-wrapper">
                                            <button class="account--btn" type="submit" {{ session()->has('notify') ? 'disabled' : '' }}>@lang('Create Account')</button>
                                            
                                            <div class="text-center mt-3">
                                                <p class="text-white-50 mb-2">@lang('Already have an account?') <a href="https://dewdropskin.com" class="text-primary fw-bold">@lang('Sign In')</a></p>
                                                <a class="custom--btn" href="https://dewdropskin.com">
                                                    <i class="las la-store me-1"></i> <span>@lang('Back to Marketplace')</span>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="existModalCenter" role="dialog" aria-bs-labelledby="existModalCenterTitle" aria-bs-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: rgba(30, 41, 59, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); color: white;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="modal-title text-white" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span class="close text-white" data-bs-dismiss="modal" type="button" aria-label="Close" style="cursor: pointer;">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center text-white">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <button class="btn btn-dark btn-sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                   <a class="btn btn-primary btn-sm" href="https://dewdropskin.com"><span>@lang('Back to Marketplace')</span></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('style')
    <style>
        .select2-container--default .select2-selection--single {
            border-left: 0;
            border-right: 0;
            border-top: 0;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";

            var not_select_msg = $('#position-test').html();

            $('#referenceBy').on('blur', function() {

                var username = $(this).val();
                var token = "{{ csrf_token() }}";
                $.ajax({
                    type: "POST",
                    url: "{{ route('check.referral') }}",
                    data: {
                        'username': username,
                        '_token': token
                    },
                    success: function(data) {
                        if (data.success) {
                            $('select[name=position]').attr('disabled', false);
                            $('#position-test').text('');
                        } else {
                            $('select[name=position]').attr('disabled', true);
                            $('#position-test').html(not_select_msg);
                        }
                        $("#ref").html(data.msg);
                    }
                });
            });
            $(document).on('change', '#position', function() {
                updateHand();
            });



            function updateHand() {
                var pos = $('#position').val();
                var referrer_id = $('#referrer_id').val();
                var token = "{{ csrf_token() }}";
                $.ajax({
                    type: "POST",
                    url: "{{ route('get.user.position') }}",
                    data: {
                        'referrer': referrer_id,
                        'position': pos,
                        '_token': token
                    },
                    success: function(data) {
                        if (!data.success) {
                            document.getElementById("ref_name").focus()
                        }
                        $("#position-test").html(data.msg);
                    }
                });
            }

            @if (old('position'))
                $(`select[name=position]`).val('{{ old('position') }}');
            @endif

            // Email uniqueness check with toaster and submission prevention
            let emailTaken = false;
            const $form = $('.account-form');
            const $emailInput = $form.find('input[name="email"]');
            const $submitBtn = $form.find('button[type="submit"]');
            const initiallyDisabled = $submitBtn.is(':disabled');

            function updateSubmitState() {
                if (emailTaken) {
                    $submitBtn.attr('disabled', true);
                } else if (!initiallyDisabled) {
                    $submitBtn.attr('disabled', false);
                }
            }

            $emailInput.on('input', function() {
                emailTaken = false;
                $emailInput.removeClass('is-invalid');
                $('.emailExist').text('');
                updateSubmitState();
            });

            $emailInput.on('focusout', function() {
                const value = $(this).val().trim();
                if (!value) {
                    emailTaken = false;
                    updateSubmitState();
                    return;
                }
                $.post('{{ route('user.checkUser') }}', {
                    email: value,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.data) {
                        emailTaken = true;
                        $('.emailExist').text('This email address is already associated with an existing account. Please sign in or use a different email.');
                        notify('error', 'The email you entered is already registered. Please sign in or use a different email address.');
                        $emailInput.addClass('is-invalid');
                        $emailInput.trigger('focus');
                    } else {
                        emailTaken = false;
                        $emailInput.removeClass('is-invalid');
                        $('.emailExist').text('');
                    }
                    updateSubmitState();
                });
            });

            $form.on('submit', function(e) {
                // Always validate email on submit to catch cases without blur
                const value = $emailInput.val().trim();
                if (!value) {
                    return; // native required will handle
                }
                e.preventDefault();
                $submitBtn.attr('disabled', true).attr('data-force-disabled', '1');
                $.post('{{ route('user.checkUser') }}', {
                    email: value,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.data) {
                        emailTaken = true;
                        $('.emailExist').text('This email address is already associated with an existing account. Please sign in or use a different email.');
                        notify('error', 'We found an existing account with this email. Please log in or use a different email to create a new account.');
                        $emailInput.addClass('is-invalid').trigger('focus');
                        $submitBtn.removeAttr('data-force-disabled');
                        updateSubmitState();
                    } else {
                        emailTaken = false;
                        $emailInput.removeClass('is-invalid');
                        $('.emailExist').text('');
                        // proceed with form submission
                        $submitBtn.removeAttr('data-force-disabled');
                        updateSubmitState();
                        $form.off('submit');
                        $form.trigger('submit');
                    }
                }).fail(function(){
                    // On network error, re-enable submit to avoid lock
                    $submitBtn.removeAttr('data-force-disabled');
                    updateSubmitState();
                });
            });

            @if (!gs('registration'))
                notify('warning', 'Registration is currently disabled');
            @endif

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .form-disabled {
            overflow: hidden;
            position: relative;
        }

        .form-disabled::after {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            top: 0;
            left: 0;
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            z-index: 99;
        }

        .form-disabled-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 991;
            font-size: 24px;
            height: auto;
            width: 100%;
            text-align: center;
            color: hsl(var(--dark-600));
            font-weight: 800;
            line-height: 1.2;
        }
    </style>
@endpush
