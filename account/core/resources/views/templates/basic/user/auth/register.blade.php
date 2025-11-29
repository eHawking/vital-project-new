@extends($activeTemplate . 'layouts.app')
@php
    $registerContent = getContent('register.content', true);
    $privacyAndPolicyContents = getContent('policy_pages.element');
@endphp
@section('panel')
    <!-- Include Modern Auth Theme CSS -->
    @include($activeTemplate . 'css.modern-auth')

    <section class="account-section p-0">
        <div class="row g-0 h-100">
            <div class="col-md-6 col-xl-7 col-lg-6 d-none d-md-block">
                <div class="account-thumb" style="background-image: url('{{ frontendImage('register', @$registerContent->data_values->register_image, '1100x750') }}');">
                    <div class="account-thumb-content">
                        <p class="welc fs-4 mb-2 text-uppercase letter-spacing-2">{{ __(@$registerContent->data_values->title) }}</p>
                        <h3 class="title display-4 fw-bold">{{ __(@$registerContent->data_values->heading) }}</h3>
                        <p class="info lead text-white-50">{{ __(@$registerContent->data_values->sub_heading) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-5 col-lg-6">
                <div class="account-form-wrapper @if (!gs('registration')) form-disabled @endif">
                    @if (!gs('registration'))
                        <span class="form-disabled-text">
                            <i class="bi bi-x-octagon-fill text-warning display-1"></i>
                            <p class="mt-3">@lang('Registration Disabled')</p>
                        </span>
                    @endif

                    <div class="logo mb-4 text-center text-lg-start">
                        <a href="{{ route('home') }}">
                            <img src="{{ siteLogo() }}" alt="logo" class="img-fluid">
                        </a>
                    </div>
                    
                    <h3 class="text-white mb-4 text-center text-lg-start">@lang('Create Your Account')</h3>

                    <form class="account-form verify-gcaptcha disableSubmission" method="POST" action="{{ route('user.register') }}">
                        @csrf
                        <div class="row">
                            @if ($refUser == null)
                                <div class="col-md-6">
                                    <div class="form--group mb-3">
                                        <label class="form--label">@lang('Referral Username')</label>
                                        <input class="referral form-control form--control" id="referenceBy" name="referBy" type="text"
                                            value="{{ old('referBy') }}" placeholder="@lang('Enter referral username')" required>
                                        <div id="ref" class="mt-1"></div>
                                        <span id="referral"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form--group mb-3">
                                        <label class="form--label">@lang('Position')</label>
                                        <select class="position form-select form--control select2" id="position" name="position" required
                                            data-minimum-results-for-search="-1">
                                            <option value="" selected disabled>@lang('Select position')</option>
                                            @foreach (mlmPositions() as $k => $v)
                                                <option value="{{ $k }}">@lang($v)</option>
                                            @endforeach
                                        </select>
                                        <span id="position-test"><span class="text-danger"></span></span>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form--group mb-3">
                                        <label class="form--label">@lang('Referral Username')</label>
                                        <input class="referral form-control form--control" value="{{ $refUser->username }}" id="ref_name" name="referBy"
                                            type="text" placeholder="@lang('Enter referral username')*" required readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form--group mb-3">
                                        <label class="form--label">@lang('Position')</label>
                                        <select class="position form-select form--control" id="position" required disabled>
                                            <option value="" selected hidden>@lang('Select position')</option>
                                            @foreach (mlmPositions() as $k => $v)
                                                <option value="{{ $k }}" @if ($position == $k) selected @endif>@lang($v)
                                                </option>
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
                                    <input class="form-control form--control" name="firstname" type="text" value="{{ old('firstname') }}" required
                                        placeholder="Enter Your First Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form--group mb-3">
                                    <label class="form--label">@lang('Last Name')</label>
                                    <input class="form-control form--control" name="lastname" type="text" value="{{ old('lastname') }}" required
                                        placeholder="Enter Your Last Name">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form--group mb-3">
                                    <label class="form--label">@lang('Email')</label>
                                    <input class="form-control form--control checkUser" name="email" type="email" required
                                        placeholder="Enter Your Email">
                                    <small class="text-danger emailExist"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form--group hover-input-popup mb-3">
                                    <label class="form--label">@lang('Password')</label>
                                    <input class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password"
                                        type="password" required placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form--group mb-3">
                                    <label class="form--label">@lang('Re-Password')</label>
                                    <input class="form-control form--control" name="password_confirmation" type="password" required
                                        placeholder="Confirm Password">
                                </div>
                            </div>
                        </div>

                        @php
                            $custom = true;
                        @endphp
                        <x-captcha :custom="$custom" />

                        @if (gs('agree'))
                            @php
                                $policyPages = getContent('policy_pages.element', false, orderById: true);
                            @endphp

                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" id="agree" name="agree" type="checkbox" @checked(old('agree')) required>
                                    <label class="form-check-label text-white-50" for="agree">
                                        @lang('I agree with')
                                        @foreach ([
                                            ['title' => 'Privacy Policy', 'link' => 'https://dewdropskin.com/privacy-policy'],
                                            ['title' => 'Terms and Conditions', 'link' => 'https://dewdropskin.com/terms-and-condition'],
                                            ['title' => 'Refund Policy', 'link' => 'https://dewdropskin.com/refund-policy'],
                                            ['title' => 'Commission Policy', 'link' => 'https://dewdropskin.com/commission-policy']
                                        ] as $policy)
                                            <a class="text--base" href="{{ $policy['link'] }}" target="_blank">{{ __($policy['title']) }}</a>
                                            @if (!$loop->last) , @endif
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="form--group button-wrapper mb-4">
							<button class="account--btn" type="submit" {{ session()->has('notify') ? 'disabled' : '' }}>@lang('Create Account')</button>
                        </div>
                        
                        <div class="text-center">
                           <p class="text-white-50">@lang('Already have an account?') <a href="{{ route('user.login') }}" class="text--base fw-bold">@lang('Sign In')</a></p>
                           <p class="mt-2"><a class="text-white-50 small" href="https://dewdropskin.com">@lang('Back to Marketplace')</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Account Section Ends Here -->

    <div class="modal fade" id="existModalCenter" role="dialog" aria-bs-labelledby="existModalCenterTitle" aria-bs-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: var(--card-bg); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title text-white" id="existModalLongTitle">@lang('You are with us')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center text-white-50">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-dark btn-sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                    <a class="btn btn-outline-light btn-sm" href="https://dewdropskin.com"><span>@lang('Back to Marketplace')</span></a>
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
