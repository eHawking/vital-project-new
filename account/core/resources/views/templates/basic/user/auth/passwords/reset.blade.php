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
                    <div class="card-body">
                        <h4 class="text-white text-center mb-3">@lang('Reset Password')</h4>
                        <div class="mb-4 text-center">
                            <p class="text-white-50">@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                        </div>
                        <form method="POST" action="{{ route('user.password.update') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="form--group mb-4 hover-input-popup">
                                <label class="form--label">@lang('Password')</label>
                                <input type="password" class="form-control form--control @if(gs('secure_password')) secure-password @endif" name="password" required placeholder="@lang('Enter new password')">
                            </div>
                            <div class="form--group mb-4">
                                <label class="form--label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control" name="password_confirmation" required placeholder="@lang('Confirm new password')">
                            </div>
                          
                            <button type="submit" class="account--btn"> @lang('Submit')</button>
                            
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

@if(gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
