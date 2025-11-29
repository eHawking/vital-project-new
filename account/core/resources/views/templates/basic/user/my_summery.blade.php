@extends($activeTemplate.'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="row mb-4">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="text-white m-0"><i class="bi bi-bar-chart-line-fill"></i> @lang('My Summary')</h4>
                <p class="text-white-50 small m-0">@lang('Overview of your team and earnings')</p>
            </div>
        </div>
    </div>
</div>

{{-- Tab Navigation --}}
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-pills modern-tabs p-2 rounded" id="mySummaryTab" role="tablist" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ !request()->get('tab') ? 'active' : '' }} text-white w-100" id="own-summary-tab" data-bs-toggle="tab" data-bs-target="#own-summary" type="button" role="tab" aria-controls="own-summary" aria-selected="{{ !request()->get('tab') ? 'true' : 'false' }}" style="border-radius: 10px;">
                    <i class="bi bi-person-circle me-2"></i> @lang('My Own')
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request()->get('tab') == 'referred-users' ? 'active' : '' }} text-white w-100" id="referred-users-tab" data-bs-toggle="tab" data-bs-target="#referred-users" type="button" role="tab" aria-controls="referred-users" aria-selected="{{ request()->get('tab') == 'referred-users' ? 'true' : 'false' }}" style="border-radius: 10px;">
                    <i class="bi bi-people-fill me-2"></i> @lang('My Referred Users')
                </button>
            </li>
        </ul>
    </div>
</div>

{{-- Tab Content --}}
<div class="tab-content" id="mySummaryTabContent">

    {{-- "My Own" Tab Pane --}}
    <div class="tab-pane fade {{ !request()->get('tab') ? 'show active' : '' }}" id="own-summary" role="tabpanel" aria-labelledby="own-summary-tab">

        {{-- Summary Boxes --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="premium-card stat-item h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">@lang('Total Free Users')</h6>
                            <h3 class="text-white m-0">{{ $totalFreeUsers }}</h3>
                        </div>
                        <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="premium-card stat-item h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">@lang('Total Paid Users')</h6>
                            <h3 class="text-white m-0">{{ $totalPaidUsers }}</h3>
                        </div>
                        <div class="icon-box variant-green" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-person-check-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="premium-card stat-item h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">@lang('My Total DSP')</h6>
                            <h3 class="text-white m-0">{{ $myTotalDsp }}</h3>
                        </div>
                        <div class="icon-box variant-purple" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-briefcase-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="premium-card stat-item h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">@lang('My Vendors')</h6>
                            <h3 class="text-white m-0">{{ $myVendors }}</h3>
                        </div>
                        <div class="icon-box variant-orange" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-shop fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="premium-card stat-item h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">@lang('Total Earnings')</h6>
                            <h3 class="text-white m-0">{{ showAmount($totalEarnings, currencyFormat: true) }}</h3>
                        </div>
                        <div class="icon-box variant-pink" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-cash-stack fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="premium-card stat-item h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">@lang('Total Reference Earnings')</h6>
                            <h3 class="text-white m-0">{{ showAmount($totalReferenceEarnings, currencyFormat: true) }}</h3>
                        </div>
                        <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i class="bi bi-person-plus-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- My Details Table --}}
        <div class="premium-card mb-4">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                <h5 class="title text-white m-0"><i class="bi bi-person-vcard"></i> @lang('My Details')</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table transection-table-2">
                        <thead>
                            <tr>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Username')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Mr. You')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('DSP')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Under User')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('City')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Position')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Rank')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Level')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: rgba(255,255,255,0.05);">
                                <td data-label="@lang('Username')" class="text-white">{{ $username }}</td>
                                <td data-label="@lang('Mr. You')" class="text-white">{{ $name }}</td>
                                <td data-label="@lang('DSP')" class="text-white">
                                    @if($user->plan_id == 1)
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill">@lang('Active')</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Under User')" class="text-white">{{ $underUser }}</td>
                                <td data-label="@lang('City')" class="text-white">{{ $city }}</td>
                                <td data-label="@lang('Position')" class="text-white">{{ $position }}</td>
                                <td data-label="@lang('Rank')" class="text-white">{{ $rank }}</td>
                                <td data-label="@lang('Level')" class="text-white">{{ $level }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- My DSP Users List --}}
        <div class="premium-card mb-4">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                <h5 class="title text-white m-0"><i class="bi bi-diagram-3-fill"></i> @lang('My DSP Users')</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table transection-table-2">
                        <thead>
                            <tr>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('S No.')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Username')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Name')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Under User')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('City')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Position')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Rank')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Level')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $startNumber = $dspUsers->total() - ($dspUsers->currentPage() - 1) * $dspUsers->perPage();
                            @endphp
                            @forelse($dspUsers as $dspUser)
                                @php
                                    $posId = getPositionId($dspUser->id);
                                    $positionalUser = $posId ? \App\Models\User::find($posId) : null;
                                    $positionName = mlmPositions()[getPositionLocation($dspUser->id)] ?? 'N/A';
                                    $userRank = 'User';
                                    foreach ($ranks as $rank) {
                                        if ($dspUser->pairs >= $rank->requirement) {
                                            $userRank = $rank->name;
                                        }
                                    }
                                    $perfectLevel = getUserLevel($user->id, $dspUser->id);
                                @endphp
                                <tr style="background: rgba(255,255,255,0.05);">
                                    <td data-label="@lang('S No.')" class="text-white">{{ $startNumber-- }}</td>
                                    <td data-label="@lang('Username')" class="text-white">{{ $dspUser->username }}</td>
                                    <td data-label="@lang('Name')" class="text-white">{{ $dspUser->refBy->fullname ?? 'N/A' }}</td>
                                    <td data-label="@lang('Under User')" class="text-white">{{ $positionalUser->username ?? 'N/A' }}</td>
                                    <td data-label="@lang('City')" class="text-white">{{ $dspUser->city ?? 'N/A' }}</td>
                                    <td data-label="@lang('Position')" class="text-white">{{ $positionName }}</td>
                                    <td data-label="@lang('Rank')" class="text-white">{{ $userRank }}</td>
                                    <td data-label="@lang('Level')" class="text-white">{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-white-50" colspan="8">@lang('No DSP users found.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($dspUsers->hasPages())
                <div class="p-3">
                    {{ $dspUsers->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- "My Referred Users" Tab Pane --}}
    <div class="tab-pane fade {{ request()->get('tab') == 'referred-users' ? 'show active' : '' }}" id="referred-users" role="tabpanel" aria-labelledby="referred-users-tab">
        <div class="premium-card mb-4">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                <h5 class="title text-white m-0"><i class="bi bi-people-fill"></i> @lang('My Referred Users')</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table transection-table-2">
                        <thead>
                            <tr>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('S No.')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Username')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Name')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Under User')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('City')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Position')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Rank')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Level')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $startNumber = $referredUsers->total() - ($referredUsers->currentPage() - 1) * $referredUsers->perPage();
                            @endphp
                            @forelse($referredUsers as $referredUser)
                                @php
                                    $posId = getPositionId($referredUser->id);
                                    $positionalUser = $posId ? \App\Models\User::find($posId) : null;
                                    $positionName = mlmPositions()[getPositionLocation($referredUser->id)] ?? 'N/A';
                                    $userRank = 'User';
                                    foreach ($ranks as $rank) {
                                        if ($referredUser->pairs >= $rank->requirement) {
                                            $userRank = $rank->name;
                                        }
                                    }
                                    $perfectLevel = getUserLevel($user->id, $referredUser->id);
                                @endphp
                                <tr style="background: rgba(255,255,255,0.05);">
                                    <td data-label="@lang('S No.')" class="text-white">{{ $startNumber-- }}</td>
                                    <td data-label="@lang('Username')" class="text-white">{{ $referredUser->username }}</td>
                                    <td data-label="@lang('Name')" class="text-white">{{ $referredUser->fullname }}</td>
                                    <td data-label="@lang('Under User')" class="text-white">{{ $positionalUser->username ?? 'N/A' }}</td>
                                    <td data-label="@lang('City')" class="text-white">{{ $referredUser->city ?? 'N/A' }}</td>
                                    <td data-label="@lang('Position')" class="text-white">{{ $positionName }}</td>
                                    <td data-label="@lang('Rank')" class="text-white">{{ $userRank }}</td>
                                    <td data-label="@lang('Level')" class="text-white">{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-white-50" colspan="8">@lang('No referred users found.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($referredUsers->hasPages())
                <div class="p-3">
                    {{ $referredUsers->appends(['tab' => 'referred-users'])->links() }}
                </div>
                @endif
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
<script>
    $(document).ready(function() {
        // Preserve tab state
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('lastTab', $(e.target).attr('id'));
        });
        
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('#' + lastTab).tab('show');
        }
    });
</script>
@endpush