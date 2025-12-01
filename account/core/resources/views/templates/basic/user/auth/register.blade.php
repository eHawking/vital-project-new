@extends($activeTemplate . 'layouts.app')
@php
    $registerContent = getContent('register.content', true);
    $privacyAndPolicyContents = getContent('policy_pages.element');
@endphp
@section('panel')
    <style>
        /* --- Premium Theme Variables --- */
        :root {
            /* Dark Mode (Default) */
            --bg-body: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --input-bg: rgba(255, 255, 255, 0.03);
            --input-border: rgba(255, 255, 255, 0.1);
            --input-focus: rgba(139, 92, 246, 0.5);
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --shape-color-1: #4f46e5;
            --shape-color-2: #c026d3;
            --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            --glass-blur: blur(20px);
        }

        [data-theme="light"] {
            /* Light Mode */
            --bg-body: #f0f4f8;
            --bg-card: rgba(255, 255, 255, 0.85);
            --border-color: rgba(0, 0, 0, 0.05);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --input-focus: #6366f1;
            --primary-grad: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --shape-color-1: #818cf8;
            --shape-color-2: #e879f9;
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            --glass-blur: blur(30px);
        }

        /* --- Global Styles --- */
        body {
            background-color: var(--bg-body);
            color: var(--text-primary);
            transition: background-color 0.5s ease, color 0.3s ease;
        }

        .account-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        /* --- Animated Background Shapes (Nano Banana Style) --- */
        .ambient-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float-ambient 10s infinite alternate ease-in-out;
        }

        .shape-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: var(--shape-color-1);
            animation-delay: 0s;
        }

        .shape-2 {
            bottom: -10%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: var(--shape-color-2);
            animation-delay: -5s;
        }

        @keyframes float-ambient {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(30px, 50px) rotate(10deg); }
        }

        /* --- Main Card Wrapper --- */
        .premium-card-wrapper {
            width: 100%;
            max-width: 1200px;
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 30px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            display: flex;
            flex-direction: row;
            transition: all 0.3s ease;
        }

        /* --- Left Side: Visuals --- */
        .premium-visual-side {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 60px;
            background: radial-gradient(circle at center, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%);
        }

        /* Nano Banana Pro Generated Image Replacement - CSS Art */
        .nano-art-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: linear-gradient(45deg, var(--shape-color-1), var(--shape-color-2));
            opacity: 0.2;
        }
        
        .nano-art-overlay {
            position: absolute;
            top: 0; 
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .visual-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .visual-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            background: linear-gradient(to right, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .visual-desc {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 400px;
        }

        /* --- Right Side: Form --- */
        .premium-form-side {
            flex: 1.2;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        /* --- Theme Toggle --- */
        .theme-toggle-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .theme-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        /* --- Form Elements --- */
        .brand-logo {
            height: 60px;
            margin-bottom: 40px;
            display: block;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: var(--text-secondary);
        }

        .form--label {
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .form--control {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 14px 16px;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form--control:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: var(--bg-card);
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--single {
            background-color: var(--input-bg) !important;
            border: 1px solid var(--input-border) !important;
            border-radius: 12px !important;
            height: 52px !important;
            display: flex !important;
            align-items: center !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-primary) !important;
            padding-left: 16px !important;
        }
        
        .select2-dropdown {
            background-color: var(--bg-body) !important;
            border: 1px solid var(--border-color) !important;
        }
        
        .select2-results__option {
            color: var(--text-primary) !important;
        }

        .btn-register {
            background: var(--primary-grad);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            width: 100%;
            font-weight: 700;
            font-size: 1.05rem;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.5);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -10px rgba(99, 102, 241, 0.6);
        }
        
        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: var(--text-primary);
        }

        .divider {
            height: 1px;
            background: var(--border-color);
            margin: 30px 0;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .premium-card-wrapper {
                flex-direction: column;
            }
            .premium-visual-side {
                display: none; /* Hide visual on mobile to save space */
            }
            .premium-form-side {
                padding: 40px 20px;
            }
        }
    </style>

    <div class="account-section">
        <!-- Background Ambient Shapes -->
        <div class="ambient-shape shape-1"></div>
        <div class="ambient-shape shape-2"></div>

        <div class="premium-card-wrapper">
            <!-- Theme Toggle -->
            <button class="theme-toggle-btn" id="themeToggle" title="Toggle Theme">
                <i class="las la-moon" id="themeIcon"></i>
            </button>

            <!-- Left Side: Nano Banana Style Art -->
            <div class="premium-visual-side">
                <div class="nano-art-container"></div>
                <div class="nano-art-overlay"></div>
                <div class="visual-content">
                    <h2 class="visual-title">{{ __(@$registerContent->data_values->heading) }}</h2>
                    <p class="visual-desc">{{ __(@$registerContent->data_values->sub_heading) }}</p>
                </div>
            </div>

            <!-- Right Side: Registration Form -->
            <div class="premium-form-side">
                <div class="account-form-wrapper @if (!gs('registration')) form-disabled @endif">
                    @if (!gs('registration'))
                        <div class="text-center py-5">
                            <h3 class="text-danger">Registration Disabled</h3>
                        </div>
                    @else
                        <div class="logo-wrapper text-center text-lg-start">
                            <a href="{{ route('home') }}">
                                <img src="{{ siteLogo() }}" alt="logo" class="brand-logo">
                            </a>
                        </div>

                        <div class="form-header text-center text-lg-start">
                            <h3 class="form-title">@lang('Create Account')</h3>
                            <p class="form-subtitle">@lang('Join our community today')</p>
                        </div>

                        <form class="account-form verify-gcaptcha disableSubmission" method="POST" action="{{ route('user.register') }}">
                            @csrf
                            
                            <!-- Referral & Position Section -->
                            <div class="row">
                                @if ($refUser == null)
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form--label">@lang('Referral Username')</label>
                                            <input class="form--control" id="referenceBy" name="referBy" type="text" value="{{ old('referBy') }}" placeholder="@lang('Enter referral username')" required>
                                            <div id="ref" class="mt-2"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form--label">@lang('Position')</label>
                                            <select class="form--control select2" id="position" name="position" required data-minimum-results-for-search="-1">
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
                                        <div class="form-group mb-4">
                                            <label class="form--label">@lang('Referral Username')</label>
                                            <input class="form--control" value="{{ $refUser->username }}" id="ref_name" name="referBy" type="text" required readonly style="opacity: 0.7; cursor: not-allowed;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form--label">@lang('Position')</label>
                                            <select class="form--control" id="position" required disabled style="opacity: 0.7; cursor: not-allowed;">
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

                            <!-- Name Section -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-4">
                                        <label class="form--label">@lang('First Name')</label>
                                        <input class="form--control" name="firstname" type="text" value="{{ old('firstname') }}" required placeholder="@lang('Enter Your First Name')">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-4">
                                        <label class="form--label">@lang('Last Name')</label>
                                        <input class="form--control" name="lastname" type="text" value="{{ old('lastname') }}" required placeholder="@lang('Enter Your Last Name')">
                                    </div>
                                </div>
                            </div>

                            <!-- Email Section -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-4">
                                        <label class="form--label">@lang('Email')</label>
                                        <input class="form--control checkUser" name="email" type="email" required placeholder="@lang('Enter Your Email')">
                                        <small class="text-danger emailExist"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-4">
                                        <label class="form--label">@lang('Password')</label>
                                        <input class="form--control @if (gs('secure_password')) secure-password @endif" name="password" type="password" required placeholder="@lang('Enter Password')">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-4">
                                        <label class="form--label">@lang('Confirm Password')</label>
                                        <input class="form--control" name="password_confirmation" type="password" required placeholder="@lang('Confirm Password')">
                                    </div>
                                </div>
                            </div>

                            @php $custom = true; @endphp
                            <x-captcha :custom="$custom" />

                            <!-- Agreements -->
                            @if (gs('agree'))
                                <div class="form-group mb-4">
                                    <div class="form-check d-flex align-items-center ps-0">
                                        <input class="form-check-input me-2" id="agree" name="agree" type="checkbox" @checked(old('agree')) required style="width: 18px; height: 18px; margin-top: 0;">
                                        <label class="form-check-label text-secondary" for="agree" style="font-size: 0.9rem;">
                                            @lang('I agree with')
                                            @foreach ([
                                                ['title' => 'Privacy Policy', 'link' => 'https://dewdropskin.com/privacy-policy'],
                                                ['title' => 'Terms and Conditions', 'link' => 'https://dewdropskin.com/terms-and-condition'],
                                                ['title' => 'Refund Policy', 'link' => 'https://dewdropskin.com/refund-policy'],
                                                ['title' => 'Commission Policy', 'link' => 'https://dewdropskin.com/commission-policy']
                                            ] as $policy)
                                                <a class="text-primary text-decoration-none" href="{{ $policy['link'] }}" target="_blank">{{ __($policy['title']) }}</a>
                                                @if (!$loop->last), @endif
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="form-group">
                                <button class="btn-register" type="submit" {{ session()->has('notify') ? 'disabled' : '' }}>@lang('Create Account')</button>
                                
                                <div class="text-center mt-4">
                                    <p class="text-secondary mb-2">
                                        @lang('Already have an account?') 
                                        <a href="https://dewdropskin.com" class="text-primary fw-bold text-decoration-none">@lang('Sign In')</a>
                                    </p>
                                    <div class="divider"></div>
                                    <a class="back-link" href="https://dewdropskin.com">
                                        <i class="las la-store me-2"></i> <span>@lang('Back to Marketplace')</span>
                                    </a>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Account Exist Modal -->
    <div class="modal fade" id="existModalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg-card); backdrop-filter: blur(20px); border: 1px solid var(--border-color); color: var(--text-primary);">
                <div class="modal-header border-0">
                    <h5 class="modal-title">@lang('Account Exists')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1); opacity: 0.5;"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="las la-info-circle text-warning mb-3" style="font-size: 48px;"></i>
                    <h6 class="mb-0">@lang('You already have an account. Please Login.')</h6>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button class="btn btn-dark btn-sm px-4" data-bs-dismiss="modal">@lang('Close')</button>
                   <a class="btn btn-primary btn-sm px-4" href="https://dewdropskin.com">@lang('Login Now')</a>
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

@push('script')
    <script>
        (function($) {
            "use strict";

            // Theme Toggler Logic
            const themeBtn = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const htmlEl = document.documentElement;
            
            // Check Local Storage
            const currentTheme = localStorage.getItem('theme') || 'dark';
            htmlEl.setAttribute('data-theme', currentTheme);
            updateIcon(currentTheme);

            if (themeBtn) {
                themeBtn.addEventListener('click', () => {
                    let theme = htmlEl.getAttribute('data-theme');
                    let newTheme = theme === 'dark' ? 'light' : 'dark';
                    htmlEl.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateIcon(newTheme);
                });
            }

            function updateIcon(theme) {
                if(theme === 'light') {
                    if(themeIcon) {
                        themeIcon.classList.remove('la-moon');
                        themeIcon.classList.add('la-sun');
                    }
                } else {
                    if(themeIcon) {
                        themeIcon.classList.remove('la-sun');
                        themeIcon.classList.add('la-moon');
                    }
                }
            }

            // Original Form Logic
            var not_select_msg = $('#position-test').html();

            $('#referenceBy').on('blur', function() {
                var username = $(this).val();
                var token = "{{ csrf_token() }}";
                $.ajax({
                    type: "POST",
                    url: "{{ route('check.referral') }}",
                    data: { 'username': username, '_token': token },
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
                    data: { 'referrer': referrer_id, 'position': pos, '_token': token },
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

            // Email Check
            let emailTaken = false;
            const $form = $('.account-form');
            const $emailInput = $form.find('input[name="email"]');
            const $submitBtn = $form.find('button[type="submit"]');

            $emailInput.on('input', function() {
                emailTaken = false;
                $emailInput.removeClass('is-invalid');
                $('.emailExist').text('');
                $submitBtn.attr('disabled', false);
            });

            $emailInput.on('focusout', function() {
                const value = $(this).val().trim();
                if (!value) return;
                
                $.post('{{ route('user.checkUser') }}', {
                    email: value,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.data) {
                        emailTaken = true;
                        $('.emailExist').text('Email already registered.');
                        notify('error', 'Email already exists.');
                        $emailInput.addClass('is-invalid');
                        $submitBtn.attr('disabled', true);
                    }
                });
            });
            
            @if (!gs('registration'))
                notify('warning', 'Registration is currently disabled');
            @endif

        })(jQuery);
    </script>
@endpush
