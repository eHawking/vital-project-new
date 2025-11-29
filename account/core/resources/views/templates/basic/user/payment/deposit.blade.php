@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h4 class="text-white m-0"><i class="bi bi-wallet2"></i> @lang('Deposit Funds')</h4>
        <a class="btn btn-outline-light border-secondary" href="{{ route('user.deposit.history') }}">
          <i class="las la-list"></i> @lang('Deposit History')
        </a>
    </div>

    <div class="premium-card">
        <div class="card-body p-4">
            <form action="{{ route('user.deposit.insert') }}" method="post" class="deposit-form">
                @csrf
                <input type="hidden" name="currency">
                <div class="gateway-card">
                    <div class="row justify-content-center gy-4">
                        <div class="col-lg-6">
                            <h5 class="text-white mb-3">@lang('Select Gateway')</h5>
                            <div class="payment-system-list is-scrollable gateway-option-list d-flex flex-column gap-3">
                                @foreach ($gatewayCurrency as $data)
                                    <label for="{{ titleToKey($data->name) }}"
                                        class="payment-item @if ($loop->index > 4) d-none @endif gateway-option d-flex align-items-center justify-content-between p-3 rounded cursor-pointer transition-all"
                                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="payment-item__check custom-radio-circle"></div>
                                            <span class="payment-item__name text-white fw-bold">{{ __($data->name) }}</span>
                                        </div>
                                        <div class="payment-item__thumb bg-white p-1 rounded">
                                            <img class="payment-item__thumb-img object-fit-contain" style="width: 60px; height: 40px;" src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}"
                                                alt="@lang('payment-thumb')">
                                        </div>
                                        <input class="payment-item__radio gateway-input" id="{{ titleToKey($data->name) }}" hidden
                                            data-gateway='@json($data)' type="radio" name="gateway" value="{{ $data->method_code }}"
                                            @if (old('gateway')) @checked(old('gateway') == $data->method_code) @else @checked($loop->first) @endif
                                            data-min-amount="{{ showAmount($data->min_amount) }}" data-max-amount="{{ showAmount($data->max_amount) }}">
                                    </label>
                                @endforeach
                                @if ($gatewayCurrency->count() > 4)
                                    <button type="button" class="btn btn-sm btn-outline-light border-secondary w-100 more-gateway-option mt-2">
                                        <span class="payment-item__btn-text">@lang('Show All Payment Options')</span>
                                        <i class="fas fa-chevron-down ms-2"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="payment-system-list p-4 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1);">
                                <div class="deposit-info mb-4">
                                    <h5 class="text-white mb-3">@lang('Enter Amount')</h5>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-secondary text-white fw-bold">{{ gs('cur_sym') }}</span>
                                        <input type="text" class="form-control bg-transparent text-white border-secondary fw-bold fs-5 amount" name="amount" placeholder="@lang('00.00')"
                                            value="{{ old('amount') }}" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class="bg-transparent p-3 rounded border border-secondary border-opacity-25 mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white-50">@lang('Limit')</span>
                                        <span class="text-white fw-bold gateway-limit">@lang('0.00')</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white-50">@lang('Processing Charge')
                                            <span data-bs-toggle="tooltip" title="@lang('Processing charge for payment gateways')" class="text-info"><i class="las la-info-circle"></i></span>
                                        </span>
                                        <span class="text-danger fw-bold"><span class="processing-fee">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                                    </div>
                                    <div class="divider my-2" style="border-bottom: 1px dashed rgba(255,255,255,0.1);"></div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-white fs-5 fw-bold">@lang('Total')</span>
                                        <span class="text-success fs-5 fw-bold"><span class="final-amount">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>

                                <div class="deposit-info gateway-conversion d-none mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-white-50">@lang('Conversion')</span>
                                        <span class="text-white conversion-text"></span>
                                    </div>
                                </div>
                                
                                <div class="deposit-info conversion-currency d-none mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-white-50">@lang('In') <span class="gateway-currency"></span></span>
                                        <span class="text-white fw-bold in-currency"></span>
                                    </div>
                                </div>
                                
                                <div class="d-none crypto-message mb-3 p-2 rounded bg-info bg-opacity-10 border border-info border-opacity-25 text-info text-center small">
                                    @lang('Conversion with') <span class="gateway-currency"></span> @lang('and final value will Show on next step')
                                </div>

                                <button type="submit" class="btn btn-primary w-100 pulse-animation py-3 fw-bold" style="background: var(--grad-primary); border: none;" disabled>
                                    @lang('Confirm Deposit')
                                </button>
                                
                                <div class="mt-3 text-center">
                                    <p class="text-white-50 small mb-0"><i class="bi bi-shield-lock-fill text-success"></i> @lang('Ensuring your funds grow safely through our secure deposit process with world-class payment options.')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
<style>
    .payment-item.gateway-option:hover, 
    .payment-item.gateway-option:has(:checked) {
        background: rgba(255, 255, 255, 0.1) !important;
        border-color: var(--accent-blue) !important;
    }
    
    .custom-radio-circle {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.5);
        border-radius: 50%;
        position: relative;
        transition: all 0.3s ease;
    }

    .gateway-input:checked + .custom-radio-circle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background: var(--accent-blue);
        border-radius: 50%;
    }

    .gateway-input:checked ~ .payment-item__check {
        border-color: var(--accent-blue);
    }

    .gateway-input:checked ~ .custom-radio-circle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background-color: var(--accent-blue);
        border-radius: 50%;
    }

    /* Since I cannot easily target the sibling of the input if the input is inside the label but after the check div */
    /* I will use a small script or simple CSS based on :has if supported, but for compatibility, standard radio styling is better. */
    /* Re-structuring the HTML slightly in the loop to make it pure CSS friendly is hard without changing logic. */
    /* I'll use a simple trick: The input is inside the label. */
</style>
@endpush

@push('script')
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
                // Visual update for custom radio
                $('.payment-item').removeClass('active-gateway');
                $(this).closest('.payment-item').addClass('active-gateway');
                $('.payment-item__check').empty(); // clear all
                $(this).closest('.payment-item').find('.payment-item__check').html('<div style="width: 10px; height: 10px; background: #0dcaf0; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>');
                $('.payment-item__check').css('border-color', 'rgba(255,255,255,0.5)');
                $(this).closest('.payment-item').find('.payment-item__check').css('border-color', '#0dcaf0');

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
                    
                    // Fixed selector for conversion text
                    $(".gateway-conversion").find('.conversion-text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate fw-bold text-info">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency text-white">${gateway.currency}</span>`
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
