@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- Include Modern Auth Theme CSS -->
    @include($activeTemplate . 'css.modern-auth')

    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 py-5">
        <div class="logo mb-4">
            <a href="{{ route('home') }}">
                <img src="{{ siteLogo() }}" alt="logo" class="img-fluid">
            </a>
        </div>
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-7 col-xl-5">
                <div class="premium-card" style="background: var(--card-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 2rem;">
                    <div class="card-body">
                        <h4 class="text-white text-center mb-3">@lang('Recover Account')</h4>
                        <div class="mb-4 text-center">
                            <p class="text-white-50">@lang('To recover your account please provide your email or username to find your account.')</p>
                        </div>
                        <form class="verify-gcaptcha" method="POST" action="{{ route('user.password.email') }}">
                            @csrf
                            <div class="form--group mb-4">
                                <label class="form--label">@lang('Email or Username')</label>
                                <input class="form-control form--control" name="value" type="text" value="{{ old('value') }}" required autofocus="off" placeholder="@lang('Enter email or username')">
                            </div>
                            @php
                                $custom = true;
                            @endphp
                            <x-captcha :custom="$custom" />
                            <button class="account--btn" type="submit">@lang('Submit')</button>
                            
                            <div class="text-center mt-4">
                                <a href="{{ route('user.login') }}" class="text--base fw-bold"><i class="las la-arrow-left"></i> @lang('Back to Login')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
