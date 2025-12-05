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
        .border-end {
            border-right: none !important;
            border-bottom: 1px solid rgba(128,128,128,0.2) !important;
            padding-bottom: 20px !important;
            margin-bottom: 20px !important;
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

    /* Payment Item */
    .payment-item {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    [data-theme="light"] .payment-item {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .payment-item:hover {
        border-color: var(--color-primary);
        background: rgba(139, 92, 246, 0.1);
    }
    .payment-item.selected {
        border-color: var(--color-primary);
        background: rgba(139, 92, 246, 0.15);
    }
    .payment-item__name {
        color: #ffffff;
        font-weight: 600;
    }
    [data-theme="light"] .payment-item__name {
        color: #1a1f2e;
    }

    /* Details Box */
    .details-box {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 20px;
    }
    [data-theme="light"] .details-box {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Form Controls */
    .form-control-custom {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        color: #ffffff;
        border-radius: 8px;
        padding: 12px 15px;
    }
    [data-theme="light"] .form-control-custom {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .form-control-custom:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        outline: none;
    }
    .input-group-text-custom {
        background: rgba(128,128,128,0.1);
        border: 1px solid rgba(128,128,128,0.2);
        border-right: none;
        color: rgba(255,255,255,0.7);
        border-radius: 8px 0 0 8px;
        padding: 12px 15px;
    }
    [data-theme="light"] .input-group-text-custom {
        background: #e9ecef;
        border: 1px solid rgba(0,0,0,0.1);
        color: #6c757d;
    }

    /* Label */
    .form-label-custom {
        color: rgba(255,255,255,0.6);
        font-size: 14px;
        margin-bottom: 8px;
    }
    [data-theme="light"] .form-label-custom {
        color: #6c757d;
    }

    /* Text Colors */
    .text-white-custom {
        color: #ffffff;
    }
    [data-theme="light"] .text-white-custom {
        color: #1a1f2e;
    }
    .text-muted-custom {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .text-muted-custom {
        color: #6c757d;
    }

    /* History Button */
    .btn-history {
        background: rgba(128,128,128,0.1);
        border: 1px solid rgba(128,128,128,0.2);
        color: rgba(255,255,255,0.8);
        border-radius: 8px;
        padding: 10px 20px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .btn-history {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .btn-history:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--color-primary);
        color: var(--color-primary);
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        border-radius: 10px;
        padding: 14px 20px;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(139, 92, 246, 0.4);
    }
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* More Options Button */
    .btn-more-options {
        background: transparent;
        border: 1px dashed rgba(128,128,128,0.3);
        color: rgba(255,255,255,0.6);
        border-radius: 8px;
        padding: 10px;
        width: 100%;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .btn-more-options {
        color: #6c757d;
    }
    .btn-more-options:hover {
        border-color: var(--color-primary);
        color: var(--color-primary);
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="m-0"><i class="bi bi-cash-coin"></i> @lang('Withdraw Funds')</h4>
            <p class="page-subtitle small m-0">@lang('Choose your preferred withdrawal method')</p>
        </div>
        <a class="btn-history" href="{{ route('user.withdraw.history') }}">
            <i class="bi bi-clock-history"></i> @lang('Withdraw History')
        </a>
    </div>

    <div class="premium-card">
        <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form">
            @csrf
            <div class="row gy-4">
                <div class="col-lg-6 border-end">
                    <h5 class="mb-3">@lang('Select Payment Method')</h5>
                    <div class="payment-system-list is-scrollable gateway-option-list" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                        @foreach ($withdrawMethod as $data)
                            <label for="{{ titleToKey($data->name) }}"
                                class="payment-item @if ($loop->index > 4) d-none @endif gateway-option d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-item__thumb" style="width: 50px; height: 50px; background: #fff; border-radius: 50%; padding: 5px; display: flex; align-items: center; justify-content: center;">
                                        <img class="payment-item__thumb-img w-100 h-100 object-fit-contain" src="{{ getImage(getFilePath('withdrawMethod') . '/' . $data->image) }}"
                                            alt="@lang('payment-thumb')">
                                    </div>
                                    <span class="payment-item__name">{{ __($data->name) }}</span>
                                </div>
                                <div class="payment-item__check">
                                    <input class="form-check-input gateway-input" id="{{ titleToKey($data->name) }}" 
                                        data-gateway='@json($data)' type="radio" name="method_code" value="{{ $data->id }}"
                                        @if (old('method_code')) @checked(old('method_code') == $data->id) @else @checked($loop->first) @endif
                                        data-min-amount="{{ showAmount($data->min_limit) }}" data-max-amount="{{ showAmount($data->max_limit) }}"
                                        style="cursor: pointer;">
                                </div>
                            </label>
                        @endforeach
                        @if ($withdrawMethod->count() > 4)
                            <button type="button" class="btn-more-options more-gateway-option">
                                @lang('Show All Payment Options') <i class="bi bi-chevron-down ms-1"></i>
                            </button>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-6 ps-lg-4">
                    <h5 class="mb-3">@lang('Withdrawal Details')</h5>
                    <div class="details-box">
                        <div class="mb-3">
                            <label class="form-label-custom">@lang('Enter Amount')</label>
                            <div class="input-group">
                                <span class="input-group-text-custom">{{ gs('cur_sym') }}</span>
                                <input type="number" step="any" class="form-control form-control-custom amount" name="amount" placeholder="@lang('0.00')"
                                    value="{{ old('amount') }}" autocomplete="off" style="border-radius: 0 8px 8px 0;">
                            </div>
                        </div>

                        <hr style="border-color: rgba(128,128,128,0.2); margin: 20px 0;">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-custom">@lang('Limit')</span>
                            <span class="text-white-custom fw-bold gateway-limit">@lang('0.00')</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-custom">@lang('Processing Charge') 
                                <i class="bi bi-info-circle text-info proccessing-fee-info" data-bs-toggle="tooltip" title="@lang('Processing charge for withdraw method')"></i>
                            </span>
                            <span class="text-danger">{{ gs('cur_sym') }}<span class="processing-fee">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 pt-3" style="border-top: 1px solid rgba(128,128,128,0.2);">
                            <span class="text-white-custom fs-5">@lang('Receivable')</span>
                            <span class="text-success fs-5 fw-bold">{{ gs('cur_sym') }}<span class="final-amount">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                        </div>

                        <div class="gateway-conversion d-none mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted-custom">@lang('Conversion')</span>
                                <span class="text-white-custom conversion-rate"></span>
                            </div>
                        </div>
                        
                        <div class="conversion-currency d-none mb-4">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted-custom">@lang('In') <span class="gateway-currency"></span></span>
                                <span class="text-white-custom fw-bold in-currency"></span>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit mt-3" disabled>
                            @lang('Confirm Withdraw')
                        </button>
                        
                        <p class="text-muted-custom text-center mt-3 small">
                            <i class="bi bi-shield-check"></i> @lang('Safely withdraw your funds using our highly secure process.')
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

        $('.amount').on('input', function(e) {
            amount = parseFloat($(this).val());
            if (!amount) {
                amount = 0;
            }
            calculation();
        });

        $('.gateway-input').on('change', function(e) {
            // Highlight selected option
            $('.payment-item').removeClass('selected');
            $(this).closest('.payment-item').addClass('selected');
            
            gatewayChange();
        });

        function gatewayChange() {
            let gatewayElement = $('.gateway-input:checked');
            let methodCode = gatewayElement.val();

            gateway = gatewayElement.data('gateway');
            minAmount = gatewayElement.data('min-amount');
            maxAmount = gatewayElement.data('max-amount');

            let processingFeeInfo =
                `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for processing fees`
            $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);

            calculation();
        }

        gatewayChange();
        // Mark first item as selected on load
        $('.gateway-input:checked').closest('.payment-item').addClass('selected');

        $(".more-gateway-option").on("click", function(e) {
            let paymentList = $(".gateway-option-list");
            paymentList.find(".gateway-option").removeClass("d-none");
            $(this).addClass('d-none');
            paymentList.animate({
                scrollTop: (paymentList.height() - 60)
            }, 'slow');
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
            let totalAmount = parseFloat((amount || 0) - totalPercentCharge - fixedCharge);

            $(".final-amount").text(totalAmount.toFixed(2));
            $(".processing-fee").text(totalCharge.toFixed(2));
            $("input[name=currency]").val(gateway.currency);
            $(".gateway-currency").text(gateway.currency);

            if (amount < Number(gateway.min_limit) || amount > Number(gateway.max_limit)) {
                $(".withdraw-form button[type=submit]").attr('disabled', true);
            } else {
                $(".withdraw-form button[type=submit]").removeAttr('disabled');
            }

            if (gateway.currency != "{{ gs('cur_text') }}") {
                $('.withdraw-form').addClass('adjust-height')
                $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                $(".gateway-conversion").find('.conversion-rate').html(
                    `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                );
                $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(2))
            } else {
                $(".gateway-conversion, .conversion-currency").addClass('d-none');
                $('.withdraw-form').removeClass('adjust-height')
            }
        }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $('.gateway-input').change();
    })(jQuery);
</script>
@endpush
