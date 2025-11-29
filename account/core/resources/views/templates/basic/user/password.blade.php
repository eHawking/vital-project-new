@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="premium-card">
            <div class="card-body">
                <div class="mb-4 text-center">
                    <h4 class="text-white"><i class="las la-lock"></i> @lang('Change Password')</h4>
                    <p class="text-white-50">@lang('Ensure your account is secure by using a strong password.')</p>
                </div>

                <form method="post">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label text-white-50">@lang('Current Password')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent text-white border-secondary"><i class="las la-key"></i></span>
                            <input class="form-control bg-transparent text-white border-secondary" name="current_password" type="password" required autocomplete="current-password">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label text-white-50">@lang('New Password')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent text-white border-secondary"><i class="las la-lock"></i></span>
                            <input class="form-control bg-transparent text-white border-secondary @if (gs('secure_password')) secure-password @endif" name="password" type="password" required autocomplete="current-password">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label text-white-50">@lang('Confirm Password')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent text-white border-secondary"><i class="las la-check-circle"></i></span>
                            <input class="form-control bg-transparent text-white border-secondary" name="password_confirmation" type="password" required autocomplete="current-password">
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 pulse-animation" type="submit" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600;">
                        @lang('Change Password')
                    </button>

                </form>
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
