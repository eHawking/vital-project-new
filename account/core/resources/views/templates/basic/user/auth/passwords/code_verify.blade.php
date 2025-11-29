@extends($activeTemplate.'layouts.frontend')
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
                    <div class="verification-area">
                        <h4 class="text-white text-center mb-3">@lang('Verify Email Address')</h4>
                        <p class="text-white-50 text-center mb-4">@lang('A 6 digit verification code sent to your email address'): <span class="fw-bold text-white">{{ showEmailAddress($email) }}</span></p>
                        
                        <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            
                            <div class="mb-4">
                                @include($activeTemplate.'partials.verification_code')
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="account--btn">@lang('Submit')</button>
                            </div>
                            <div class="text-center">
                                <p class="text-white-50 mb-0">
                                    @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                    <a href="{{ route('user.password.request') }}" class="text--base fw-bold">@lang('Try to send again')</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
