@extends('theme-views.layouts.app')

@section('title', translate('my_wallet').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset('assets/css/daterangepicker.css')}}">
<style>
    /* Custom styles for the modern modal */
    .form--check-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .form--check-card:hover {
        border-color: #7E2AFA !important;
        box-shadow: 0 4px 15px rgba(107, 115, 255, 0.1);
    }
    .form--check-card input:checked {
        display: none;
    }
    .form--check-card input:checked + .form-check-label {
        position: relative;
    }

    .form--check-card input:checked + .form-check-label::before {
        content: '✔';
        position: absolute;
        top: -10px;
        right: -5px;
        background-color: #7E2AFA;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border: 2px solid white;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(107, 115, 255, 0.25) !important;
      border-color: #6B73FF !important;
    }

    #add_fund_to_wallet_form_btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 115, 255, 0.3);
    }
</style>
@endpush

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="tab-content">
                @include('theme-views.users-profile.user-wallet._partial-my-wallet-nav-tab')
                @php($addFundsToWallet = getWebConfig(name: 'add_funds_to_wallet'))
                <div class="tab-content">
                    <div class="my-wallet-card mt-4 mb-32px">
                        <div class="row g-4 g-xl-5">
                            <div class="col-lg-6">
                                <div class="d-flex flex-wrap mb-3">
                                    <h6 class="trx-title letter-spacing-0 font-bold mb-3 w-100">
                                        {{ translate('deposit_wallet') }}
                                        @if($addFundsToWallet && count($addFundBonusList) > 0)
                                            <span class="fs-18 float-end cursor-pointer" data-bs-toggle="modal"
                                                  data-bs-target="#howToUseModal">
                                        <span data-bs-toggle="tooltip"  data-bs-placement="left" title="{{translate('Click_here_to_see_instructions')}}">
                                                <i class="bi bi-info-circle"></i>
                                        </span>
                                    </span>
                                        @endif
                                    </h6>
                                    <div class="my-wallet-card-content-2 gap-20 w-100 ">
                                        <div class="info">
                                            <img loading="lazy"
                                                 src="{{ theme_asset('assets/img/icons/wallet-img.png') }}"
                                                 alt="{{ translate('wallet') }}">
                                            <div>{{ translate('deposit_balance') }}</div>
                                        </div>

                                        <div>
                                            @if ($addFundsToWallet)
                                                <div class="d-flex justify-content-end">
    <div class="dropdown">
        <button class="btn btn-base dropdown-toggle" type="button" id="addFundDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-plus-circle-fill fs-18"></i>
            {{ translate('add_Fund') }}
        </button>
        <ul class="dropdown-menu" aria-labelledby="addFundDropdown">
          <li>
    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addFundToWallet">
        <i class="bi bi-credit-card fs-18"></i> {{ translate('Add Fund via Gateway') }}
    </a>
</li>

            <li>
                <a class="dropdown-item" href="/account/user/deposit">
                    <i class="bi bi-bank fs-18"></i> {{ translate('Add Fund via Bank') }}
                </a>
            </li>
           <li>
    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addFundFromCurrentBalanceModal">
        <i class="bi bi-wallet2 fs-18"></i> {{ translate('Add Fund via Current Balance') }}
    </a>
</li>
        </ul>
    </div>
</div>

											
											{{-- Add Fund From Current Balance Modal --}}
<div class="modal fade" id="addFundFromCurrentBalanceModal" tabindex="-1" aria-labelledby="addFundFromCurrentBalanceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title w-100 text-center fw-bold" id="addFundFromCurrentBalanceLabel">{{ translate('Transfer to Deposit Wallet') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form action="{{ route('customer.transfer-balance-to-wallet') }}" method="post" class="needs-validation" novalidate>
                    @csrf

                    {{-- Current Balance Card --}}
                    <div class="mb-3">
                        <label class="form-label text-muted">{{ translate('From') }}</label>
                        <div class="d-flex align-items-center bg-light p-3" style="border-radius: 0.75rem;">
                            <div class="me-3">
                                <i class="bi bi-wallet2 fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-normal">{{ translate('Your Current Balance') }}</h6>
                                <h4 class="fw-bold text-dark mb-0">{{ \App\Utils\currency_converter($current_balance) }}</h4>
                            </div>
                        </div>
                    </div>

                    {{-- Transfer Icon --}}
                    <div class="text-center my-2">
                        <i class="bi bi-arrow-down-circle-fill fs-1 text-primary-light"></i>
                    </div>

                    {{-- Deposit Wallet Card --}}
                    <div class="mb-4">
                        <label class="form-label text-muted">{{ translate('To') }}</label>
                        <div class="d-flex align-items-center bg-light p-3" style="border-radius: 0.75rem;">
                             <div class="me-3">
                                <img loading="lazy" src="{{ theme_asset('assets/img/icons/wallet-img.png') }}" alt="{{ translate('wallet') }}" style="width: 40px;">
                            </div>
                            <div>
                                <h6 class="mb-0 fw-normal">{{ translate('Deposit Wallet') }}</h6>
                                <h4 class="fw-bold text-success mb-0">{{ webCurrencyConverter($totalWalletBalance ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    {{-- Amount Input --}}
                    <div class="form-group mb-4">
                         <label for="transferAmountInput" class="form-label fw-medium">{{ translate('Amount to Transfer') }}</label>
                        <div class="input-group">
                             <span class="input-group-text bg-transparent border-end-0" style="font-size: 1.2rem;">{{ \App\Utils\BackEndHelper::currency_symbol() }}</span>
                            <input type="number" class="form-control form-control-lg border-start-0" id="transferAmountInput" name="amount"
                                   placeholder="0.00"
                                   min="1" max="{{ $current_balance }}" required>
                        </div>
                        <div class="invalid-feedback">
                            {{ translate('Please enter a valid amount within your available balance.') }}
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-base btn-lg py-3">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ translate('Confirm Transfer') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <small class="text-muted d-flex align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ translate('Your transfer will be reflected immediately.') }}
                </small>
            </div>
        </div>
    </div>
</div>

                                            @endif
                                            <div>
                                                <h3 class="price">
                                                    @if ($addFundsToWallet)
                                                        <span class="fs-18" data-bs-toggle="tooltip"
                                                              data-bs-placement="bottom"
                                                              title="{{ translate('this_wallet_balance_can_be_used_for_product_purchase_and_if_want_to_add_more_fund_to_wallet,_click_on_add_fund_') }}">
                                                        <i class="bi bi-info-circle"></i>
                                                    </span>
                                                    @endif
                                                    {{ webCurrencyConverter($totalWalletBalance ?? 0) }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if($addFundsToWallet && count($addFundBonusList) > 0)
                                <div class="col-lg-6">
                                    <div class="overflow-hidden h-100 d-flex align-items-end">
                                        <div class="recommended-slider-wrapper w-100">
                                            <div class="add-fund-slider owl-theme owl-carousel">
                                                @foreach ($addFundBonusList as $bonus)
                                                    <div class="add-fund-swiper-card position-relative z-1 border border-primary rounded-3 py-4 px-5 ms-1 overflow-hidden">
                                                        <div class="item">
                                                            <div class="w-100">
                                                                <h4 class="mb-2 text-primary">{{ $bonus->title }}</h4>
                                                                <p class="mb-2 text-dark">{{ translate('valid_till') }} {{ date('d M, Y',strtotime($bonus->end_date_time)) }}</p>
                                                            </div>
                                                            <div>
                                                                @if ($bonus->bonus_type == 'percentage')
                                                                    <p>{{ translate('add_fund_to_wallet') }} {{ webCurrencyConverter($bonus->min_add_money_amount) }} {{ translate('and_enjoy') }} {{ $bonus->bonus_amount }}
                                                                        % {{ translate('bonus') }}</p>
                                                                @else
                                                                    <p>{{ translate('add_fund_to_wallet') }} {{ webCurrencyConverter($bonus->min_add_money_amount) }} {{ translate('and_enjoy') }} {{ webCurrencyConverter($bonus->bonus_amount) }} {{ translate('bonus') }}</p>
                                                                @endif
                                                                <p class="fw-bold text-primary mb-0">{{ $bonus->description ? Str::limit($bonus->description, 50):'' }}</p>
                                                            </div>
                                                            <img loading="lazy" class="slider-card-bg-img" width="50"
                                                                 src="{{ theme_asset('assets/img/media/add_fund_vector.png') }}"
                                                                 alt="{{ translate('add_fund') }}">
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-6">
                                    <div class="my-wallet-card-content h-100">
                                        <h6 class="subtitle pt-4">{{ translate('how_to_use') }}</h6>
                                        <ul>
    <li>
        {{ translate('deposit_money_into_your_wallet_for_future_use') }}
    </li>
    <li>
        {{ translate('use_your_wallet_balance_to_make_quick_and_easy_payments') }}
    </li>
    <li>
        {{ translate('earn_rewards_when_you_use_your_wallet_for_transactions') }}
    </li>
    <li>
        {{ translate('track_your_wallet_balance_and_transaction_history') }}
    </li>
</ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="trx-table">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="trx-title letter-spacing-0 font-bold text-capitalize">{{ translate('transaction_history') }}</h6>

                            <div class="dropdown">
                                <button type="button"
                                    id="transactionFilterBtn"
                                    class="btn border-border px-3 py-1 text-dark fs-14 d-flex align-items-center gap-10"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                     {{ translate('filter') }}
                                     <span class="position-relative">
                                        <i class="bi bi-funnel-fill text--primary {{ $filterCount > 0 ? 'fs-20' : '' }}"></i>
                                         @if($filterCount > 0)
                                            <span class="count bg-danger top-0">
                                                {{ $filterCount }}
                                            </span>
                                         @endif
                                     </span>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end shadow transaction-filter_dropdown">
                                    <form action="{{ route('wallet') }}" method="get">
                                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                            <h5> {{ translate('filter_data') }}</h5>
                                            <button id="filterCloseBtn" type="button" class="btn btn-reset text-absolute-white border-0 rounded-circle fs-10 lh-1 p-1 m-0">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div class="p-3 overflow-auto max-h-290px">
                                            <div class="mb-4">
                                                <h6 class="mb-3"> {{ translate('filter_by') }}</h6>
                                                <div class="d-flex gap-3 transaction_filter_by">
                                                    <label type="button"
                                                           class="btn p-2 min-w-60px {{ $filterBy == '' || $filterBy == 'all' ? 'btn-base-outline' : 'btn-outline-secondary' }}">
                                                        {{ translate('all') }}
                                                        <input type="radio" name="filter_by" hidden
                                                               value="all" {{ $filterBy == '' || $filterBy == 'all' ? 'checked' : '' }}>
                                                    </label>
                                                    <label type="button"
                                                           class="btn p-2 min-w-60px {{ $filterBy == 'debit' ? 'btn-base-outline' : 'btn-outline-secondary' }}">
                                                        {{ translate('debit') }}
                                                        <input type="radio" name="filter_by" hidden
                                                               value="debit" {{ $filterBy == 'debit' ? 'checked' : '' }}>
                                                    </label>
                                                    <label type="button"
                                                           class="btn p-2 min-w-60px {{ $filterBy == 'credit' ? 'btn-base-outline' : 'btn-outline-secondary' }}">
                                                        {{ translate('credit') }}
                                                        <input type="radio" name="filter_by" hidden
                                                               value="credit" {{ $filterBy == 'credit' ? 'checked' : '' }}>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <h6 class="mb-3"> {{ translate('date_range') }}</h6>
                                                <div class="position-relative">
                                                    <span class="bi bi-calendar icon-absolute-on-right"></span>
                                                    <input type="text" id="dateRangeInput" name="transaction_range"
                                                           class="form-control" placeholder="{{ translate('Select_Date') }}" value="{{ $transactionRange ?? '' }}" />
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <h6 class="mb-3"> {{ translate('earn_by') }}</h6>
                                                <div class="d-flex flex-column gap-3 transaction_earn_by">
                                                    <label
                                                        class="d-flex justify-content-between align-items-center title-color">
                                                        <span>{{ translate('Order_Transactions') }}</span>
                                                        <input type="checkbox" class="earn-checkbox"
                                                               name="types[]" value="order_place"
                                                            {{ in_array('order_place', $transactionTypes) ? 'checked' : '' }}>
                                                    </label>
                                                    <label
                                                        class="d-flex justify-content-between align-items-center title-color">
                                                        <span>{{ translate('Order_Refund') }}</span>
                                                        <input type="checkbox"
                                                               class="earn-checkbox border-dark"
                                                               name="types[]" value="order_refund"
                                                            {{ in_array('order_refund', $transactionTypes) ? 'checked' : '' }}>
                                                    </label>
                                                    <label
                                                        class="d-flex justify-content-between align-items-center title-color">
                                                        <span>{{ translate('Converted_from_Loyalty_Point') }}</span>
                                                        <input type="checkbox"
                                                               class="earn-checkbox border-dark"
                                                               name="types[]" value="loyalty_point"
                                                            {{ in_array('loyalty_point', $transactionTypes) ? 'checked' : '' }}>
                                                    </label>
                                                    <label
                                                        class="d-flex justify-content-between align-items-center title-color">
                                                        <span>{{ translate('Added_via_Payment_Method') }}</span>
                                                        <input type="checkbox"
                                                               class="earn-checkbox border-dark"
                                                               name="types[]" value="add_fund"
                                                            {{ in_array('add_fund', $transactionTypes) ? 'checked' : '' }}>
                                                    </label>
                                                    <label
                                                        class="d-flex justify-content-between align-items-center title-color">
                                                        <span>{{ translate('Add_fund_by_admin') }}</span>
                                                        <input type="checkbox"
                                                               class="earn-checkbox border-dark"
                                                               name="types[]" value="add_fund_by_admin"
                                                            {{ in_array('add_fund_by_admin', $transactionTypes) ? 'checked' : '' }}>
                                                    </label>
                                                    <label
                                                        class="d-flex justify-content-between align-items-center title-color">
                                                        <span>{{ translate('Earned_by_Referral') }}</span>
                                                        <input type="checkbox"
                                                               class="earn-checkbox border-dark"
                                                               name="types[]" value="earned_by_referral"
                                                            {{ in_array('earned_by_referral', $transactionTypes) ? 'checked' : '' }}>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow-lg p-3 d-flex flex-row gap-3">
                                            <a href="{{ route('wallet') }}" class="btn btn-outline-secondary w-100">
                                                {{ translate('clear_filter') }}
                                            </a>
                                            <button type="submit" class="btn btn-base w-100">
                                                {{ translate('filter') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table __table table-borderless centered--table vertical-middle text-text-2">
                                <tbody>
                                @foreach($walletTransactionList as $key=>$item)
                                    @if ($item['admin_bonus'] > 0)
                                        <tr>
                                            <td class="bg-section rounded">
                                                <div class="trx-history-order">
                                                    <h5 class="direction-ltr mb-1 text-start">
                                                        {{'+ '. webCurrencyConverter( $item['admin_bonus'])}}
                                                    </h5>
                                                    <div>{{ translate('admin_bonus') }}</div>
                                                </div>
                                            </td>
                                            <td class="bg-section ">
                                                <div class="date word-nobreak d-none d-md-block">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                            </td>
                                            <td class=" bg-section pe-md-5 text-end rounded">
                                                <div class="date word-nobreak d-md-none">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                                <div class="text-{{ $item['credit'] ?'success': 'danger'}}">{{ $item['credit'] ? 'Credit' : 'Debit'}}</div>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td class="bg-section rounded">
                                            <div class="trx-history-order">
                                                <h5 class="direction-ltr mb-1 text-start">
                                                    {{ $item['debit'] != 0 ? ' - '.webCurrencyConverter(amount: $item['debit']): ' + '.webCurrencyConverter(amount: $item['credit']) }}
                                                </h5>
                                                <div>
                                                    @if ($item['transaction_type'] == 'add_fund_by_admin')
                                                        {{translate('add_fund_by_admin')}} {{ $item['reference'] =='earned_by_referral' ? '('.translate($item['reference']).')' : '' }}
                                                    @elseif($item['transaction_type'] == 'order_place')
                                                        {{translate('order_place')}}
                                                    @elseif($item['transaction_type'] == 'loyalty_point')
                                                        {{translate('converted_from_loyalty_point')}}
                                                    @elseif($item['transaction_type'] == 'add_fund')
                                                        {{translate('added_via_payment_method')}}
                                                    @else
                                                        {{ translate($item['transaction_type']) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="bg-section ">
                                            <div class="date word-nobreak d-none d-md-block">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                        </td>
                                        <td class=" bg-section pe-md-5 text-end rounded">
                                            <div class="date word-nobreak d-md-none">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                            <div class="text-{{ $item['credit'] ?'success': 'danger'}}">{{ $item['credit'] ? 'Credit' : 'Debit'}}</div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if ($walletTransactionList->count() == 0)
                                <div class="w-100">
                                    <div class="text-center mb-5">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/data.svg') }}"
                                             alt="{{ translate('wallet_transaction') }}">
                                        <h5 class="my-3">{{translate('no_Transaction_Found')}}</h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if(count($walletTransactionList)>0)
                        <div class="d-flex justify-content-end w-100 overflow-auto mt-3" id="paginator-ajax">
                            {{$walletTransactionList->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($addFundsToWallet)
            <!-- Modern Add Fund to Wallet Modal -->
<div class="modal fade" id="addFundToWallet" tabindex="-1" aria-labelledby="addFundToWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Side: Information & Branding -->
                    <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-4" style="background: linear-gradient(135deg, #7E2AFA 0%, #110620 100%); color: white; border-top-left-radius: 20px; border-bottom-left-radius: 20px;">
                        <div>
                            <h4 class="fw-bold mb-3" style="font-family: 'Inter', sans-serif; color: white;">{{ translate('Secure & Instant') }}</h4>
                            <p class="small">{{ translate('Add funds to your wallet using our secure payment gateways. Your balance will be updated instantly.') }}</p>
                        </div>
                        <div class="text-center">
                             <img src="https://placehold.co/200x200/FFFFFF/333333?text=SecurePay" alt="Secure Payment" class="img-fluid" style="max-width: 150px; border-radius: 15px;">
                        </div>
                        <div>
                            <p class="small mb-0">{{ translate('Trusted by millions') }}</p>
                        </div>
                    </div>

                    <!-- Right Side: Form & Payment -->
                    <div class="col-lg-7 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                             <h4 class="modal-title fw-bold" id="addFundToWalletModalLabel" style="font-family: 'Inter', sans-serif; color: #333;">{{ translate('Add Money to Wallet') }}</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="{{ route('customer.add-fund-request') }}" method="post">
                            @csrf
                            <!-- Amount Input -->
                            <div class="mb-4">
                                <label for="add-fund-amount-input" class="form-label fw-medium">{{ translate('Enter Amount') }}</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px;">{{ \App\Utils\BackEndHelper::currency_symbol() }}</span>
                                    <input type="number" class="form-control border-0 shadow-none"
                                           min="1" name="amount" id="add-fund-amount-input" autocomplete="off" required
                                           placeholder="0.00" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; background-color: #f8f9fa;">
                                </div>
                            </div>

                            <input type="hidden" value="web" name="payment_platform" required>
                            <input type="hidden" value="{{ request()->url() }}" name="external_redirect_link" required>

                            @if(count($paymentGatewayList) > 0)
                                <!-- Payment Method Selection -->
                                <div id="add-fund-list-area">
                                    <h5 class="mb-3 fw-medium">{{ translate('Choose Payment Method') }}</h5>
                                    <div class="row g-2">
                                        @foreach ($paymentGatewayList as $gateway)
                                            <div class="col-6">
                                                <label class="form-check form--check-card p-3 rounded-3 border">
                                                    <input type="radio" class="form-check-input" name="payment_method" value="{{ $gateway->key_name }}" required>
                                                    <div class="form-check-label d-flex flex-column align-items-center justify-content-center text-center">
                                                        @php( $payment_method_title = !empty($gateway->additional_data) ? (json_decode($gateway->additional_data)->gateway_title ?? ucwords(str_replace('_',' ', $gateway->key_name))) : ucwords(str_replace('_',' ', $gateway->key_name)) )
                                                        @php( $payment_method_img = !empty($gateway->additional_data) ? json_decode($gateway->additional_data)->gateway_image : '' )
                                                        <img loading="lazy" alt="{{ $payment_method_title }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/payment_modules/gateway_image/'.$payment_method_img, type:'banner') }}"
                                                             class="mb-2" style="max-height: 40px; max-width: 100%;">
                                                        <span class="small fw-medium">{{ $payment_method_title }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid pt-4">
                                    <button type="submit" class="btn btn-base btn-lg" id="add_fund_to_wallet_form_btn" style="background: #7E2AFA; border: none; border-radius: 12px; padding: 14px;">{{ translate('Proceed to Add Fund') }}</button>
                                </div>
                            @else
                                <!-- No Payment Gateway Message -->
                                <div class="text-center p-4 bg-light rounded-3">
                                    <img loading="lazy" src="{{ theme_asset('assets/img/icons/data.svg') }}" alt="" class="mb-3" style="width: 80px;">
                                    <h6 class="text-muted">{{ translate('No Payment Gateways Available') }}</h6>
                                    <p class="small text-muted">{{ translate('Please contact support to enable payment options.') }}</p>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



        @endif

        <div class="modal fade" id="howToUseModal" tabindex="-1" aria-labelledby="howToUseModalModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="my-wallet-card-content h-100 p-5">

                            <h6 class="subtitle pb-3">
                                {{ translate('how_to_use') }}
                                <span class="float-end">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </span>
                            </h6>
                            <ul>
                                <li>
                                    {{ translate('earn_money _o_your_wallet_by_completing_the_offer_&_challenged') }}
                                </li>
                                <li>
                                    {{ translate('convert_your_loyalty_points_into_wallet_money') }}
                                </li>
                                <li>
                                    {{ translate('admin_also_rewards_their_top_customers_with_wallet_money') }}
                                </li>
                                <li>
                                    {{ translate('send_your_wallet_money_while_order') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ theme_asset('assets/js/daterangepicker.min.js')}}"></script>
    <script src="{{ theme_asset('assets/js/user-wallet.js')}}"></script>
@endpush
