@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="premium-card text-center">
            <div class="card-body p-4">
                <h3 class="card-title text-white mb-2"><i class="bi bi-building text-info"></i> Build Business Support (BBS) Rewards</h3>
                <p class="text-white-50">Achieve milestones by helping your DSP team members reach the Emperor rank to unlock massive cash rewards.</p>
                <div class="divider my-4" style="height: 1px; background: rgba(255,255,255,0.1);"></div>
                <h5 class="text-white mb-3">Your Progress</h5>
                <p class="lead text-white-50">You have <span class="fw-bold text-info display-6">{{ $emperorCount }}</span> Emperor(s) in your DSP team.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @foreach($bbsRanks as $rank)
    <div class="col-lg-6 col-md-12">
        <div class="premium-card h-100 d-flex flex-column">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <!-- Left Section: Rank Details -->
                <div class="flex-grow-1">
                    <h4 class="card-title d-flex align-items-center text-white mb-3">
                        <img src="{{ asset('assets/images/user/ranks/' . $rank->image) }}" 
                             class="rounded-circle me-3 border border-2 border-info" 
                             style="width:50px; height:50px;" 
                             alt="Rank Image">
                        {{ $rank->name }}
                    </h4>
                    <h5 class="text-warning mb-2">{{ $rank->reward }}</h5>
                    <h6 class="text-white-50"> 
                        @lang('Requirement: ') <strong class="text-white">{{ $rank->requirement }}</strong> Emperors
                    </h6>
                </div>

                <!-- Right Section: Reward Image -->
                <div class="ms-3">
                    <img src="{{ asset('assets/images/user/rewards/' . $rank->reward_image) }}" 
                         class="rounded object-fit-contain bg-white p-1" 
                         style="width:100px; height:100px;" 
                         alt="Reward Image">
                </div>
            </div>

            <!-- Progress Bar and Status -->
            <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-4">
                @if (trim($rank->name) == 'Emperor ∞')
                    @php
                        // Assuming the requirement for this repeatable reward is 1.
                        $timesEarned = $rank->requirement > 0 ? floor($emperorCount / $rank->requirement) : 0;
                    @endphp
                    <div class="text-center">
                        <i class="fas fa-infinity fa-3x {{ $timesEarned > 0 ? 'text-success' : 'text-white-50' }} mb-2"></i>
                        @if ($timesEarned > 0)
                            <p class="h5 text-white mt-2">
                                Earned <span class="fw-bold text-success">{{ $timesEarned }}</span> time(s)!
                            </p>
                            <p class="text-white-50 mt-2 small">
                                This is a recurring reward. Keep growing your team to earn more!
                            </p>
                        @else
                            <p class="text-white-50 mt-2 small">
                                Earn this reward for every new Emperor in your DSP team.
                            </p>
                        @endif
                    </div>
                @else
                    @php
                        $progress = $rank->requirement > 0 ? intval(($emperorCount / $rank->requirement) * 100) : 100;
                    @endphp
                    @if ($emperorCount < $rank->requirement)
                        <div class="progress mb-2" style="height: 10px; border-radius: 10px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $progress }}%; background: var(--grad-primary);" 
                                 aria-valuenow="{{ $progress }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <p class="text-white-50 mt-2 small mb-0">You need <strong class="text-warning">{{ $rank->requirement - $emperorCount }}</strong> more Emperor(s) to earn this reward.</p>
                    @else
                        <div class="progress mb-2" style="height: 10px; border-radius: 10px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: 100%;" 
                                 aria-valuenow="100" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <p class="text-success mt-2 small mb-0 fw-bold"><i class="bi bi-check-circle-fill"></i> Congratulations! You have unlocked this reward!</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
