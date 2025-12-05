@extends($activeTemplate . 'layouts.master')

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
        overflow: hidden;
    }
    [data-theme="light"] .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Card Header */
    .card-header-premium {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        padding: 20px 25px;
        border: none;
    }
    .card-header-premium h5 {
        color: #ffffff !important;
        margin: 0;
        font-weight: 600;
    }

    /* Card Body */
    .card-body-premium {
        padding: 25px;
    }

    /* Amount Alert */
    .amount-alert {
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }
    .amount-alert p {
        color: rgba(255,255,255,0.8);
        margin: 0;
        font-size: 15px;
    }
    [data-theme="light"] .amount-alert p {
        color: #1a1f2e;
    }
    .amount-alert .amount-value {
        color: #ffffff;
        font-weight: 700;
    }
    [data-theme="light"] .amount-alert .amount-value {
        color: var(--color-primary);
    }

    /* Description */
    .gateway-description {
        color: rgba(255,255,255,0.6);
        padding: 15px;
        background: rgba(128,128,128,0.05);
        border-radius: 10px;
        margin-bottom: 20px;
    }
    [data-theme="light"] .gateway-description {
        color: #6c757d;
    }

    /* Form Styling */
    .premium-form .form-label {
        color: rgba(255,255,255,0.7);
        font-weight: 500;
        margin-bottom: 8px;
    }
    [data-theme="light"] .premium-form .form-label {
        color: #6c757d;
    }
    .premium-form .form-control {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        color: #ffffff;
        border-radius: 10px;
        padding: 12px 15px;
    }
    [data-theme="light"] .premium-form .form-control {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .premium-form .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }
    .premium-form .form-control::placeholder {
        color: rgba(255,255,255,0.4);
    }
    [data-theme="light"] .premium-form .form-control::placeholder {
        color: #999;
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        border-radius: 12px;
        padding: 15px;
        font-weight: 700;
        font-size: 16px;
        width: 100%;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-shield-check"></i> @lang('Confirm Deposit')</h4>
        <p class="page-subtitle small m-0">@lang('Complete your deposit payment')</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="premium-card">
                <div class="card-header-premium">
                    <h5><i class="bi bi-credit-card"></i> @lang('Payment Details')</h5>
                </div>
                <div class="card-body-premium">
                    <form class="disableSubmission premium-form" action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="amount-alert">
                            <p><i class="bi bi-info-circle text-info me-2"></i> @lang('You are requesting') <span class="amount-value">{{ showAmount($data['amount']) }}</span> @lang('to deposit.') @lang('Please pay')
                                <span class="amount-value">{{ showAmount($data['final_amount'], currencyFormat: false) . ' ' . $data['method_currency'] }}</span> @lang('for successful payment.')
                            </p>
                        </div>

                        @if($data->gateway->description)
                            <div class="gateway-description">@php echo $data->gateway->description @endphp</div>
                        @endif

                        <div class="modern-form-group">
                            <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />
                        </div>
                            
                        <button class="btn-submit" type="submit">
                            <i class="bi bi-check-circle"></i> @lang('Pay Now')
                        </button>

                    </form>
                </div>
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
    $(document).ready(function() {
        // Apply theme-aware styling to dynamic form elements
        var isDarkTheme = document.documentElement.getAttribute('data-theme') !== 'light';
        
        if (isDarkTheme) {
            $('.form-control').css({
                'background': '#242938',
                'border': '1px solid rgba(128,128,128,0.2)',
                'color': '#ffffff',
                'border-radius': '10px',
                'padding': '12px 15px'
            });
            $('.form-label').css('color', 'rgba(255,255,255,0.7)');
        } else {
            $('.form-control').css({
                'background': '#f8f9fa',
                'border': '1px solid rgba(0,0,0,0.1)',
                'color': '#1a1f2e',
                'border-radius': '10px',
                'padding': '12px 15px'
            });
            $('.form-label').css('color', '#6c757d');
        }
    });
</script>
@endpush
