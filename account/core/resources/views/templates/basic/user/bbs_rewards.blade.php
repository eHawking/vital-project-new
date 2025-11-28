@extends($activeTemplate . 'layouts.master')

@section('content')

<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="card-title">Build Business Support (BBS) Rewards</h3>
                <p class="text-muted">Achieve milestones by helping your DSP team members reach the Emperor rank to unlock massive cash rewards.</p>
                <hr>
                <h5>Your Progress</h5>
                <p class="lead">You have <span class="fw-bold text-primary display-6">{{ $emperorCount }}</span> Emperor(s) in your DSP team.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @foreach($bbsRanks as $rank)
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <!-- Left Section: Rank Details -->
                <div>
                    <h4 class="card-title d-flex align-items-center">
                        <img src="{{ asset('assets/images/user/ranks/' . $rank->image) }}" 
                             class="rounded-circle me-2" 
                             style="width:50px; height:50px;" 
                             alt="Rank Image">
                        {{ $rank->name }}
                    </h4>
                    <h5 class="text-primary">{{ $rank->reward }}</h5>
                    <h6> 
                        <span class="text-muted">@lang('Requirement: ') {{ $rank->requirement }} Emperors</span>
                    </h6>
                   
                </div>

                <!-- Right Section: Reward Image -->
                <img src="{{ asset('assets/images/user/rewards/' . $rank->reward_image) }}" 
                     class="rounded" 
                     style="width:100px; height:100px;" 
                     alt="Reward Image">
            </div>

            <!-- Progress Bar and Status -->
            <div class="card-footer bg-white">
                @if (trim($rank->name) == 'Emperor ∞')
                    @php
                        // Assuming the requirement for this repeatable reward is 1.
                        $timesEarned = $rank->requirement > 0 ? floor($emperorCount / $rank->requirement) : 0;
                    @endphp
                    <div class="text-center">
                        <i class="fas fa-infinity fa-3x {{ $timesEarned > 0 ? 'text-success' : 'text-muted' }}"></i>
                        @if ($timesEarned > 0)
                            <p class="h5 mt-2">
                                Earned <span class="fw-bold">{{ $timesEarned }}</span> time(s)!
                            </p>
                            <p class="text-muted mt-2">
                                This is a recurring reward. Keep growing your team to earn more!
                            </p>
                        @else
                            <p class="text-muted mt-2">
                                Earn this reward for every new Emperor in your DSP team.
                            </p>
                        @endif
                    </div>
                @else
                    @php
                        $progress = $rank->requirement > 0 ? intval(($emperorCount / $rank->requirement) * 100) : 100;
                    @endphp
                    @if ($emperorCount < $rank->requirement)
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $progress }}%;" 
                                 aria-valuenow="{{ $progress }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <p class="text-muted mt-2">You need {{ $rank->requirement - $emperorCount }} more Emperor(s) to earn this reward.</p>
                    @else
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: 100%;" 
                                 aria-valuenow="100" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <p class="text-success mt-2">Congratulations! You have unlocked this reward!</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
