@extends($activeTemplate . 'layouts.master')
@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Color Picker (Right Sidebar) -->
@include($activeTemplate . 'partials.color-picker')

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

    /* Mobile Full Width Adjustments */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr !important;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .stat-item, .premium-card {
            width: 100% !important;
            margin-bottom: 10px;
        }

        /* Outer container padding */
        .container-fluid.px-4 {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        /* Remove inner container padding to align with header */
        .inner-dashboard-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Reset Row and Column for perfect alignment */
        .user-dashboard .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        .dashboard-main-col {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    
    <!-- KYC ALERTS (Preserved Original Logic) -->
    <div class="row mb-4">
        <div class="col-12">
            @php
                $kyc = getContent('kyc.content', true);
            @endphp
            @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <div class="icon-box variant-pink me-3 mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg></div>
                    <div>
                        <span class="fw-bold">@lang('KYC Documents Rejected')</span><br>
                        <small>
                            {{ __(@$kyc->data_values->reject) }}
                            <a class="fw-bold text-white" data-bs-toggle="modal" data-bs-target="#kycRejectionReason" href="javascript::void(0)">@lang('Click here')</a> @lang('to show reason').
                            <a class="fw-bold text-white" href="{{ route('user.kyc.form') }}">@lang('Re-submit')</a>
                        </small>
                    </div>
                </div>
            @elseif (auth()->user()->kv == Status::KYC_UNVERIFIED)
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <div class="icon-box variant-blue me-3 mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div>
                    <div>
                        <span class="fw-bold">@lang('KYC Verification Required')</span><br>
                        <small>{{ __(@$kyc->data_values->required) }} <a class="fw-bold text-white" href="{{ route('user.kyc.form') }}">@lang('Click here')</a> @lang('to submit').</small>
                    </div>
                </div>
            @elseif(auth()->user()->kv == Status::KYC_PENDING)
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <div class="icon-box variant-orange me-3 mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div>
                        <span class="fw-bold">@lang('KYC Verification Pending')</span><br>
                        <small>{{ __(@$kyc->data_values->pending) }} <a class="fw-bold text-white" href="{{ route('user.kyc.data') }}">@lang('View Data')</a></small>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- MAIN WALLET OVERVIEW -->
    <div class="wallet-overview">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="wallet-balance-title">@lang('Total Balance')</div>
                <div class="wallet-balance-amount">{{ getAmount(auth()->user()->balance) }} <span style="font-size: 1.5rem">PKR</span></div>
                <div class="wallet-balance-sub">≈ {{ getAmount(auth()->user()->balance * 0.35) }} USD</div>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark mb-2">@lang('Main Wallet')</span>
                @if(auth()->user()->plan_id == 1)
                    <br><span class="badge bg-success bg-opacity-75">@lang('Profit Active')</span>
                @endif
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="quick-actions">
            <a href="{{ route('user.deposit.index') }}" class="action-btn-premium">
                <div class="icon-box variant-green mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg></div>
                <span>@lang('Deposit')</span>
            </a>
            <a href="{{ route('user.withdraw') }}" class="action-btn-premium">
                <div class="icon-box variant-orange mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg></div>
                <span>@lang('Withdraw')</span>
            </a>
            <a href="{{ route('user.plan.index') }}" class="action-btn-premium">
                <div class="icon-box variant-purple mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                <span>@lang('Invest')</span>
            </a>
            <a href="{{ route('user.transactions') }}" class="action-btn-premium">
                <div class="icon-box variant-blue mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg></div>
                <span>@lang('History')</span>
            </a>
        </div>
    </div>

    <!-- STATS GRID -->
    <div class="stats-grid">
        <!-- E-Pin Credit -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('E-Pin Credit')</h6>
                <h3>{{ getAmount(auth()->user()->epin_credit) }} PKR</h3>
            </div>
            <div class="icon-box variant-blue mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
            </div>
        </div>

        <!-- DSP Membership -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('DSP Membership')</h6>
                <h3>
                    @if(auth()->user()->plan_id == 1)
                        <span class="text-success">Active</span>
                    @else
                        <span class="text-danger">Inactive</span>
                    @endif
                </h3>
            </div>
            <div class="icon-box {{ auth()->user()->plan_id == 1 ? 'variant-green' : 'variant-orange' }} mb-0">
                @if(auth()->user()->plan_id == 1)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @endif
            </div>
        </div>

        <!-- BV Points -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Bonus Value (BV)')</h6>
                <h3>{{ getAmount(auth()->user()->bv) }}</h3>
            </div>
            <div class="icon-box variant-purple mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
            </div>
        </div>

        <!-- PV Points -->
        <div class="premium-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-info">
                    <h6>@lang('Point Voucher (PV)')</h6>
                    <h3>{{ getAmount(auth()->user()->pv) }}</h3>
                </div>
                <div class="icon-box variant-pink mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                </div>
            </div>
            
            @if (auth()->user()->pv >= 100)
                <button type="button" class="btn btn-primary w-100 btn-sm pulse-animation" data-bs-toggle="modal" data-bs-target="#generateVoucherModal">
                    Generate Voucher
                </button>
            @else
                <div class="d-flex justify-content-between text-muted small mb-1">
                    <span>Progress</span>
                    <span>{{ (auth()->user()->pv / 100) * 100 }}%</span>
                </div>
                <div class="premium-progress">
                    <div class="premium-progress-bar" style="width: {{ (auth()->user()->pv / 100) * 100 }}%;"></div>
                </div>
            @endif
        </div>

        <!-- RANK CARD -->
        <div class="premium-card" style="grid-column: span 1; background: linear-gradient(145deg, rgba(30,41,59,0.8), rgba(15,23,42,0.9));">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="text-muted text-uppercase mb-1">Current Rank</h6>
                    <h3 class="text-primary mb-0">{{ $currentRank }}</h3>
                </div>
                <img src="{{ url('assets/images/user/ranks/' . $currentRankImage) }}" class="rounded-circle" style="width: 60px; height: 60px; border: 2px solid var(--primary);">
            </div>
            
            <div class="d-flex justify-content-between text-muted small mb-1">
                <span>Total Pairs: {{ $pairs }}</span>
                <span>Target: {{ $nextRank }}</span>
            </div>
            @if($progress < 100)
                <div class="premium-progress mb-2">
                    <div class="premium-progress-bar bg-info" style="width: {{ $progress }}%;"></div>
                </div>
                <small class="text-muted">Need {{ $pairsRemaining }} more pairs for next rank.</small>
            @endif
        </div>

        <!-- Bonuses Grid -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('DSP Ref Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->dsp_ref_bonus) }}</h3>
            </div>
            <div class="icon-box variant-green mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Royalty Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->royalty_bonus) }}</h3>
            </div>
            <div class="icon-box variant-purple mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Pair Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->pair_bonus) }}</h3>
            </div>
            <div class="icon-box variant-blue mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Shop Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->shop_bonus) }}</h3>
            </div>
            <div class="icon-box variant-orange mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Total Rewards')</h6>
                <h3>{{ auth()->user()->rewards }}</h3>
            </div>
            <div class="icon-box variant-pink mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Franchise Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->franchise_bonus) }}</h3>
            </div>
            <div class="icon-box variant-purple mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg></div>
        </div>

        <!-- Added Missing Wallets -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('DDS Ref Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->dds_ref_bonus) }}</h3>
            </div>
            <div class="icon-box variant-blue mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Shop Ref Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->shop_reference) }}</h3>
            </div>
            <div class="icon-box variant-orange mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Franchise Ref Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->franchise_ref_bonus) }}</h3>
            </div>
            <div class="icon-box variant-green mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('City Reference')</h6>
                <h3>{{ getAmount(auth()->user()->city_ref_bonus) }}</h3>
            </div>
            <div class="icon-box variant-pink mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Weekly Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->weekly_bonus) }}</h3>
            </div>
            <div class="icon-box variant-purple mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Product Partner Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->product_partner_bonus) }}</h3>
            </div>
            <div class="icon-box variant-blue mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Promo Bonus')</h6>
                <h3>{{ getAmount(auth()->user()->promo) }}</h3>
            </div>
            <div class="icon-box variant-green mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Total DSP Purchase')</h6>
                <h3>{{ getAmount(auth()->user()->total_invest) }}</h3>
            </div>
            <div class="icon-box variant-orange mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Total Deposit')</h6>
                <h3>{{ getAmount($totalDeposit) }}</h3>
            </div>
            <div class="icon-box variant-purple mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Total Withdraw')</h6>
                <h3>{{ getAmount($totalWithdraw) }}</h3>
            </div>
            <div class="icon-box variant-pink mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Completed Withdraw')</h6>
                <h3>{{ getAmount($completeWithdraw) }}</h3>
            </div>
            <div class="icon-box variant-green mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
        </div>

        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6>@lang('Pending Withdraw')</h6>
                <h3>{{ getAmount($pendingWithdraw) }}</h3>
            </div>
            <div class="icon-box variant-orange mb-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
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
            button.html('<i class="las la-check"></i> Copied!').addClass('btn-success').removeClass('btn-outline-primary');
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