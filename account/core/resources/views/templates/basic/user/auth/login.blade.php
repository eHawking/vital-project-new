@extends($activeTemplate . 'layouts.app')

@section('panel')
    <!-- Include Modern Auth Theme CSS -->
    @include($activeTemplate . 'css.modern-auth')

    <section class="account-section p-0">
        <div class="row g-0 h-100">
            <div class="col-md-6 col-xl-7 col-lg-6 d-none d-md-block">
                <div class="account-thumb" style="background-image: url('{{ frontendImage('login', @$loginContent->data_values->login_image, '1100x750') }}');">
                    <div class="account-thumb-content">
                        <p class="welc fs-4 mb-2 text-uppercase letter-spacing-2">{{ __(@$loginContent->data_values->title) }}</p>
                        <h3 class="title display-4 fw-bold">{{ __(@$loginContent->data_values->heading) }}</h3>
                        <p class="info lead text-white-50">{{ __(@$loginContent->data_values->sub_heading) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-5 col-lg-6">
                <div class="account-form-wrapper">
                    <div class="logo mb-5">
                        <a href="{{ route('home') }}">
                            <img src="{{ siteLogo() }}" alt="logo" class="img-fluid">
                        </a>
                    </div>

                    <h3 class="text-white mb-4">@lang('Welcome Back!')</h3>

                    <form class="account-form verify-gcaptcha" method="POST" action="{{ route('user.login') }}">
                        @csrf

                        <div class="form--group mb-4">
                            <label class="form--label">@lang('Username')</label>
                            <input class="form-control form--control" name="username" type="text" value="{{ old('username') }}"
                                placeholder="@lang('Enter Username')" required>
                        </div>
                        <div class="form--group mb-4">
                            <label class="form--label">@lang('Password')</label>
                            <input class="form-control form--control" id="password" name="password" type="password" placeholder="@lang('Enter Password')"
                                required>
                        </div>

                        @php
                            $custom = true;
                        @endphp
                        <x-captcha :custom="$custom" />

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form--group custom--checkbox m-0">
                                <input class="form-check-input" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-white-50 ms-2" for="remember">
                                    @lang('Remember Me')
                                </label>
                            </div>
                            <a class="text--base small" href="{{ route('user.password.request') }}">
                                @lang('Forgot Password?')
                            </a>
                        </div>

                        <div class="form--group button-wrapper mb-4">
                            <button class="account--btn" type="submit">@lang('Sign In')</button>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-white-50">@lang('Don\'t have an account?') <a href="{{ route('user.register') }}" class="text--base fw-bold">@lang('Create Account')</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
