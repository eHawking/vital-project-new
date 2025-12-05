@extends($activeTemplate.'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
    /* Theme Text Colors */
    h1, h2, h3, h4, h5, h6 {
        color: #ffffff;
    }
    [data-theme="light"] h1,
    [data-theme="light"] h2,
    [data-theme="light"] h3,
    [data-theme="light"] h4,
    [data-theme="light"] h5,
    [data-theme="light"] h6 {
        color: #1a1f2e;
    }

    .page-subtitle {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .page-subtitle {
        color: #6c757d;
    }

    /* Mobile Full Width */
    @media (max-width: 768px) {
        .inner-dashboard-container,
        .container-fluid.px-4,
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            width: 100% !important;
            margin: 0 !important;
        }
        .premium-card {
            width: 100% !important;
            margin-bottom: 10px;
            border-radius: 12px !important;
        }
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }

    /* Premium Card */
    .premium-card {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        padding: 25px;
    }
    [data-theme="light"] .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Summary Alert */
    .summary-alert {
        background: linear-gradient(135deg, rgba(17, 153, 142, 0.15) 0%, rgba(56, 239, 125, 0.1) 100%);
        border: 1px solid rgba(17, 153, 142, 0.3);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }
    .summary-alert .icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 24px;
    }
    .summary-alert .title {
        color: #38ef7d;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .summary-alert .text {
        color: rgba(255,255,255,0.7);
    }
    [data-theme="light"] .summary-alert .text {
        color: #6c757d;
    }

    /* Description Box */
    .description-box {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-left: 4px solid var(--color-primary);
        border-radius: 0 12px 12px 0;
        padding: 20px;
        margin-bottom: 25px;
        color: rgba(255,255,255,0.7);
    }
    [data-theme="light"] .description-box {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        border-left: 4px solid var(--color-primary);
        color: #6c757d;
    }

    /* Form Controls */
    .form-control-custom {
        background: #242938 !important;
        border: 1px solid rgba(128,128,128,0.2) !important;
        color: #ffffff !important;
        border-radius: 8px;
        padding: 12px 15px;
    }
    [data-theme="light"] .form-control-custom {
        background: #f8f9fa !important;
        border: 1px solid rgba(0,0,0,0.1) !important;
        color: #1a1f2e !important;
    }
    .form-control-custom:focus {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1) !important;
    }
    .form-control-custom::placeholder {
        color: rgba(255,255,255,0.4) !important;
    }
    [data-theme="light"] .form-control-custom::placeholder {
        color: #999 !important;
    }

    /* Form Label */
    .form-label-custom {
        color: rgba(255,255,255,0.6);
        font-size: 14px;
        margin-bottom: 8px;
    }
    [data-theme="light"] .form-label-custom {
        color: #6c757d;
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        border-radius: 10px;
        padding: 16px 20px;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(139, 92, 246, 0.4);
    }

    /* Method Header */
    .method-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(128,128,128,0.2);
    }
    .method-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 20px;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="mb-4">
                <h4 class="m-0"><i class="bi bi-check-circle"></i> @lang('Withdraw Preview')</h4>
                <p class="page-subtitle small m-0">@lang('Confirm your withdrawal details')</p>
            </div>

            <div class="premium-card">
                <!-- Method Header -->
                <div class="method-header">
                    <div class="method-icon">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div>
                        <h5 class="m-0">@lang('Withdraw Via') {{ $withdraw->method->name }}</h5>
                        <small class="page-subtitle">@lang('Complete the form below')</small>
                    </div>
                </div>
                
                <!-- Summary Alert -->
                <div class="summary-alert">
                    <div class="d-flex gap-3">
                        <div class="icon flex-shrink-0">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div>
                            <div class="title">@lang('Withdrawal Summary')</div>
                            <p class="text mb-0">
                                @lang('You are requesting') <strong style="color: #ffffff;">{{ showAmount($withdraw->amount) }}</strong> @lang('for withdraw.')
                                @lang('The admin will send you') <strong class="text-success">{{showAmount($withdraw->final_amount,currencyFormat:false) .' '.$withdraw->currency }}</strong> @lang('to your account.')
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{route('user.withdraw.submit')}}" class="disableSubmission" method="post" enctype="multipart/form-data">
                    @csrf
                    
                    @if($withdraw->method->description)
                    <div class="description-box">
                        @php
                            echo $withdraw->method->description;
                        @endphp
                    </div>
                    @endif
                    
                    <div class="modern-form-group">
                        <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                    </div>
                    
                    @if(auth()->user()->ts)
                    <div class="form-group mb-4">
                        <label class="form-label-custom"><i class="bi bi-shield-lock"></i> @lang('Google Authenticator Code')</label>
                        <input type="text" name="authenticator_code" class="form-control form-control-custom" placeholder="@lang('Enter 6-digit code')" required>
                    </div>
                    @endif
                    
                    <button type="submit" class="btn-submit mt-4">
                        <i class="bi bi-check-circle me-2"></i> @lang('Submit Withdrawal')
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
<script>
    // Apply modern styling to dynamic form elements
    $(document).ready(function() {
        $('.form-control').addClass('form-control-custom');
        $('.form-label').addClass('form-label-custom');
    });
</script>
@endpush
