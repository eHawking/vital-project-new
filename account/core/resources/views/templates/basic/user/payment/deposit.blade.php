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
        padding: 25px;
    }
    [data-theme="light"] .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* History Button */
    .btn-history {
        background: rgba(128,128,128,0.1);
        border: 1px solid rgba(128,128,128,0.2);
        color: rgba(255,255,255,0.7);
        border-radius: 10px;
        padding: 10px 20px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .btn-history {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #6c757d;
    }
    .btn-history:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--color-primary);
        color: var(--color-primary);
    }

    /* Gateway List */
    .gateway-list {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }
    .gateway-list::-webkit-scrollbar {
        width: 6px;
    }
    .gateway-list::-webkit-scrollbar-track {
        background: rgba(128,128,128,0.1);
        border-radius: 3px;
    }
    .gateway-list::-webkit-scrollbar-thumb {
        background: rgba(128,128,128,0.3);
        border-radius: 3px;
    }

    /* Gateway Item */
    .gateway-item {
        background: #242938;
        border: 2px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }
    [data-theme="light"] .gateway-item {
        background: #f8f9fa;
        border: 2px solid rgba(0,0,0,0.1);
    }
    .gateway-item:hover {
        border-color: var(--color-primary);
        background: rgba(139, 92, 246, 0.05);
    }
    .gateway-item.selected {
        border-color: var(--color-primary);
        background: rgba(139, 92, 246, 0.1);
    }
    .gateway-item .radio-circle {
        width: 22px;
        height: 22px;
        border: 2px solid rgba(128,128,128,0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .gateway-item.selected .radio-circle {
        border-color: var(--color-primary);
    }
    .gateway-item.selected .radio-circle::after {
        content: '';
        width: 12px;
        height: 12px;
        background: var(--color-primary);
        border-radius: 50%;
    }
    .gateway-name {
        font-weight: 600;
        color: #ffffff;
    }
    [data-theme="light"] .gateway-name {
        color: #1a1f2e;
    }
    .gateway-thumb {
        width: 60px;
        height: 40px;
        object-fit: contain;
        background: #ffffff;
        border-radius: 6px;
        padding: 5px;
    }

    /* Amount Section */
    .amount-section {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        padding: 25px;
    }
    [data-theme="light"] .amount-section {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Amount Input */
    .amount-input-group {
        display: flex;
        background: #1a1f2e;
        border: 2px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .amount-input-group {
        background: #ffffff;
        border: 2px solid rgba(0,0,0,0.1);
    }
    .amount-input-group:focus-within {
        border-color: var(--color-primary);
    }
    .amount-input-group .currency-prefix {
        background: rgba(128,128,128,0.1);
        color: #ffffff;
        padding: 15px 20px;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
    }
    [data-theme="light"] .amount-input-group .currency-prefix {
        color: #1a1f2e;
    }
    .amount-input-group input {
        background: transparent;
        border: none;
        color: #ffffff;
        padding: 15px 20px;
        font-size: 20px;
        font-weight: 700;
        flex: 1;
        min-width: 0;
    }
    [data-theme="light"] .amount-input-group input {
        color: #1a1f2e;
    }
    .amount-input-group input:focus {
        outline: none;
    }
    .amount-input-group input::placeholder {
        color: rgba(255,255,255,0.3);
    }
    [data-theme="light"] .amount-input-group input::placeholder {
        color: #999;
    }

    /* Info Card */
    .info-card {
        background: rgba(128,128,128,0.05);
        border: 1px solid rgba(128,128,128,0.1);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }
    .info-row:not(:last-child) {
        border-bottom: 1px dashed rgba(128,128,128,0.1);
    }
    .info-row .label {
        color: rgba(255,255,255,0.6);
        font-size: 14px;
    }
    [data-theme="light"] .info-row .label {
        color: #6c757d;
    }
    .info-row .value {
        color: #ffffff;
        font-weight: 600;
    }
    [data-theme="light"] .info-row .value {
        color: #1a1f2e;
    }
    .info-row.total .label,
    .info-row.total .value {
        font-size: 18px;
        font-weight: 700;
    }
    .info-row.total .value {
        color: #28a745;
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        border-radius: 12px;
        padding: 18px;
        font-weight: 700;
        font-size: 16px;
        width: 100%;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
    }
    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Show More Button */
    .btn-show-more {
        background: rgba(128,128,128,0.1);
        border: 1px solid rgba(128,128,128,0.2);
        color: rgba(255,255,255,0.7);
        border-radius: 10px;
        padding: 12px;
        width: 100%;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .btn-show-more {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
        color: #6c757d;
    }
    .btn-show-more:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--color-primary);
        color: var(--color-primary);
    }

    /* Crypto Message */
    .crypto-message {
        background: rgba(13, 202, 240, 0.1);
        border: 1px solid rgba(13, 202, 240, 0.3);
        color: #0dcaf0;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        font-size: 13px;
        margin-top: 15px;
    }

    /* Security Text */
    .security-text {
        color: rgba(255,255,255,0.5);
        font-size: 12px;
        text-align: center;
        margin-top: 20px;
    }
    [data-theme="light"] .security-text {
        color: #6c757d;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="m-0"><i class="bi bi-wallet2"></i> @lang('Deposit Funds')</h4>
            <p class="page-subtitle small m-0">@lang('Add funds to your account securely')</p>
        </div>
        <a class="btn-history" href="{{ route('user.deposit.history') }}">
            <i class="bi bi-clock-history"></i> @lang('Deposit History')
        </a>
    </div>

    <!-- Deposit Form Card -->
    <div class="premium-card">
        <form action="{{ route('user.deposit.insert') }}" method="post" class="deposit-form">
            @csrf
            <input type="hidden" name="currency">
            <div class="row g-4">
                <!-- Left: Gateway Selection -->
                <div class="col-lg-6">
                    <h5 class="mb-3"><i class="bi bi-credit-card-2-front"></i> @lang('Select Gateway')</h5>
                    <div class="gateway-list">
                        @foreach ($gatewayCurrency as $data)
                            <label for="{{ titleToKey($data->name) }}" class="gateway-item @if ($loop->index > 4) d-none @endif">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="radio-circle"></div>
                                        <span class="gateway-name">{{ __($data->name) }}</span>
                                    </div>
                                    <img class="gateway-thumb" src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}" alt="@lang('payment-thumb')">
                                </div>
                                <input class="gateway-input" id="{{ titleToKey($data->name) }}" hidden
                                    data-gateway='@json($data)' type="radio" name="gateway" value="{{ $data->method_code }}"
                                    @if (old('gateway')) @checked(old('gateway') == $data->method_code) @else @checked($loop->first) @endif
                                    data-min-amount="{{ showAmount($data->min_amount) }}" data-max-amount="{{ showAmount($data->max_amount) }}">
                            </label>
                        @endforeach
                        @if ($gatewayCurrency->count() > 4)
                            <button type="button" class="btn-show-more more-gateway-option">
                                <i class="bi bi-chevron-down"></i> @lang('Show All Payment Options')
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Right: Amount Section -->
                <div class="col-lg-6">
                    <div class="amount-section">
                        <h5 class="mb-3"><i class="bi bi-currency-exchange"></i> @lang('Enter Amount')</h5>
                        <div class="amount-input-group">
                            <span class="currency-prefix">{{ gs('cur_sym') }}</span>
                            <input type="text" class="amount" name="amount" placeholder="@lang('00.00')" value="{{ old('amount') }}" autocomplete="off">
                        </div>

                        <div class="info-card">
                            <div class="info-row">
                                <span class="label">@lang('Limit')</span>
                                <span class="value gateway-limit">@lang('0.00')</span>
                            </div>
                            <div class="info-row">
                                <span class="label">@lang('Processing Charge') <i class="bi bi-info-circle text-info" data-bs-toggle="tooltip" title="@lang('Processing charge for payment gateways')"></i></span>
                                <span class="value text-danger"><span class="processing-fee">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                            </div>
                            <div class="info-row total">
                                <span class="label">@lang('Total')</span>
                                <span class="value"><span class="final-amount">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                            </div>
                        </div>

                        <div class="gateway-conversion d-none" style="margin-top: 15px;">
                            <div class="info-row">
                                <span class="label">@lang('Conversion')</span>
                                <span class="value conversion-text"></span>
                            </div>
                        </div>
                        
                        <div class="conversion-currency d-none">
                            <div class="info-row">
                                <span class="label">@lang('In') <span class="gateway-currency"></span></span>
                                <span class="value in-currency"></span>
                            </div>
                        </div>
                        
                        <div class="crypto-message d-none">
                            <i class="bi bi-info-circle"></i> @lang('Conversion with') <span class="gateway-currency"></span> @lang('and final value will Show on next step')
                        </div>

                        <button type="submit" class="btn-submit" disabled>
                            <i class="bi bi-shield-check"></i> @lang('Confirm Deposit')
                        </button>
                        
                        <p class="security-text">
                            <i class="bi bi-shield-lock-fill text-success"></i> @lang('Ensuring your funds grow safely through our secure deposit process with world-class payment options.')
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
<script>
    "use strict";
    (function($) {

        var amount = parseFloat($('.amount').val() || 0);
        var gateway, minAmount, maxAmount;

        // Initialize first gateway as selected
        $('.gateway-item').first().addClass('selected');

        $('.amount').on('input', function(e) {
            amount = parseFloat($(this).val());
            if (!amount) {
                amount = 0;
            }
            calculation();
        });

        $('.gateway-item').on('click', function(e) {
            // Visual update
            $('.gateway-item').removeClass('selected');
            $(this).addClass('selected');
        });

        $('.gateway-input').on('change', function(e) {
            gatewayChange();
        });

        function gatewayChange() {
            let gatewayElement = $('.gateway-input:checked');
            let methodCode = gatewayElement.val();

            gateway = gatewayElement.data('gateway');
            minAmount = gatewayElement.data('min-amount');
            maxAmount = gatewayElement.data('max-amount');

            let processingFeeInfo =
                `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`
            $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);
            calculation();
        }

        gatewayChange();

        $(".more-gateway-option").on("click", function(e) {
            let paymentList = $(".gateway-list");
            paymentList.find(".gateway-item").removeClass("d-none");
            $(this).addClass('d-none');
        });

        function calculation() {
            if (!gateway) return;
            $(".gateway-limit").text(minAmount + " - " + maxAmount);

            let percentCharge = 0;
            let fixedCharge = 0;
            let totalPercentCharge = 0;

            if (amount) {
                percentCharge = parseFloat(gateway.percent_charge);
                fixedCharge = parseFloat(gateway.fixed_charge);
                totalPercentCharge = parseFloat(amount / 100 * percentCharge);
            }

            let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
            let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

            $(".final-amount").text(totalAmount.toFixed(2));
            $(".processing-fee").text(totalCharge.toFixed(2));
            $("input[name=currency]").val(gateway.currency);
            $(".gateway-currency").text(gateway.currency);

            if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                $(".deposit-form button[type=submit]").attr('disabled', true);
            } else {
                $(".deposit-form button[type=submit]").removeAttr('disabled');
            }

            if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {
                $('.deposit-form').addClass('adjust-height')

                $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                
                $(".gateway-conversion").find('.conversion-text').html(
                    `1 {{ __(gs('cur_text')) }} = <span class="text-info fw-bold">${parseFloat(gateway.rate).toFixed(2)}</span> ${gateway.currency}`
                );
                
                $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ? 8 : 2))
            } else {
                $(".gateway-conversion, .conversion-currency").addClass('d-none');
                $('.deposit-form').removeClass('adjust-height')
            }

            if (gateway.method.crypto == 1) {
                $('.crypto-message').removeClass('d-none');
            } else {
                $('.crypto-message').addClass('d-none');
            }
        }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        $('.gateway-input').change();
    })(jQuery);
</script>
@endpush
