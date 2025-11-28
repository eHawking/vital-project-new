@php use App\Utils\Helpers; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('payment').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')

    <section class="breadcrumb-section pt-20px">
        <div class="container">
            <div class="section-title mb-4">
                <div
                    class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{route('home')}}">{{translate('home')}}</a>
                        </li>
                        <li>
                            <a href="{{route('shop-cart')}}">{{translate('cart')}}</a>
                        </li>
                        <li>
                            <a href="{{route('checkout-details')}}">{{translate('checkout')}}</a>
                        </li>
                        <li>
                            <a href="{{url()->current()}}">{{translate('payment')}}</a>
                        </li>
                    </ul>
                    <div class="ms-auto ms-md-0">
                        @if(auth('customer')->check())
                            <a href="{{route('shop-cart')}}" class="text-base custom-text-link">
                                {{ translate('check_All_CartList') }}
                            </a>
                        @else
                            <a href="javascript:" class="text-base custom-text-link customer_login_register_modal">
                                {{ translate('check_All_CartList') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="payment-section section-gap pt-4">
        <div class="container">
            <h3 class="d-flex justify-content-center justify-content-sm-start mb-3 mb-lg-4">{{translate('payment')}}</h3>
            <div class="row g-4 payment-method-list-page">
                <div class="col-lg-7">
                    <ul class="checkout-flow">
                        <li class="checkout-flow-item active">
                            <a href="{{route('shop-cart')}}">
                                <span class="serial">{{ translate('1') }}</span>
                                <span class="icon">
                                    <i class="bi bi-check"></i>
                                </span>
                                <span class="text">{{translate('cart')}}</span>
                            </a>
                        </li>
                        <li class="line"></li>
                        <li class="checkout-flow-item active">
                            <a href="{{route('checkout-details')}}">
                                <span class="serial">{{ translate('2') }}</span>
                                <span class="icon">
                                    <i class="bi bi-check"></i>
                                </span>
                                <span class="text text-capitalize">{{translate('shipping_details')}}</span>
                            </a>
                        </li>
                        <li class="line"></li>
                        <li class="checkout-flow-item current">
                            <a href="javascript:">
                                <span class="serial">{{ translate('3') }}</span>
                                <span class="icon">
                                    <i class="bi bi-check"></i>
                                </span>
                                <span class="text">{{translate('payment')}}</span>
                            </a>
                        </li>
                    </ul>

                    @if(!$activeMinimumMethods)
                        <div class="d-flex justify-content-center py-5 align-items-center">
                            <div class="text-center">
                                <img src="{{ theme_asset(path: 'assets/img/icons/nodata.svg') }}" alt="" class="mb-4" width="70">
                                <h5 class="fs-14 text-muted py-4">{{ translate('payment_methods_are_not_available_at_this_time.') }}</h5>
                            </div>
                        </div>
                    @else
                        <div class="delivery-information">
                            <h6 class="font-bold letter-spacing-0 title mb-20px text-capitalize">
                                {{ translate('select_payment_method') }}
                            </h6>
                            <div class="payment-area">
                                  @if(!auth('customer')->check() && $cashOnDeliveryBtnShow && $cash_on_delivery['status'])
                                    <label class="payment-item">
                                        <form action="{{route('checkout-complete')}}" method="get" class="checkout-payment-form payment-method-form checkout-cash-on-payment">
                                            <input type="radio" hidden name="payment_method" value="cash_on_delivery" data-form=".checkout-cash-on-payment">
                                            <input type="hidden" class="form-control" name="bring_change_amount" id="bring_change_amount_value">
                                            <div class="payment-item-card payment-method_parent bg-transparent next-btn-enable cash_on_delivery">
                                                <img loading="lazy" src="{{theme_asset('assets/img/checkout/cash-on.png')}}"
                                                     class="icon"
                                                     alt="{{ translate('checkout') }}">
                                                <button class="content bg-transparent border-0">
                                                    <h6 class="subtitle text-start">{{translate('cash_on_delivery')}}</h6>
                                                    <p class="text-start">
                                                        {{translate('please_contact_with_deliveryman_to_confirm_your_pay_and_receive_your_order.')}}
                                                    </p>
                                                </button>
                                            </div>
                                        </form>
                                    </label>

                                    <div class="collapse py-12" id="bring_change_amount" data-more="{{ translate('See_More') }}" data-less="{{ translate('See_Less') }}">
                                        <div class="bring_change_amount_details row justify-content-start align-items-center rounded g-2 p-3">
                                            <div class="col-sm-6">
                                                <h6 class="fs-14 fw-semibold">
                                                    {{ translate('Bring_Change_Instruction') }}
                                                </h6>
                                                <p class="mb-0 fs-14 title-color opacity-50">
                                                    {{ translate('Insert_amount_if_you_need_deliveryman_to_bring') }}
                                                </p>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="fs-14 fw-semibold title-color mb-1" for="bring_change_amount_input">
                                                    {{ translate('Change_Amount') }} ({{ getCurrencySymbol(type: 'web') }})
                                                </label>
                                                <input type="text" class="form-control max-w-210px only-integer-input-field" id="bring_change_amount_input" placeholder="{{ translate('Amount') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center bring-change-amount-container d--none">
                                        <a id="bring_change_amount_btn" class="btn btn-link text-primary text-center text-capitalize text-decoration-none shadow-none border-0 fs-14 p-0 mb-10px" data-bs-toggle="collapse" href="#bring_change_amount" role="button" aria-expanded="false" aria-controls="change_amount">
                                            {{ translate('see_more') }}
                                        </a>
                                    </div>

                                @endif

                                @if(auth('customer')->check() && $wallet_status==1)
                                    <label class="payment-item">
                                        <div class="payment-item-card payment-method_parent">
                                            <img loading="lazy"
                                                 src="{{theme_asset('assets/img/checkout/digital-wallet.png')}}"
                                                 class="icon"
                                                 alt="{{ translate('checkout') }}">
                                            <button class="content bg-transparent border-0"
                                                    type="submit" data-bs-toggle="modal"
                                                    data-bs-target="#wallet_submit_button">
                                                <h6 class="subtitle text-start">{{translate('wallet')}}</h6>
                                                <p class="text-start">
                                                    {{translate('Payment_using_your_wallet_balance')}}
                                                </p>
                                            </button>
                                        </div>
                                    </label>
                                @endif

                                @if(isset($offline_payment) && $offline_payment['status'] && count($offline_payment_methods) > 0)
                                    <label class="payment-item">
                                        <form  action="{{route('offline-payment-checkout-complete')}}" method="get">
                                            <div class="payment-item-card payment-method_parent">
                                                <img loading="lazy"
                                                src="{{ theme_asset('assets/img/icons/cash-payment.png') }}"
                                                class="dark-support icon"
                                                alt="{{ translate('offline_payments') }}">
                                                <span class="content bg-transparent border-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#offline_payment_submit_button">
                                                    <h6 class="subtitle text-start">{{translate('pay_offilne')}}</h6>
                                                    <p class="text-start">
                                                        {{translate('please_contact_with_deliveryman_to_confirm_your_pay_and_receive_your_order')}}
                                                    </p>
                                                </span>
                                            </div>
                                        </form>
                                    </label>
                                @endif

                                @if($digital_payment['status'] == 1)
                                    @if(count($payment_gateways_list) > 0 || (isset($offline_payment) && $offline_payment['status'] && count($offline_payment_methods) > 0))
                                        <label class="payment-item">
                                            <div class="payment-item-card payment-method_parent">
                                                <div class="digital_payment_btn">
                                                    <img loading="lazy"
                                                         src="{{theme_asset('assets/img/checkout/card-pos.png')}}" class="icon"
                                                         alt="{{ translate('checkout') }}">
                                                </div>
                                                <div class="content">
                                                    <div class="digital_payment_btn">
                                                        <h6 class="subtitle">{{translate('digital_payment')}}</h6>
                                                        <p>
                                                            {{translate('please_confirm_your_verified_account_to_pay_through_your_bank_account')}}
                                                        </p>
                                                    </div>
                                                    <div class="d--none" id="digital_payment">
                                                        <div class="mt-3 row g-2">
                                                            @foreach ($payment_gateways_list as $payment_gateway)
                                                                <div class="col-xl-3 col-md-4 col-6">
                                                                    <div
                                                                        class="digital-payment-card card d-flex align-items-center justify-content-center overflow-hidden">
                                                                        <form method="post"
                                                                              class="w-100 payment-method-form checkout-payment-{{ $payment_gateway->key_name }}"
                                                                              action="{{ route('customer.web-payment-request') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="user_id"
                                                                                   value="{{ auth('customer')->check() ? auth('customer')->user()->id : session('guest_id') }}">
                                                                            <input type="hidden" name="customer_id"
                                                                                   value="{{ auth('customer')->check() ? auth('customer')->user()->id : session('guest_id') }}">
                                                                            <input type="radio" hidden name="payment_method" value="{{ $payment_gateway->key_name }}" data-form=".checkout-payment-{{ $payment_gateway->key_name }}">
                                                                            <input type="hidden" name="payment_platform"
                                                                                   value="web">

                                                                            @if ($payment_gateway->mode == 'live' && isset($payment_gateway->live_values['callback_url']))
                                                                                <input type="hidden" name="callback"
                                                                                       value="{{ $payment_gateway->live_values['callback_url'] }}">
                                                                            @elseif ($payment_gateway->mode == 'test' && isset($payment_gateway->test_values['callback_url']))
                                                                                <input type="hidden" name="callback"
                                                                                       value="{{ $payment_gateway->test_values['callback_url'] }}">
                                                                            @else
                                                                                <input type="hidden" name="callback" value="">
                                                                            @endif

                                                                            <input type="hidden" name="external_redirect_link"
                                                                                   value="{{ route('web-payment-success') }}">
                                                                            @php($additional_data = $payment_gateway['additional_data'] != null ? json_decode($payment_gateway['additional_data']) : [])
                                                                            <button
                                                                                class="bg-transparent border-0 h-100 w-100 px-2 justify-content-center position-absolute top-0 digital-payment-card_btn next-btn-enable"
                                                                                type="submit">
                                                                                @if($additional_data != null && isset($additional_data->gateway_image) && file_exists(base_path('storage/app/public/payment_modules/gateway_image/'.$additional_data->gateway_image)))
                                                                                    <img loading="lazy" width="100"
                                                                                         class="dark-support mw-100"
                                                                                         alt="{{ translate('gateway') }}"
                                                                                         src="{{ getStorageImages(path: null, type: 'payment-banner', source: 'storage/app/public/payment_modules/gateway_image/'.($additional_data != null ? ($additional_data->gateway_image ?? '') : '')) }}">
                                                                                @else
                                                                                    <h6 class="fs-14 fw-bold opacity-75">
                                                                                        {{ ucwords(str_replace('_', ' ', $payment_gateway->key_name)) }}
                                                                                    </h6>
                                                                                @endif
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex digital_payment_btn flex-wrap flex-md-nowrap justify-content-between align-items-center mt-3 row-gap-3 column-gap-3">
                                                        <span>{{translate('pay_with_secure_Digital_payment_gateways')}}</span>
                                                        <img loading="lazy"
                                                             src="{{theme_asset('assets/img/checkout/payment-methods.png')}}"
                                                             class="mw-100" alt="{{ translate('checkout') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endif
                                @endif

                                @if(auth('customer')->check() && $wallet_status==1)
    <div class="modal fade" id="wallet_submit_button" tabindex="-1" aria-labelledby="walletPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title w-100 text-center" id="walletPaymentModalLabel">{{translate('Pay with Wallet')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @php($customer_balance = auth('customer')->user()->wallet_balance)
                    @php($remain_balance = $customer_balance - $amount)
                    <form action="{{route('checkout-complete-wallet')}}" method="get" class="needs-validation checkout-wallet-payment-form">
                        @csrf
                        <div class="text-center mb-4">
                            <img src="{{theme_asset('assets/img/checkout/digital-wallet.png')}}" alt="Wallet" style="width: 80px; height: 80px;">
                            <p class="text-muted mt-2">{{ translate('Confirm your payment using your wallet balance.') }}</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-light p-3 mb-2" style="border-radius: 10px;">
                            <div>
                                <span class="fs-6 text-muted">{{translate('Total Order Amount')}}</span>
                                <h4 class="fw-bold mb-0">{{webCurrencyConverter($amount)}}</h4>
                            </div>
                            <i class="bi bi-dash-circle fs-2 text-danger"></i>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-light p-3 mb-3" style="border-radius: 10px;">
                            <div>
                                <span class="fs-6 text-muted">{{translate('Your Wallet Balance')}}</span>
                                <h4 class="fw-bold mb-0">{{webCurrencyConverter($customer_balance)}}</h4>
                            </div>
                            <i class="bi bi-wallet2 fs-2 text-primary"></i>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center p-3" style="border-radius: 10px; background-color: #e9f5ff;">
                            <div>
                                <span class="fs-6 text-muted">{{translate('Remaining Balance')}}</span>
                                <h4 class="fw-bold mb-0 text-success">{{webCurrencyConverter($remain_balance)}}</h4>
                            </div>
                            <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                        </div>

                        @if ($remain_balance < 0)
                            <div class="alert alert-danger text-center mt-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{translate('Insufficient balance to complete this purchase!')}}
                            </div>
                        @endif

                        <div class="modal-footer border-0 p-0 mt-4">
                             <button type="submit" class="btn btn-base w-100 py-3 fs-6 fw-bold" style="border-radius: 10px;" {{$remain_balance >= 0 ? '' : 'disabled'}}>
                                 <i class="bi bi-shield-check me-2"></i>
                                 {{translate('Confirm & Pay')}}
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

                                @if ($digital_payment['status']==1)
                                    <div class="modal fade offline-payment-modal" id="offline_payment_submit_button">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header px-sm-5">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLongTitle">{{translate('offline_payment')}}</h5>
                                                    <button type="button" class="btn-close outside" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <form action="{{route('offline-payment-checkout-complete')}}" method="post"
                                                      class="needs-validation form-loading-button-form">
                                                    @csrf
                                                    <div class="modal-body p-3 p-md-5">

                                                        <div class="text-center px-5">
                                                            <img loading="lazy"
                                                                 src="{{ theme_asset('assets/img/offline-payments.png') }}"
                                                                 alt="{{ translate('offline_payments') }}">
                                                            <p class="py-2">
                                                                {{ translate('pay_your_bill_using_any_of_the_payment_method_below_and_input_the_required_information_in_the_form') }}
                                                            </p>
                                                        </div>

                                                        <div class="">
                                                            <select class="form-select" id="pay_offline_method"
                                                                    name="payment_by" required>
                                                                <option
                                                                    value="">{{ translate('select_Payment_Method') }}</option>
                                                                @foreach ($offline_payment_methods as $method)
                                                                    <option
                                                                        value="{{ $method->id }}">{{ translate('payment_Method') }}
                                                                        :
                                                                        {{ $method->method_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="" id="method-filed__div">
                                                            <div class="text-center py-5">
                                                                <img loading="lazy" class="pt-5"
                                                                     src="{{ theme_asset('assets/img/offline-payments-vectors.png') }}"
                                                                     alt="{{ translate('offline_payments') }}">
                                                                <p class="py-2 pb-5 text-muted">{{ translate('select_a_payment_method_first') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif


                </div>

                <div class="col-lg-5">
                    @include('theme-views.partials._order-summery')
                </div>
            </div>
        </div>
    </section>

    <span id="route-pay-offline-method-list" data-route="{{ route('pay-offline-method-list') }}"></span>
    <span id="text-redirecting-to-the-payment" data-text="{{ translate('Redirecting_to_the_payment') }}"></span>

@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/payment-page.js') }}"></script>
@endpush
