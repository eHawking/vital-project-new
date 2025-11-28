@extends($activeTemplate . 'layouts.master')
@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Theme Switcher -->
@include($activeTemplate . 'partials.theme-switcher')

<style>
    #generate-epin-btn {
        display: block;
        margin: 0 auto;
        text-align: center;
        color: #000;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        box-shadow: 0 0 10px blue;
        transition: all 0.3s ease;
    }

    #generate-epin-btn:hover {
        background-color: blue;
        box-shadow: 0 0 20px blue;
        color: #fff;
    }

    .padding-bottom {
        padding-bottom: 15px;
    }

    .padding-top {
        padding-top: 15px;
    }

    .dashboard-sidebar {
        top: 0px !important;
    }

    .btn-block {
        border-radius: 0px;
    }

    .dashboard-item:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(126, 42, 252, 0.7);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(126, 42, 252, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(126, 42, 252, 0);
        }
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    .rotating-images {
        position: relative;
        overflow: hidden;
        width: 100px; /* Width of the images */
        height: 100px; /* Height of the images */
    }

    .rotating-images img {
        position: absolute;
        top: 0;
        left: 0;
        transition: all 0.9s ease; /* Smooth transition */
    }

    .rotating-images img.small {
        transform: scale(0.1); /* Make the small image smaller */
    }

    .progress-bar-container {
        margin: 20px 0;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        background-color: #7e2afc;
        color: white;
        font-weight: bold;
        text-align: center;
        line-height: 20px;
    }
	.g-3 {
		margin: 0px;
		padding: 0px;
	}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="notice"></div>
            @php
                $kyc = getContent('kyc.content', true);
            @endphp
            @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
                <div class="alert alert--danger" role="alert">
                    <div class="alert__icon"><i class="fas fa-file-signature"></i></div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('KYC Documents Rejected')</span><br>
                        <small>
                            <i>
                                {{ __(@$kyc->data_values->reject) }}
                                <a class="link-color text--base" data-bs-toggle="modal" data-bs-target="#kycRejectionReason"
                                    href="javascript::void(0)">@lang('Click here')</a> @lang('to show the reason').
                                <a class="link-color text--base" href="{{ route('user.kyc.form') }}">@lang('Click Here')</a>
                                @lang('to Re-submit Documents'). <br>

                                <a class="link-color text--base mt-2" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                            </i>
                        </small>
                    </p>
                </div>
            @elseif (auth()->user()->kv == Status::KYC_UNVERIFIED)
                <div class="alert alert--info" role="alert">
                    <div class="alert__icon"><i class="fas fa-file-signature"></i></div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('KYC Verification Required')</span><br>
                        <small>
                            <i>
                                {{ __(@$kyc->data_values->required) }}
                                <a class="link-color text--base" href="{{ route('user.kyc.form') }}">@lang('Click here')</a>
                                @lang('to submit KYC information').
                            </i>
                        </small>
                    </p>
                </div>
            @elseif(auth()->user()->kv == Status::KYC_PENDING)
                <div class="alert alert--warning" role="alert">
                    <div class="alert__icon"><i class="fas fa-user-check"></i></div>
                    <p class="alert__message">
                        <span class="fw-bold">@lang('KYC Verification Pending')</span><br>
                        <small>
                            <i>
                                {{ __(@$kyc->data_values->pending) }}
                                <a class="link-color text--base" href="{{ route('user.kyc.data') }}">@lang('Click here')</a> @lang('to see your submitted information')
                            </i>
                        </small>
                    </p>
                </div>
            @endif

            @if (gs('notice'))
                <div class="col-lg-12 col-sm-6 mt-4">
                    <div>
                        <div class="card-header">
                            <h5 class="pb-2">@lang('Notice')</h5>
                        </div>
                        <div class="card-body">
                            @if (gs('notice'))
                                <p class="notice-text-inner">@php echo gs('notice') @endphp</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (gs('free_user_notice'))
                <div class="col-lg-12 col-sm-6 mt-4">
                    <div>
                        <div class="card-header">
                            <h5 class="pb-1">@lang('Free User Notice')</h5>
                        </div>
                        <div class="card-body">
                            @if (gs('free_user_notice') != null)
                                <p class="notice-text-inner"> @php echo gs('free_user_notice'); @endphp </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row justify-content-center g-3">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-4">
                <div class="dashboard-item mb-3">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Current Balance')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->balance) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-wallet"></i></div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-item mb-3">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">
                                @lang('DSP Membership')
                            </h6>
                            <h3 class="ammount">
                                @if(auth()->user()->plan_id == 1)
                                    <h3 class="ammount theme-two">Active <span style="text-transform: uppercase;">{{ auth()->user()->dsp_username }}</span></h3>
                                @else
                                    <span class="text--danger">@lang('Inactive')</span>
                                @endif
                            </h3>
                        </div>
                        <div class="right-content">
                            @if(auth()->user()->plan_id == 1)
                                <div class="icon"><i class="fas fa-check-circle"></i></div>
                            @else
                                <div class="icon"><i class="fas fa-times-circle"></i></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="dashboard-item mb-3">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('BV (Bonus Value)')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->bv) }} BV</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-award"></i></div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-item mb-3">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('PV (Point Voucher)')</h6>
                            <h3 class="ammount text--base">{{ getAmount(auth()->user()->pv) }} PV</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-gem"></i></div>
                        </div>
                    </div>
                    @if (auth()->user()->pv >= 100)
                <div id="generateButtonContainer" style="text-align: center; margin: 20px 0;">
                    <button type="button" class="btn btn-primary pulse" data-bs-toggle="modal" data-bs-target="#generateVoucherModal" style="background-color: #7e2afc; position: relative; overflow: hidden;">
                        <i class="fas fa-gift" style="position: relative; z-index: 1;"></i> Generate DSP Gift Voucher
                    </button>
                </div>
            @else
                <div id="progressBarContainer" class="progress-bar-container" style="text-align: center; margin: 20px 0;">
                    <p>You need <strong>{{ 100 - auth()->user()->pv }} PV</strong> to generate a DSP Voucher.</p>
                    <div class="progress" style="height: 10px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ (auth()->user()->pv / 100) * 100 }}%;" 
                             aria-valuenow="{{ auth()->user()->pv }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            @endif
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <div class="dashboard-item card shadow-sm mb-4 rounded">
                    <!-- Current Rank Section -->
                    <div class="dashboard-item-header d-flex justify-content-between align-items-center mb-4">
                        <div class="col-lg-10">
                            <h6 class="title text-muted">Current Rank</h6>
                            <h3 class="ammount mt-2 theme-two font-weight-bold text-primary">{{ $currentRank }}</h3>
                        </div>
                        <div class="col-lg-2 d-flex justify-content-end">
                            <img src="{{ url('assets/images/user/ranks/' . $currentRankImage) }}" 
                                 alt="Current Rank Image" 
                                 class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Total Pairs and Progress Section -->
                    <div class="col-lg-12 mb-4">
                        <h3 class="ammount theme-two font-weight-bold text-primary">Total Pairs: {{ $pairs }}</h3>
                        @if($progress < 100)
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $progress }}%;" 
                                 aria-valuenow="{{ $progress }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        
                        <!-- Additional information about progress -->
                        <h6 class="amount theme-two bg-white rounded p-2 mt-2 shadow-sm">
                            You need {{ $pairsRemaining }} more pairs to reach the <strong>{{ $nextRank }}</strong> rank and earn the <strong>{{ $nextReward }}</strong> reward.
                        </h6>
                        @endif
                    </div>

                    <!-- Next Rank and Reward Section -->
                    <div class="dashboard-item-header d-flex justify-content-between align-items-center">
                        <div class="col-lg-9">
                            <h6 class="title text-muted">Next Rank: {{ $nextRank ?? 'N/A' }}</h6>
                            <h6 class="title text-muted">Next Reward: {{ $nextReward ?? 'N/A' }}</h6>
                        </div>
                        <div class="col-lg-3 d-flex justify-content-end">
                            <div class="rotating-images">
                                <img style="width: 100px; margin: 0; border-radius: 8px;" src="" alt="Reward Image 1">
                                <img style="width: 100px; margin: 0; border-radius: 8px;" src="" alt="Reward Image 2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-3 mt-2">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('E-Pin Credit')</h6>
                            <h3 class="ammount theme-one">{{ getAmount(auth()->user()->epin_credit) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-credit-card"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total DSP Ref Bonus')</h6>
                            <h3 class="ammount theme-one">{{ getAmount(auth()->user()->dsp_ref_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Royalty Bonus')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->royalty_bonus) }} PKR</h3>

                            @if(auth()->user()->is_btp == 1)
                                <!-- Progress Bar -->
                                @php
                                    $royaltyBonus = auth()->user()->royalty_bonus;
                                    $target = 2600;
                                    $progress = ($royaltyBonus / $target) * 100;
                                    $progress = min($progress, 100); // Ensure progress does not exceed 100%
                                @endphp

                                <div class="progress" style="height: 10px; margin-top: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" 
                                         aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <!-- Completion Message -->
                                @if($royaltyBonus >= $target)
                                    <p class="text-success mt-2">You have received the full Royalty Bonus of 2600 PKR from the company.</p>
                                @else
                                    <p class="text-muted mt-2">{{ number_format($target - $royaltyBonus, 2) }} PKR is remaining.</p>
                                @endif
                            @endif
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-star"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total earned Rewards')</h6>
                            <h3 class="ammount theme-one">{{ (auth()->user()->rewards) }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-gift"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Pair Bonus')</h6>
                            <h3 class="ammount theme-one">{{ getAmount(auth()->user()->pair_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-briefcase"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total DDS Ref Bonus')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->dds_ref_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-cart-plus"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Shop Bonus')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->shop_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Shop Ref Bonus')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->shop_reference) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-shopping-basket"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Franchise Bonus')</h6>
                            <h3 class="ammount theme-one">{{ getAmount(auth()->user()->franchise_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-briefcase"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Franchise Ref Bonus')</h6>
                            <h3 class="ammount theme-one">{{ getAmount(auth()->user()->franchise_ref_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-user-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total City Reference')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->city_ref_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-building"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Weekly Bonus')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->weekly_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Product Partner Bonus')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->product_partner_bonus) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Promo Bonus')</h6>
                            <h3 class="ammount theme-two">{{ getAmount(auth()->user()->promo) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-gift"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total DSP Purchase')</h6>
                            <h3 class="ammount theme-one">{{ getAmount(auth()->user()->total_invest) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-tag"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Deposit')</h6>
                            <h3 class="ammount text--base">{{ getAmount($totalDeposit) }} PKR</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Withdraw')</h6>
                            <h3 class="ammount theme-one">{{ getAmount($totalWithdraw) }} PKR</h3>
                        </div>
                        <div class="icon"><i class="fas fa-coins"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Complete Withdraw')</h6>
                            <h3 class="ammount theme-two">{{ getAmount($completeWithdraw) }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Pending Withdraw')</h6>
                            <h3 class="ammount text--base">{{ getAmount($pendingWithdraw) }}</h3>
                        </div>
                        <div class="icon"><i class="fas fa-history"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection
@include($activeTemplate.'partials.voucher_modals')
@if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
    <div class="modal fade" id="kycRejectionReason">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ auth()->user()->kyc_rejection_reason }}</p>
                </div>
            </div>
        </div>
    </div>
@endif


@push('script')

<!-- Include Modern Dashboard Initialization -->
@include($activeTemplate . 'js.modern-dashboard-init')

<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

<script>
$(document).ready(function(){
    // --- START: MODAL TRANSITION FIX ---
    $('#proceedToConfirmBtn').on('click', function() {
        const generateModalEl = document.getElementById('generateVoucherModal');
        const confirmationModalEl = document.getElementById('confirmationModal');

        if (generateModalEl && confirmationModalEl) {
            const generateModal = bootstrap.Modal.getInstance(generateModalEl);
            const confirmationModal = new bootstrap.Modal(confirmationModalEl);

            // Listen for the exact moment the first modal is completely hidden
            generateModalEl.addEventListener('hidden.bs.modal', function (event) {
                // Now that the first modal is gone, show the second one
                confirmationModal.show();
            }, { once: true }); // { once: true } ensures this listener only runs one time

            // Command the first modal to hide
            if (generateModal) {
                generateModal.hide();
            }
        }
    });

    // Handle the "Cancel" button in the confirmation modal to go back
    $('#confirmationModal').on('click', '[data-bs-target="#generateVoucherModal"]', function() {
        const generateModal = new bootstrap.Modal(document.getElementById('generateVoucherModal'));
        const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));

        if (confirmationModal) {
            confirmationModal.hide();
        }

        // A small delay to ensure the backdrop is gone before showing the first modal again
        setTimeout(function() {
            generateModal.show();
        }, 200);
    });
    // --- END: MODAL TRANSITION FIX ---


    // --- Voucher Generation AJAX Call ---
    $('#confirmYes').on('click', function() {
        const $confirmBtn = $(this);
        $confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Generating...');

        const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
        if (confirmationModal) {
            confirmationModal.hide();
        }

        $.ajax({
            url: '{{ route("user.generate.voucher.codes") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: (response) => {
                if (response.success) {
                    const generatedCodeModal = new bootstrap.Modal(document.getElementById('generatedCodeModal'));
                    $('#generatedVoucherCode').val(response.code);
                    generatedCodeModal.show();
                    notify('success', 'Voucher generated successfully!');
                } else {
                    notify('error', response.message || 'An unknown error occurred.');
                }
            },
            error: (xhr) => {
                const message = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                notify('error', message);
            },
            complete: () => {
                $confirmBtn.prop('disabled', false).html('Yes, Generate');
            }
        });
    });

    // --- Utility Functions (Copy, Reload, Image Rotation) ---
    $('#copyVoucherCode').on('click', function() {
        navigator.clipboard.writeText($('#generatedVoucherCode').val()).then(() => {
            const button = $(this);
            const originalHtml = button.html();
            button.html('<i class="fas fa-check"></i> Copied!').addClass('btn-success').removeClass('btn-outline-primary');
            setTimeout(() => {
                button.html(originalHtml).removeClass('btn-success').addClass('btn-outline-primary');
            }, 2000);
        });
    });

    $('#generatedCodeModal').on('hidden.bs.modal', () => location.reload());

    // Your existing image rotation script
    var images = [
        "{{ asset('assets/images/user/rewards/' . $nextRewardImage) }}",
        "{{ asset('assets/images/user/ranks/' . $nextRankImage) }}"
    ];
    var currentIndex = 0;
    function rotateImages() {
        $('.rotating-images img').eq(currentIndex).fadeIn();
        $('.rotating-images img').eq(currentIndex).addClass('small').css('left', '100%');
        currentIndex = (currentIndex + 1) % images.length;
        if (images[currentIndex]) {
            $('.rotating-images img').eq(currentIndex).attr('src', images[currentIndex]).removeClass('small').css('left', 0);
        }
    }
    if (images.length > 1 && images[0] && images[1]) {
        setInterval(rotateImages, 3000);
        rotateImages();
    }
});
</script>
@endpush