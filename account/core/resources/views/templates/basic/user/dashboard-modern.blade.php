@extends($activeTemplate . 'layouts.master')
@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Theme Switcher -->
@include($activeTemplate . 'partials.theme-switcher')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- KYC Alerts -->
            @php
                $kyc = getContent('kyc.content', true);
            @endphp
            @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
                <div class="alert alert--danger" role="alert" style="animation: slideInRight 0.5s ease;">
                    <div class="alert__icon"><i class="las la-money-bill" style="z-index: 10;"></i></div>
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
                <div class="alert alert--info" role="alert" style="animation: slideInRight 0.5s ease;">
                    <div class="alert__icon"><i class="las la-money-bill" style="z-index: 10;"></i></div>
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
                <div class="alert alert--warning" role="alert" style="animation: slideInRight 0.5s ease;">
                    <div class="alert__icon"><i class="las la-user-check" style="z-index: 10;"></i></div>
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

            <!-- TOP WALLET CARD - Mobile/Desktop -->
            <div class="wallet-card">
                <div class="wallet-header">
                    <div>
                        <div class="wallet-title">@lang('All Wallets in USD')</div>
                        <div class="wallet-balance">{{ getAmount(auth()->user()->balance) }} PKR</div>
                        <div class="wallet-sub-balance">{{ getAmount(auth()->user()->balance * 0.35) }} USD</div>
                    </div>
                    <div>
                        <div style="text-align: right;">
                            <span class="badge-success">@lang('Main Wallet')</span>
                            @if(auth()->user()->plan_id == 1)
                                <br><span class="badge-success mt-2" style="display: inline-block;">@lang('Profit Wallet')</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="action-buttons">
                    <a href="{{ route('user.deposit.index') }}" class="action-btn">
                        <i class="las la-download" style="z-index: 10;"></i>
                        <span>@lang('Deposit')</span>
                    </a>
                    <a href="{{ route('user.plan.index') }}" class="action-btn">
                        <i class="las la-box" style="z-index: 10;"></i>
                        <span>@lang('Investment')</span>
                    </a>
                    <a href="{{ route('user.withdraw') }}" class="action-btn">
                        <i class="las la-building" style="z-index: 10;"></i>
                        <span>@lang('Withdraw')</span>
                    </a>
                </div>
            </div>

            <!-- ALL NAVIGATIONS SECTION -->
            <div class="stats-section">
                <div class="stats-header">
                    <i class="las la-th" style="z-index: 10;"></i>
                    @lang('ALL NAVIGATIONS')
                </div>
                <div class="nav-grid">
                    <a href="{{ route('user.plan.index') }}" class="nav-item">
                        <i class="las la-columns" style="z-index: 10;"></i>
                        <span>@lang('Schemas')</span>
                    </a>
                    <a href="{{ route('user.plan.index') }}" class="nav-item">
                        <i class="las la-chart-line" style="z-index: 10;"></i>
                        <span>@lang('Investment')</span>
                    </a>
                    <a href="{{ route('user.transactions') }}" class="nav-item">
                        <i class="las la-exchange-alt" style="z-index: 10;"></i>
                        <span>@lang('Transactions')</span>
                    </a>
                    <a href="{{ route('user.deposit.index') }}" class="nav-item">
                        <i class="las la-download" style="z-index: 10;"></i>
                        <span>@lang('Deposit')</span>
                    </a>
                    <a href="{{ route('user.deposit.history') }}" class="nav-item">
                        <i class="las la-history" style="z-index: 10;"></i>
                        <span>@lang('Deposit Log')</span>
                    </a>
                    <a href="{{ route('user.withdraw.history') }}" class="nav-item">
                        <i class="las la-wallet" style="z-index: 10;"></i>
                        <span>@lang('Wallet Exch.')</span>
                    </a>
                </div>
                <div class="text-center mt-3">
                    <button class="load-more-btn" onclick="toggleMoreNav()">@lang('LOAD MORE')</button>
                </div>
            </div>

            <!-- ALL STATISTICS SECTION -->
            <div class="stats-section">
                <div class="stats-header">
                    <i class="las la-chart-bar" style="z-index: 10;"></i>
                    @lang('ALL STATISTIC')
                </div>
                
                <div class="stat-card variant-1">
                    <div class="stat-icon">
                        <i class="las la-exchange-alt" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('All Transactions')</div>
                        <div class="stat-value">1</div>
                    </div>
                </div>

                <div class="stat-card variant-2">
                    <div class="stat-icon">
                        <i class="las la-download" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Total Deposit')</div>
                        <div class="stat-value">{{ getAmount($totalDeposit) }}</div>
                    </div>
                </div>

                <div class="stat-card variant-3">
                    <div class="stat-icon">
                        <i class="las la-chart-line" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Total Investment')</div>
                        <div class="stat-value">{{ getAmount(auth()->user()->total_invest) }}</div>
                    </div>
                </div>

                <div class="stat-card variant-4">
                    <div class="stat-icon">
                        <i class="las la-building" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Total Withdraw')</div>
                        <div class="stat-value">{{ getAmount($totalWithdraw) }}</div>
                    </div>
                </div>

                <div class="stat-card variant-5">
                    <div class="stat-icon">
                        <i class="las la-star" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Total Profit')</div>
                        <div class="stat-value">{{ getAmount(auth()->user()->balance) }}</div>
                    </div>
                </div>

                <div class="stat-card variant-6">
                    <div class="stat-icon">
                        <i class="las la-trophy" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Total Referral Bonus')</div>
                        <div class="stat-value">{{ getAmount(auth()->user()->dsp_ref_bonus) }}</div>
                    </div>
                </div>

                <div class="stat-card variant-1">
                    <div class="stat-icon">
                        <i class="las la-users" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Referral Bonus')</div>
                        <div class="stat-value">{{ getAmount(auth()->user()->dds_ref_bonus) }}</div>
                    </div>
                </div>

                <div class="stat-card variant-2">
                    <div class="stat-icon">
                        <i class="las la-trophy" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Rank Achieved')</div>
                        <div class="stat-value">{{ $currentRank ?? 'Bronze' }}</div>
                    </div>
                </div>

                <div class="stat-card variant-3">
                    <div class="stat-icon">
                        <i class="las la-gift" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Total Referral')</div>
                        <div class="stat-value">0</div>
                    </div>
                </div>

                <div class="stat-card variant-4">
                    <div class="stat-icon">
                        <i class="las la-ticket-alt" style="z-index: 10;"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">@lang('Total Ticket')</div>
                        <div class="stat-value">0</div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button class="load-more-btn">@lang('LOAD MORE')</button>
                </div>
            </div>

            <!-- DESKTOP GRID VIEW - All Stats Cards -->
            <div class="row justify-content-center g-3 d-none d-md-flex" style="margin-top: 30px;">
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-1">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Current Balance')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->balance) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-wallet" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-2">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('E-Pin Credit')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->epin_credit) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-credit-card" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-3">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Total DSP Ref Bonus')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->dsp_ref_bonus) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-clipboard-check" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-4">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Total Royalty Bonus')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->royalty_bonus) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-star" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-5">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Total Pair Bonus')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->pair_bonus) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-briefcase" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-6">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Total DDS Ref Bonus')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->dds_ref_bonus) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-cart-plus" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-1">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Total Shop Bonus')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->shop_bonus) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-shopping-bag" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-2">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Total Franchise Bonus')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->franchise_bonus) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-briefcase" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                    <div class="dashboard-item gradient-card-3">
                        <div class="dashboard-item-header">
                            <div class="header-left">
                                <h6 class="title">@lang('Total Weekly Bonus')</h6>
                                <h3 class="ammount">{{ getAmount(auth()->user()->weekly_bonus) }} PKR</h3>
                            </div>
                            <div class="right-content">
                                <div class="icon"><i class="las la-calendar-check" style="z-index: 10;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT TRANSACTIONS SECTION -->
            <div class="transactions-section" style="margin-top: 30px;">
                <div class="stats-header">
                    <i class="las la-history" style="z-index: 10;"></i>
                    @lang('RECENT TRANSACTIONS')
                </div>
                
                <div class="transaction-item">
                    <div class="transaction-left">
                        <div class="transaction-icon">
                            <i class="las la-gift" style="z-index: 10;"></i>
                        </div>
                        <div class="transaction-details">
                            <h6>@lang('Signup Bonus')</h6>
                            <p>TRXDRMGP6GD0F</p>
                            <p class="text-muted" style="font-size: 11px;">Sep 28 2025 10:48</p>
                        </div>
                    </div>
                    <div class="transaction-right">
                        <div class="transaction-amount">+3 USD</div>
                        <span class="badge-success">@lang('Success')</span>
                    </div>
                </div>
            </div>

            <!-- REFERRAL URL SECTION -->
            <div class="referral-url-section">
                <h6>@lang('REFERRAL URL')</h6>
                <div class="url-input-group">
                    <input type="text" readonly value="https://hyiprio.tdevs.co/register?invite=Z24mMILqJ" id="refUrlInput">
                    <button class="copy-btn" onclick="copyReferralUrl()">
                        <i class="las la-clipboard" style="z-index: 10;"></i> @lang('Copy')
                    </button>
                </div>
                <p class="text-muted" style="font-size: 12px; margin-top: 8px;">@lang('0 peoples are joined by using this URL')</p>
            </div>

        </div>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<div class="bottom-nav d-md-none">
    <div class="bottom-nav-items">
        <a href="{{ route('user.home') }}" class="bottom-nav-item active">
            <i class="las la-home" style="z-index: 10;"></i>
            <span>@lang('Home')</span>
        </a>
        <a href="{{ route('user.transactions') }}" class="bottom-nav-item">
            <i class="las la-exchange-alt" style="z-index: 10;"></i>
            <span>@lang('Activity')</span>
        </a>
        <a href="{{ route('user.my.ref') }}" class="bottom-nav-item">
            <i class="las la-users" style="z-index: 10;"></i>
            <span>@lang('Referral')</span>
        </a>
        <a href="{{ route('user.notifications') }}" class="bottom-nav-item">
            <i class="las la-bell" style="z-index: 10;"></i>
            <span>@lang('Alerts')</span>
        </a>
        <a href="{{ route('user.profile.setting') }}" class="bottom-nav-item">
            <i class="las la-user" style="z-index: 10;"></i>
            <span>@lang('Profile')</span>
        </a>
    </div>
</div>

@endsection

@push('script')
<script>
function copyReferralUrl() {
    const input = document.getElementById('refUrlInput');
    input.select();
    document.execCommand('copy');
    
    const btn = event.target.closest('.copy-btn');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="las la-check" style="z-index: 10;"></i> Copied!';
    btn.style.background = 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)';
    
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.style.background = '';
    }, 2000);
}

function toggleMoreNav() {
    // Toggle visibility of additional navigation items
    alert('Load more navigation items');
}

// Add scroll animation
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.dashboard-item, .stat-card, .transaction-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.5s ease';
        observer.observe(el);
    });
});
</script>
@endpush
