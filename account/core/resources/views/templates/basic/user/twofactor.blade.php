@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="row justify-content-center gy-4">

        @if (!auth()->user()->ts)
            <div class="col-md-6">
                <div class="premium-card h-100">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-4">
                        <h4 class="m-0 text-white"><i class="las la-plus-circle text-primary"></i> @lang('Add Your Account')</h4>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="mb-4 text-white-50 font-weight-normal">
                            @lang('Use the QR code or setup key on your Google Authenticator app to add your account. ')
                        </h6>
                        
                        <div class="text-center mb-4 p-3 rounded bg-white d-inline-block mx-auto" style="border: 4px solid rgba(255,255,255,0.1);">
                            <img class="img-fluid" src="{{ $qrCodeUrl }}" alt="QR" style="max-width: 180px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label text-white-50">@lang('Setup Key')</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-secondary"><i class="las la-key"></i></span>
                                <input type="text" name="key" value="{{ $secret }}" class="form-control bg-transparent text-white border-secondary referralURL" readonly>
                                <button type="button" class="btn btn-primary copytext" id="copyBoard" style="background: var(--grad-primary); border: none;"> <i class="fas fa-copy"></i> </button>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                            <label class="text-white mb-2"><i class="fas fa-info-circle text-info"></i> @lang('Help')</label>
                            <p class="text-white-50 small">@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') <a class="text-primary"
                                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                    target="_blank">@lang('Download')</a></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6">
            @if (auth()->user()->ts)
                <div class="premium-card h-100">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-4">
                        <h5 class="card-title text-white m-0"><i class="las la-shield-alt text-danger"></i> @lang('Disable 2FA Security')</h5>
                    </div>
                    <form action="{{ route('user.twofactor.disable') }}" method="POST">
                        <div class="card-body p-4">
                            @csrf
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group mb-4">
                                <label class="form-label text-white-50">@lang('Google Authenticator OTP')</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent text-white border-secondary"><i class="las la-lock"></i></span>
                                    <input type="text" class="form-control bg-transparent text-white border-secondary" name="code" required placeholder="@lang('Enter 6-digit code')">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 pulse-animation" style="padding: 12px; font-weight: 600;">@lang('Disable 2FA')</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="premium-card h-100">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-4">
                        <h4 class="m-0 text-white"><i class="las la-shield-alt text-success"></i> @lang('Enable 2FA Security')</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.twofactor.enable') }}" method="POST">
                            <div class="card-body p-0">
                                @csrf
                                <input type="hidden" name="key" value="{{ $secret }}">
                                <div class="form-group mb-4">
                                    <label class="form-label text-white-50">@lang('Google Authenticator OTP')</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent text-white border-secondary"><i class="las la-lock"></i></span>
                                        <input type="text" class="form-control bg-transparent text-white border-secondary" name="code" required placeholder="@lang('Enter 6-digit code')">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 pulse-animation" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600;">@lang('Enable 2FA')</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .copied::after {
            background-color: #{{ gs('base_color') }};
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').on('click', function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
