@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Theme Switcher -->
@include($activeTemplate . 'partials.theme-switcher')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="page-title text-white mb-1"><i class="bi bi-cash-coin"></i> @lang('Withdraw Funds')</h2>
                    <p class="page-subtitle text-white-50">@lang('Choose your preferred withdrawal method')</p>
                </div>
                <a class="btn btn-outline-light" href="{{ route('user.withdraw.history') }}" style="border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.05);">
                    <i class="bi bi-clock-history"></i> @lang('Withdraw History')
                </a>
            </div>

            <div class="premium-card mb-4">
                <div class="card-body">
                    <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form">
                        @csrf
                        <div class="row gy-4">
                            <div class="col-lg-6 border-end border-secondary border-opacity-25">
                                <h5 class="text-white mb-3">@lang('Select Payment Method')</h5>
                                <div class="payment-system-list is-scrollable gateway-option-list" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                                    @foreach ($withdrawMethod as $data)
                                        <label for="{{ titleToKey($data->name) }}"
                                            class="payment-item @if ($loop->index > 4) d-none @endif gateway-option d-flex align-items-center justify-content-between p-3 mb-3 rounded cursor-pointer"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s ease;">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="payment-item__thumb" style="width: 50px; height: 50px; background: #fff; border-radius: 50%; padding: 5px; display: flex; align-items: center; justify-content: center;">
                                                    <img class="payment-item__thumb-img w-100 h-100 object-fit-contain" src="{{ getImage(getFilePath('withdrawMethod') . '/' . $data->image) }}"
                                                        alt="@lang('payment-thumb')">
                                                </div>
                                                <span class="payment-item__name text-white fw-bold">{{ __($data->name) }}</span>
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
                                        <button type="button" class="btn btn-link text-white text-decoration-none w-100 mt-2 more-gateway-option">
                                            @lang('Show All Payment Options') <i class="fas fa-chevron-down ms-1"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-lg-6 ps-lg-4">
                                <h5 class="text-white mb-3">@lang('Withdrawal Details')</h5>
                                <div class="p-3 rounded" style="background: rgba(255,255,255,0.02);">
                                    <div class="mb-3">
                                        <label class="form-label text-white-50">@lang('Enter Amount')</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent text-white border-secondary">{{ gs('cur_sym') }}</span>
                                            <input type="number" step="any" class="form-control bg-transparent text-white border-secondary amount" name="amount" placeholder="@lang('0.00')"
                                                value="{{ old('amount') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <hr class="border-secondary opacity-25 my-4">

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white-50">@lang('Limit')</span>
                                        <span class="text-white fw-bold gateway-limit">@lang('0.00')</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white-50">@lang('Processing Charge') 
                                            <i class="las la-info-circle text-info proccessing-fee-info" data-bs-toggle="tooltip" title="@lang('Processing charge for withdraw method')"></i>
                                        </span>
                                        <span class="text-danger">{{ gs('cur_sym') }}<span class="processing-fee">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-3 pt-2 border-top border-secondary border-opacity-25">
                                        <span class="text-white fs-5">@lang('Receivable')</span>
                                        <span class="text-success fs-5 fw-bold">{{ gs('cur_sym') }}<span class="final-amount">@lang('0.00')</span> {{ __(gs('cur_text')) }}</span>
                                    </div>

                                    <div class="gateway-conversion d-none mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-white-50">@lang('Conversion')</span>
                                            <span class="text-white conversion-rate"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="conversion-currency d-none mb-4">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-white-50">@lang('In') <span class="gateway-currency"></span></span>
                                            <span class="text-white fw-bold in-currency"></span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 pulse-animation mt-3" disabled style="background: var(--grad-primary); border: none; padding: 12px; font-size: 16px; font-weight: 600;">
                                        @lang('Confirm Withdraw')
                                    </button>
                                    
                                    <p class="text-white-50 text-center mt-3 small">
                                        <i class="las la-shield-alt"></i> @lang('Safely withdraw your funds using our highly secure process.')
                                    </p>
                                </div>
                            </div>
                        </div>
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
                $('.payment-item').css('background', 'rgba(255,255,255,0.05)').removeClass('border-primary');
                $(this).closest('.payment-item').css('background', 'rgba(var(--rgb-primary), 0.2)').addClass('border-primary');
                
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
