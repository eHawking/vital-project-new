@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="row mb-4">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="text-white m-0"><i class="bi bi-trophy-fill"></i> @lang('Ranks & Rewards')</h4>
                <p class="text-white-50 small m-0">@lang('Track your progress and unlock amazing rewards')</p>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4 g-4">
    <!-- Rank Status Card -->
    <div class="col-lg-4 col-md-6">
        <div class="premium-card h-100">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                <h5 class="title text-white m-0"><i class="bi bi-award-fill text-warning"></i> @lang('Rank Status')</h5>
            </div>
            <div class="card-body text-center p-4">
                <div class="mb-4">
                    <p class="text-white-50 mb-2">@lang('Current Rank')</p>
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset('assets/images/user/ranks/' . $currentRankImage) }}" class="rounded-circle mb-2" style="width:80px; height:80px; border: 3px solid #ffd700; box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);" alt="Current Rank">
                        <div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-1" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-lg text-dark" style="font-size: 14px;"></i>
                        </div>
                    </div>
                    <h3 class="text-white mt-2">{{ $currentRankName }}</h3>
                </div>
                <div class="divider my-4" style="height: 1px; background: rgba(255,255,255,0.1);"></div>
                <div class="mt-3">
                    <p class="text-white-50 mb-2">@lang('Next Rank & Reward')</p>
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <img src="{{ asset('assets/images/user/ranks/' . $nextRankImage) }}" class="rounded-circle" style="width:60px; height:60px; border: 2px solid rgba(255,255,255,0.2); opacity: 0.7;" alt="Next Rank">
                        <div class="text-start">
                            <h5 class="text-white mb-1">{{ $nextRankName }}</h5>
                            <span class="badge bg-light text-dark">{{ $nextReward }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rewards Summary Card -->
    <div class="col-lg-4 col-md-6">
        <div class="premium-card h-100">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                <h5 class="title text-white m-0"><i class="bi bi-gift-fill text-danger"></i> @lang('Rewards Summary')</h5>
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                <h1 class="display-3 text-white fw-bold mb-0">{{ $totalRewardsEarned }}</h1>
                <p class="text-white-50">@lang('Total Rewards Earned')</p>
                <div class="row mt-4 g-3">
                    <div class="col-6">
                        <div class="p-3 rounded h-100" style="background: rgba(255,193,7,0.1); border: 1px solid rgba(255,193,7,0.2);">
                            <h3 class="text-white mb-0">{{ $pendingRewards }}</h3>
                            <small class="text-warning"><i class="bi bi-hourglass-split"></i> @lang('Pending')</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded h-100" style="background: rgba(25,135,84,0.1); border: 1px solid rgba(25,135,84,0.2);">
                            <h3 class="text-white mb-0">{{ $deliveredRewards }}</h3>
                            <small class="text-success"><i class="bi bi-check-circle-fill"></i> @lang('Delivered')</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BBS Account Card -->
    <div class="col-lg-4 col-md-6">
        <div class="premium-card h-100">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                <h5 class="title text-white m-0"><i class="bi bi-building text-info"></i> @lang('BBS Account')</h5>
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                @if($bbsUser)
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem; text-shadow: 0 0 20px rgba(25,135,84,0.4);"></i>
                    </div>
                    <p class="text-white-50">@lang('Your Unique BBS ID:')</p>
                    <h2 class="text-white mb-4">{{ $bbsUser->bbs_username ?? 'Username not found' }}</h2>
                    <a href="{{ route('user.bbs.rewards') }}" class="btn btn-info w-100 pulse-animation text-white">
                        <i class="bi bi-eye"></i> @lang('View BBS Rewards')
                    </a>
                @else
                    <div class="mb-3">
                        <i class="bi bi-lock-fill text-white-50" style="font-size: 4rem;"></i>
                    </div>
                    <p class="text-white mt-3">@lang('Achieve the') <strong class="text-warning">@lang('Emperor')</strong> @lang('rank to unlock your Build Business Support (BBS) account and rewards.')</p>             
                    <a href="{{ route('user.bbs.rewards') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-gift-fill"></i> @lang('View BBS Rewards')
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tabs Section -->
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-pills modern-tabs p-2 rounded" id="myTab" role="tablist" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
            <li class="nav-item" role="presentation">
                <button class="nav-link active w-100 text-white" id="all-ranks-tab" data-bs-toggle="tab" data-bs-target="#all-ranks" type="button" role="tab" aria-controls="all-ranks" aria-selected="true" style="border-radius: 10px;">
                    <i class="bi bi-trophy-fill me-2"></i> @lang('All Ranks')
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-white" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false" style="border-radius: 10px;">
                    <i class="bi bi-clock-history me-2"></i> @lang('Rewards History')
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content" id="myTabContent">
    <!-- All Ranks Tab -->
    <div class="tab-pane fade show active" id="all-ranks" role="tabpanel" aria-labelledby="all-ranks-tab">
        <div class="row g-4">
            @foreach ($ranks as $rank)
            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('assets/images/user/ranks/' . $rank->image) }}" class="rounded-circle me-3" style="width:50px; height:50px; border: 2px solid var(--border-card);" alt="Rank Image">
                                    <h4 class="mb-0 text-white">{{ $rank->name }}</h4>
                                </div>
                                <h5 class="text-primary mb-2">
                                    <i class="bi bi-gift"></i> {{ $rank->reward }}
                                </h5>
                                <p class="text-white-50 mb-1">
                                    <i class="bi bi-diagram-3 text-info"></i> @lang('Requirement:') <strong class="text-white">{{ $rank->requirement }}</strong> @lang('Pairs')
                                </p>
                                @if ($rank->budget)
                                <p class="text-white-50 mb-0">
                                    <i class="bi bi-cash-stack text-success"></i> @lang('Budget:') <strong class="text-white">{{ $rank->budget }}</strong>
                                </p>
                                @endif
                            </div>
                            <div class="reward-image bg-white p-2 rounded">
                                <img src="{{ asset('assets/images/user/rewards/' . $rank->reward_image) }}" class="rounded object-fit-contain" style="width:80px; height:80px;" alt="Reward Image">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-3">
                        @php
                            $progress = $rank->requirement > 0 ? min(100, intval(($userPairs / $rank->requirement) * 100)) : 100;
                        @endphp
                        @if ($userPairs < $rank->requirement)
                            <div class="progress mb-2" style="height: 10px; border-radius: 10px; background: rgba(255,255,255,0.1);">
                                <div class="progress-bar" style="width: {{ $progress }}%; background: var(--grad-primary);" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-white-50">{{ $progress }}% @lang('Completed')</span>
                                <span class="text-warning">
                                    <i class="bi bi-arrow-up-circle"></i> @lang('Need') <strong>{{ $rank->requirement - $userPairs }}</strong> @lang('more')
                                </span>
                            </div>
                        @else
                            <div class="progress mb-2" style="height: 10px; border-radius: 10px; background: rgba(255,255,255,0.1);">
                                <div class="progress-bar bg-success" style="width: 100%;" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <p class="text-success small mb-0 text-center fw-bold">
                                <i class="bi bi-check-circle-fill"></i> @lang('Rank Achieved!')
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
        <div class="premium-card mt-4">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                <h5 class="title text-white m-0"><i class="bi bi-list-ul"></i> @lang('My Rewards History')</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table transection-table-2">
                        <thead>
                            <tr>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-trophy"></i> @lang('Rank Won')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-gift"></i> @lang('Reward')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-calendar-event"></i> @lang('Date Earned')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-check-circle"></i> @lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rewardsHistory as $reward)
                            <tr style="background: rgba(255,255,255,0.05);">
                                <td data-label="@lang('Rank Won')" class="text-white">
                                    <strong class="text-warning">{{ $reward->rank->name }}</strong>
                                </td>
                                <td data-label="@lang('Reward')" class="text-white">
                                    {{ $reward->rank->reward }}
                                </td>
                                <td data-label="@lang('Date Earned')" class="text-white">
                                    <small>{{ showDateTime($reward->created_at, 'd M Y') }}</small>
                                </td>
                                <td data-label="@lang('Status')" class="text-white">
                                    @if($reward->status == 'delivered')
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Delivered')</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill"><i class="bi bi-hourglass-split"></i> @lang('Pending')</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-white-50">
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
@endpush
