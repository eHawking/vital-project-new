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

    /* Mobile Full Width Adjustments */
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
        .stats-grid {
            grid-template-columns: 1fr !important;
        }
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Gradient Cards */
    .gradient-card {
        border-radius: 16px;
        padding: 25px;
        position: relative;
        overflow: hidden;
    }
    .gradient-card-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .gradient-card-2 {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .gradient-card-3 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .gradient-card h5,
    .gradient-card h3,
    .gradient-card h2,
    .gradient-card h1,
    .gradient-card p,
    .gradient-card .card-text {
        color: #ffffff !important;
    }
    .gradient-card .card-muted {
        color: rgba(255,255,255,0.7) !important;
    }

    /* Reward Stats */
    .reward-stat {
        padding: 15px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
    }

    /* Modern Tabs */
    .modern-tabs {
        border: none;
        background: #1a1f2e;
        padding: 8px;
        border-radius: 12px;
        margin-bottom: 0;
        display: flex;
        gap: 8px;
    }
    [data-theme="light"] .modern-tabs {
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .modern-tabs .nav-item {
        flex: 1;
    }
    .modern-tabs .nav-link {
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        color: rgba(255,255,255,0.6);
        background: transparent;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    [data-theme="light"] .modern-tabs .nav-link {
        color: #6c757d;
    }
    .modern-tabs .nav-link:hover {
        background: rgba(139, 92, 246, 0.1);
        color: var(--color-primary);
    }
    .modern-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    /* Rank Cards */
    .rank-card {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    [data-theme="light"] .rank-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .rank-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    .rank-card-body {
        padding: 25px;
    }
    .rank-card h4 {
        color: #ffffff;
    }
    [data-theme="light"] .rank-card h4 {
        color: #1a1f2e;
    }
    .rank-card .rank-muted {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .rank-card .rank-muted {
        color: #6c757d;
    }
    .rank-card-footer {
        padding: 20px 25px;
        background: rgba(128,128,128,0.05);
        border-top: 1px solid rgba(128,128,128,0.1);
    }

    /* Progress Bar */
    .progress {
        background: rgba(128,128,128,0.2);
        border-radius: 10px;
    }
    .progress-bar {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-radius: 10px;
    }

    /* Table Custom */
    .table-custom {
        margin-bottom: 0;
    }
    .table-custom th {
        background: rgba(128,128,128,0.05) !important;
        color: rgba(255,255,255,0.6) !important;
        font-weight: 600;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.1) !important;
    }
    [data-theme="light"] .table-custom th {
        color: #6c757d !important;
    }
    .table-custom td {
        color: #ffffff !important;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.05) !important;
        vertical-align: middle;
    }
    [data-theme="light"] .table-custom td {
        color: #1a1f2e !important;
    }
    .table-custom tbody tr:hover {
        background: rgba(128,128,128,0.03) !important;
    }

    /* Mobile Card Item */
    .mobile-card-item {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        color: #ffffff;
    }
    [data-theme="light"] .mobile-card-item {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .mobile-card-item .text-muted-custom {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .mobile-card-item .text-muted-custom {
        color: #6c757d;
    }

    /* Badge Styling */
    .badge--success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
    }
    .badge--warning {
        background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
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
    .premium-card h5 {
        color: #ffffff;
    }
    [data-theme="light"] .premium-card h5 {
        color: #1a1f2e;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-trophy-fill"></i> @lang('Ranks & Rewards')</h4>
        <p class="page-subtitle small m-0">@lang('Track your progress and unlock amazing rewards')</p>
    </div>

    <!-- Summary Cards Grid -->
    <div class="stats-grid">
        <!-- Rank Status Card -->
        <div class="gradient-card gradient-card-1">
            <h6 class="mb-3"><i class="bi bi-award-fill"></i> @lang('Rank Status')</h6>
            <div class="text-center">
                <div class="mb-3">
                    <p class="card-muted mb-2 small">@lang('Current Rank')</p>
                    <img src="{{ asset('assets/images/user/ranks/' . $currentRankImage) }}" class="rounded-circle mb-2" style="width:60px; height:60px; border: 3px solid rgba(255,255,255,0.3);" alt="Current Rank">
                    <h4>{{ $currentRankName }}</h4>
                </div>
                <hr style="border-color: rgba(255,255,255,0.2); margin: 15px 0;">
                <div>
                    <p class="card-muted mb-2 small">@lang('Next Rank')</p>
                    <img src="{{ asset('assets/images/user/ranks/' . $nextRankImage) }}" class="rounded-circle mb-2" style="width:45px; height:45px; border: 2px solid rgba(255,255,255,0.3);" alt="Next Rank">
                    <h6>{{ $nextRankName }}</h6>
                    <span class="badge bg-light text-dark">{{ $nextReward }}</span>
                </div>
            </div>
        </div>

        <!-- Rewards Summary Card -->
        <div class="gradient-card gradient-card-2">
            <h6 class="mb-3"><i class="bi bi-gift-fill"></i> @lang('Rewards Summary')</h6>
            <div class="text-center">
                <h1 class="display-4 fw-bold mb-1">{{ $totalRewardsEarned }}</h1>
                <p class="card-muted mb-3">@lang('Total Rewards Earned')</p>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="reward-stat">
                            <h4 class="mb-0">{{ $pendingRewards }}</h4>
                            <small class="text-warning"><i class="bi bi-hourglass-split"></i> @lang('Pending')</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="reward-stat">
                            <h4 class="mb-0">{{ $deliveredRewards }}</h4>
                            <small style="color: #90EE90;"><i class="bi bi-check-circle-fill"></i> @lang('Delivered')</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BBS Account Card -->
        <div class="gradient-card gradient-card-3">
            <h6 class="mb-3"><i class="bi bi-building"></i> @lang('BBS Account')</h6>
            <div class="text-center">
                @if($bbsUser)
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: rgba(255,255,255,0.9);"></i>
                    <p class="card-muted mt-2 mb-1 small">@lang('Your Unique BBS ID:')</p>
                    <h5 class="mb-3">{{ $bbsUser->bbs_username ?? 'Username not found' }}</h5>
                    <a href="{{ route('user.bbs.rewards') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-eye"></i> @lang('View BBS Rewards')
                    </a>
                @else
                    <i class="bi bi-lock-fill" style="font-size: 3rem; color: rgba(255,255,255,0.6);"></i>
                    <p class="mt-2 small">@lang('Achieve the') <strong>@lang('Emperor')</strong> @lang('rank to unlock BBS account.')</p>
                    <a href="{{ route('user.bbs.rewards') }}" class="btn btn-light btn-sm mt-2">
                        <i class="bi bi-gift-fill"></i> @lang('View BBS Rewards')
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <ul class="nav nav-tabs modern-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-ranks-tab" data-bs-toggle="tab" data-bs-target="#all-ranks" type="button" role="tab">
                <i class="bi bi-trophy-fill"></i> @lang('All Ranks')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                <i class="bi bi-clock-history"></i> @lang('Rewards History')
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="myTabContent">
        <!-- All Ranks Tab -->
        <div class="tab-pane fade show active" id="all-ranks" role="tabpanel">
            <div class="row g-3">
                @foreach ($ranks as $rank)
                <div class="col-lg-6">
                    <div class="rank-card h-100">
                        <div class="rank-card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ asset('assets/images/user/ranks/' . $rank->image) }}" class="rounded-circle me-3" style="width:50px; height:50px; border: 2px solid var(--color-primary);" alt="Rank Image">
                                        <h4 class="mb-0">{{ $rank->name }}</h4>
                                    </div>
                                    <h6 class="text-primary mb-2">
                                        <i class="bi bi-gift"></i> {{ $rank->reward }}
                                    </h6>
                                    <p class="rank-muted mb-1 small">
                                        <i class="bi bi-diagram-3"></i> @lang('Requirement:') <strong>{{ $rank->requirement }}</strong> @lang('Pairs')
                                    </p>
                                    @if ($rank->budget)
                                    <p class="rank-muted mb-0 small">
                                        <i class="bi bi-cash-stack"></i> @lang('Budget:') <strong>{{ $rank->budget }}</strong>
                                    </p>
                                    @endif
                                </div>
                                <div class="reward-image">
                                    <img src="{{ asset('assets/images/user/rewards/' . $rank->reward_image) }}" class="rounded" style="width:80px; height:80px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);" alt="Reward Image">
                                </div>
                            </div>
                        </div>
                        <div class="rank-card-footer">
                            @php
                                $progress = $rank->requirement > 0 ? min(100, intval(($userPairs / $rank->requirement) * 100)) : 100;
                            @endphp
                            @if ($userPairs < $rank->requirement)
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar" style="width: {{ $progress }}%;" role="progressbar">
                                        <span class="small">{{ $progress }}%</span>
                                    </div>
                                </div>
                                <p class="rank-muted small mb-0">
                                    <i class="bi bi-arrow-up-circle"></i> @lang('You need') <strong>{{ $rank->requirement - $userPairs }}</strong> @lang('more pairs.')
                                </p>
                            @else
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: 100%;" role="progressbar">
                                        <span class="small">100%</span>
                                    </div>
                                </div>
                                <p class="text-success small mb-0">
                                    <i class="bi bi-check-circle-fill"></i> @lang('Congratulations! You have achieved this rank!')
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Rewards History Tab -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            <div class="premium-card">
                <h5 class="mb-4"><i class="bi bi-list-ul"></i> @lang('My Rewards History')</h5>

                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>@lang('Rank Won')</th>
                                <th>@lang('Reward')</th>
                                <th>@lang('Date Earned')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rewardsHistory as $reward)
                            <tr>
                                <td><strong>{{ $reward->rank->name }}</strong></td>
                                <td>{{ $reward->rank->reward }}</td>
                                <td>{{ showDateTime($reward->created_at, 'd M Y') }}</td>
                                <td>
                                    @if($reward->status == 'delivered')
                                        <span class="badge--success"><i class="bi bi-check-circle-fill"></i> @lang('Delivered')</span>
                                    @else
                                        <span class="badge--warning"><i class="bi bi-hourglass-split"></i> @lang('Pending')</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                    @lang('You haven\'t earned any rewards yet.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @forelse ($rewardsHistory as $reward)
                        <div class="mobile-card-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $reward->rank->name }}</h6>
                                    <small class="text-muted-custom">{{ $reward->rank->reward }}</small>
                                </div>
                                @if($reward->status == 'delivered')
                                    <span class="badge--success"><i class="bi bi-check-circle-fill"></i> @lang('Delivered')</span>
                                @else
                                    <span class="badge--warning"><i class="bi bi-hourglass-split"></i> @lang('Pending')</span>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted-custom">@lang('Date Earned'):</small>
                                <strong>{{ showDateTime($reward->created_at, 'd M Y') }}</strong>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                            <span class="text-muted-custom">@lang('You haven\'t earned any rewards yet.')</span>
                        </div>
                    @endforelse
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
@endpush
