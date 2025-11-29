@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="dashboard-header mb-4">
                <h2 class="page-title"><i class="bi bi-trophy-fill"></i> @lang('Ranks & Rewards')</h2>
                <p class="page-subtitle">@lang('Track your progress and unlock amazing rewards')</p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Rank Status Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-1 h-100">
                <div class="dashboard-item-header mb-3">
                    <h5 class="title"><i class="bi bi-award-fill"></i> @lang('Rank Status')</h5>
                </div>
                <div class="text-center">
                    <div class="mb-4">
                        <p class="text-white-50 mb-2">@lang('Current Rank')</p>
                        <img src="{{ asset('assets/images/user/ranks/' . $currentRankImage) }}" class="rounded-circle mb-2" style="width:70px; height:70px; border: 3px solid rgba(255,255,255,0.3);" alt="Current Rank">
                        <h3 class="text-white">{{ $currentRankName }}</h3>
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.2);">
                    <div class="mt-3">
                        <p class="text-white-50 mb-2">@lang('Next Rank & Reward')</p>
                        <img src="{{ asset('assets/images/user/ranks/' . $nextRankImage) }}" class="rounded-circle mb-2" style="width:60px; height:60px; border: 2px solid rgba(255,255,255,0.3);" alt="Next Rank">
                        <h5 class="text-white">{{ $nextRankName }}</h5>
                        <span class="badge bg-light text-dark mt-2">{{ $nextReward }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rewards Summary Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-2 h-100">
                <div class="dashboard-item-header mb-3">
                    <h5 class="title"><i class="bi bi-gift-fill"></i> @lang('Rewards Summary')</h5>
                </div>
                <div class="text-center d-flex flex-column justify-content-center">
                    <h1 class="display-3 text-white fw-bold">{{ $totalRewardsEarned }}</h1>
                    <p class="text-white-50">@lang('Total Rewards Earned')</p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="reward-stat">
                                <h3 class="text-white mb-0">{{ $pendingRewards }}</h3>
                                <small class="text-warning"><i class="bi bi-hourglass-split"></i> @lang('Pending')</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="reward-stat">
                                <h3 class="text-white mb-0">{{ $deliveredRewards }}</h3>
                                <small class="text-success"><i class="bi bi-check-circle-fill"></i> @lang('Delivered')</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BBS Account Card -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-3 h-100">
                <div class="dashboard-item-header mb-3">
                    <h5 class="title"><i class="bi bi-building"></i> @lang('BBS Account')</h5>
                </div>
                <div class="text-center d-flex flex-column justify-content-center">
                    @if($bbsUser)
                        <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: rgba(255,255,255,0.9);"></i>
                        <p class="text-white-50 mt-3">@lang('Your Unique BBS ID:')</p>
                        <h2 class="text-white mb-3">{{ $bbsUser->bbs_username ?? 'Username not found' }}</h2>
                        <a href="{{ route('user.bbs.rewards') }}" class="btn btn-light">
                            <i class="bi bi-eye"></i> @lang('View BBS Rewards')
                        </a>
                    @else
                        <i class="bi bi-lock-fill" style="font-size: 4rem; color: rgba(255,255,255,0.6);"></i>
                        <p class="text-white mt-3">@lang('Achieve the') <strong>@lang('Emperor')</strong> @lang('rank to unlock your Build Business Support (BBS) account and rewards.')</p>             
                        <a href="{{ route('user.bbs.rewards') }}" class="btn btn-light mt-2">
                            <i class="bi bi-gift-fill"></i> @lang('View BBS Rewards')
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs modern-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-ranks-tab" data-bs-toggle="tab" data-bs-target="#all-ranks" type="button" role="tab" aria-controls="all-ranks" aria-selected="true">
                        <i class="bi bi-trophy-fill"></i> @lang('All Ranks')
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">
                        <i class="bi bi-clock-history"></i> @lang('Rewards History')
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="myTabContent">
        <!-- All Ranks Tab -->
        <div class="tab-pane fade show active" id="all-ranks" role="tabpanel" aria-labelledby="all-ranks-tab">
            <div class="row mt-4">
                @foreach ($ranks as $rank)
                <div class="col-lg-6 mb-4">
                    <div class="dashboard-item rank-card h-100">
                        <div class="rank-card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ asset('assets/images/user/ranks/' . $rank->image) }}" class="rounded-circle me-3" style="width:50px; height:50px; border: 2px solid var(--accent-blue);" alt="Rank Image">
                                        <h4 class="mb-0">{{ $rank->name }}</h4>
                                    </div>
                                    <h5 class="text-primary mb-2">
                                        <i class="bi bi-gift"></i> {{ $rank->reward }}
                                    </h5>
                                    <p class="text-muted mb-1">
                                        <i class="bi bi-diagram-3"></i> @lang('Requirement:') <strong>{{ $rank->requirement }}</strong> @lang('Pairs')
                                    </p>
                                    @if ($rank->budget)
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-cash-stack"></i> @lang('Budget:') <strong>{{ $rank->budget }}</strong>
                                    </p>
                                    @endif
                                </div>
                                <div class="reward-image">
                                    <img src="{{ asset('assets/images/user/rewards/' . $rank->reward_image) }}" class="rounded" style="width:100px; height:100px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" alt="Reward Image">
                                </div>
                            </div>
                        </div>
                        <div class="rank-card-footer">
                            @php
                                $progress = $rank->requirement > 0 ? min(100, intval(($userPairs / $rank->requirement) * 100)) : 100;
                            @endphp
                            @if ($userPairs < $rank->requirement)
                                <div class="progress mb-2" style="height: 12px; border-radius: 10px;">
                                    <div class="progress-bar" style="width: {{ $progress }}%; background: var(--gradient-purple-blue);" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                        <span class="small">{{ $progress }}%</span>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-arrow-up-circle"></i> @lang('You need') <strong>{{ $rank->requirement - $userPairs }}</strong> @lang('more pairs to earn this reward.')
                                </p>
                            @else
                                <div class="progress mb-2" style="height: 12px; border-radius: 10px;">
                                    <div class="progress-bar bg-success" style="width: 100%;" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
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
        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
            <div class="dashboard-item mt-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title"><i class="bi bi-list-ul"></i> @lang('My Rewards History')</h4>
                </div>
                <div class="table-responsive">
                    <table class="transection-table-2">
                        <thead>
                            <tr>
                                <th><i class="bi bi-trophy"></i> @lang('Rank Won')</th>
                                <th><i class="bi bi-gift"></i> @lang('Reward')</th>
                                <th><i class="bi bi-calendar-event"></i> @lang('Date Earned')</th>
                                <th><i class="bi bi-check-circle"></i> @lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rewardsHistory as $reward)
                            <tr>
                                <td data-label="@lang('Rank Won')">
                                    <strong>{{ $reward->rank->name }}</strong>
                                </td>
                                <td data-label="@lang('Reward')">
                                    {{ $reward->rank->reward }}
                                </td>
                                <td data-label="@lang('Date Earned')">
                                    <small>{{ showDateTime($reward->created_at, 'd M Y') }}</small>
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($reward->status == 'delivered')
                                        <span class="badge badge--success"><i class="bi bi-check-circle-fill"></i> @lang('Delivered')</span>
                                    @else
                                        <span class="badge badge--warning"><i class="bi bi-hourglass-split"></i> @lang('Pending')</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                    @lang('You haven\'t earned any rewards yet.')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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

<style>
/* Ranks & Rewards Page Custom Styles */
.dashboard-header {
    text-align: center;
    padding: 20px 0;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.page-subtitle {
    font-size: 16px;
    color: var(--text-secondary);
    opacity: 0.8;
}

/* Reward Stats */
.reward-stat {
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

/* Modern Tabs */
.modern-tabs {
    border: none;
    background: var(--card-bg);
    padding: 10px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 0;
    display: flex;
    gap: 10px;
}

.modern-tabs .nav-item {
    flex: 1;
}

.modern-tabs .nav-link {
    border: none;
    border-radius: 10px;
    padding: 15px 20px;
    color: var(--text-secondary);
    background: transparent;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modern-tabs .nav-link i {
    font-size: 18px;
}

.modern-tabs .nav-link:hover {
    background: rgba(102, 126, 234, 0.1);
    color: var(--accent-blue);
    transform: translateY(-2px);
}

.modern-tabs .nav-link.active {
    background: var(--gradient-purple-blue);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

/* Rank Cards */
.rank-card {
    transition: all 0.3s ease;
}

.rank-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.rank-card-body {
    padding: 25px;
}

.rank-card-footer {
    padding: 20px 25px;
    background: rgba(0, 0, 0, 0.02);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 0 0 15px 15px;
}

/* Progress Bar Enhancement */
.progress {
    background: rgba(0, 0, 0, 0.1);
}

.progress-bar {
    transition: width 0.6s ease;
}

/* Badge Styling */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.badge--success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(17, 153, 142, 0.3);
}

.badge--warning {
    background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(242, 153, 74, 0.3);
}

/* Table Enhancements */
.transection-table-2 {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

.transection-table-2 thead th {
    background: var(--gradient-purple-blue);
    color: #ffffff;
    padding: 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: none;
}

.transection-table-2 thead th:first-child {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

.transection-table-2 thead th:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

.transection-table-2 thead th i {
    margin-right: 5px;
    opacity: 0.9;
}

.transection-table-2 tbody tr {
    background: var(--card-bg);
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.transection-table-2 tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.transection-table-2 tbody td {
    padding: 15px;
    color: var(--text-primary);
    border: none;
    vertical-align: middle;
}

.transection-table-2 tbody td:first-child {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

.transection-table-2 tbody td:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
    }
    
    .modern-tabs {
        flex-direction: column;
        gap: 5px;
    }
    
    .modern-tabs .nav-link {
        padding: 12px 15px;
    }
    
    .rank-card-body {
        padding: 20px;
    }
    
    .rank-card-footer {
        padding: 15px 20px;
    }
    
    .reward-image img {
        width: 80px !important;
        height: 80px !important;
    }
    
    .transection-table-2 {
        border-spacing: 0;
    }
    
    .transection-table-2 thead {
        display: none;
    }
    
    .transection-table-2 tbody tr {
        display: block;
        margin-bottom: 15px;
    }
    
    .transection-table-2 tbody td {
        display: block;
        text-align: right;
        padding: 10px 15px;
        border-radius: 0 !important;
    }
    
    .transection-table-2 tbody td:first-child {
        border-top-left-radius: 10px !important;
        border-top-right-radius: 10px !important;
    }
    
    .transection-table-2 tbody td:last-child {
        border-bottom-left-radius: 10px !important;
        border-bottom-right-radius: 10px !important;
    }
    
    .transection-table-2 tbody td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: var(--text-secondary);
    }
}
</style>
@endpush
