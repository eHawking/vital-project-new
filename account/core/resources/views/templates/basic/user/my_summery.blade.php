@extends($activeTemplate.'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<!-- Include Theme Switcher -->
@include($activeTemplate . 'partials.theme-switcher')

<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="dashboard-header mb-4">
                <h2 class="page-title"><i class="bi bi-bar-chart-line-fill"></i> @lang('My Summary')</h2>
                <p class="page-subtitle">@lang('Overview of your team and earnings')</p>
            </div>
        </div>
    </div>
</div>

{{-- Tab Navigation --}}
<div class="container">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs modern-tabs" id="mySummaryTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !request()->get('tab') ? 'active' : '' }}" id="own-summary-tab" data-bs-toggle="tab" data-bs-target="#own-summary" type="button" role="tab" aria-controls="own-summary" aria-selected="{{ !request()->get('tab') ? 'true' : 'false' }}">
                        <i class="bi bi-person-circle"></i> @lang('My Own')
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request()->get('tab') == 'referred-users' ? 'active' : '' }}" id="referred-users-tab" data-bs-toggle="tab" data-bs-target="#referred-users" type="button" role="tab" aria-controls="referred-users" aria-selected="{{ request()->get('tab') == 'referred-users' ? 'true' : 'false' }}">
                        <i class="bi bi-people-fill"></i> @lang('My Referred Users')
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Tab Content --}}
<div class="container">
    <div class="tab-content" id="mySummaryTabContent">

    {{-- "My Own" Tab Pane --}}
    <div class="tab-pane fade {{ !request()->get('tab') ? 'show active' : '' }}" id="own-summary" role="tabpanel" aria-labelledby="own-summary-tab">

        {{-- Summary Boxes and User Details Table --}}
        {{-- ... (This part has no changes) ... --}}
        <div class="row justify-content-center mt-4">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Free Users')</h6>
                            <h3 class="ammount theme-one">{{ $totalFreeUsers }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="bi bi-people"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Paid Users')</h6>
                            <h3 class="ammount theme-one">{{ $totalPaidUsers }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="bi bi-person-check-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('My Total DSP')</h6>
                            <h3 class="ammount theme-one">{{ $myTotalDsp }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="bi bi-briefcase-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('My Vendors')</h6>
                            <h3 class="ammount theme-one">{{ $myVendors }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="bi bi-shop"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Earnings')</h6>
                            <h3 class="ammount theme-one">{{ showAmount($totalEarnings, currencyFormat: true) }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="bi bi-cash-stack"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                <div class="dashboard-item">
                    <div class="dashboard-item-header">
                        <div class="header-left">
                            <h6 class="title">@lang('Total Reference Earnings')</h6>
                            <h3 class="ammount theme-one">{{ showAmount($totalReferenceEarnings, currencyFormat: true) }}</h3>
                        </div>
                        <div class="right-content">
                            <div class="icon"><i class="bi bi-person-plus-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-item mb-4">
            <div class="dashboard-item-header mb-3">
                <h4 class="title"><i class="bi bi-person-vcard"></i> @lang('My Details')</h4>
            </div>
            <div class="table-responsive">
                <table class="table transection-table-2">
                <thead>
                    <tr>
                        <th scope="col">@lang('Username')</th>
                        <th scope="col">@lang('Mr. You')</th>
                        <th scope="col">@lang('DSP')</th>
                        <th scope="col">@lang('Under User')</th>
                        <th scope="col">@lang('City')</th>
                        <th scope="col">@lang('Position')</th>
                        <th scope="col">@lang('Rank')</th>
                        <th scope="col">@lang('Level')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="@lang('Username')">{{ $username }}</td>
                        <td data-label="@lang('Mr. You')">{{ $name }}</td>
                        <td data-label="@lang('DSP')">
                            @if($user->plan_id == 1)
                                <span class="badge badge--success">@lang('Active')</span>
                            @else
                                <span class="badge badge--danger">@lang('Inactive')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Under User')">{{ $underUser }}</td>
                        <td data-label="@lang('City')">{{ $city }}</td>
                        <td data-label="@lang('Position')">{{ $position }}</td>
                        <td data-label="@lang('Rank')">{{ $rank }}</td>
                        <td data-label="@lang('Level')">{{ $level }}</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>

        {{-- My DSP Users List --}}
        <div class="dashboard-item mb-4">
            <div class="dashboard-item-header mb-3">
                <h4 class="title"><i class="bi bi-diagram-3-fill"></i> @lang('My DSP Users')</h4>
            </div>
            <div class="table-responsive">
                <table class="table transection-table-2">
                    <thead>
                        <tr>
                            {{-- Updated Header --}}
                            <th scope="col">@lang('S No.')</th>
                            <th scope="col">@lang('Username')</th>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Under User')</th>
                            <th scope="col">@lang('City')</th>
                            <th scope="col">@lang('Position')</th>
                            <th scope="col">@lang('Rank')</th>
                            <th scope="col">@lang('Level')</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Logic for descending serial number --}}
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
                            <tr>
                                {{-- Updated Data Cell --}}
                                <td data-label="@lang('S No.')">{{ $startNumber-- }}</td>
                                <td data-label="@lang('Username')">{{ $dspUser->username }}</td>
                                <td data-label="@lang('Name')">{{ $dspUser->refBy->fullname ?? 'N/A' }}</td>
                                <td data-label="@lang('Under User')">{{ $positionalUser->username ?? 'N/A' }}</td>
                                <td data-label="@lang('City')">{{ $dspUser->city ?? 'N/A' }}</td>
                                <td data-label="@lang('Position')">{{ $positionName }}</td>
                                <td data-label="@lang('Rank')">{{ $userRank }}</td>
                                <td data-label="@lang('Level')">{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="8">@lang('No DSP users found.')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $dspUsers->links() }}
            </div>
        </div>

    </div>

    {{-- "My Referred Users" Tab Pane --}}
    <div class="tab-pane fade {{ request()->get('tab') == 'referred-users' ? 'show active' : '' }}" id="referred-users" role="tabpanel" aria-labelledby="referred-users-tab">
        <div class="dashboard-item mb-4 mt-4">
            <div class="dashboard-item-header mb-3">
                <h4 class="title"><i class="bi bi-people-fill"></i> @lang('My Referred Users')</h4>
            </div>
            <div class="table-responsive">
                <table class="table transection-table-2">
                    <thead>
                        <tr>
                            {{-- Updated Header --}}
                            <th scope="col">@lang('S No.')</th>
                            <th scope="col">@lang('Username')</th>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Under User')</th>
                            <th scope="col">@lang('City')</th>
                            <th scope="col">@lang('Position')</th>
                            <th scope="col">@lang('Rank')</th>
                            <th scope="col">@lang('Level')</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Logic for descending serial number --}}
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
                            <tr>
                                {{-- Updated Data Cell --}}
                                <td data-label="@lang('S No.')">{{ $startNumber-- }}</td>
                                <td data-label="@lang('Username')">{{ $referredUser->username }}</td>
                                <td data-label="@lang('Name')">{{ $referredUser->fullname }}</td>
                                <td data-label="@lang('Under User')">{{ $positionalUser->username ?? 'N/A' }}</td>
                                <td data-label="@lang('City')">{{ $referredUser->city ?? 'N/A' }}</td>
                                <td data-label="@lang('Position')">{{ $positionName }}</td>
                                <td data-label="@lang('Rank')">{{ $userRank }}</td>
                                <td data-label="@lang('Level')">{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="8">@lang('No referred users found.')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $referredUsers->appends(['tab' => 'referred-users'])->links() }}
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
/* My Summary Page Custom Styles */
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

/* Modern Tabs Styling */
.modern-tabs {
    border: none;
    background: var(--card-bg);
    padding: 10px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
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

.tab-content {
    margin-top: 20px;
}

/* Badge Styling */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge--success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(17, 153, 142, 0.3);
}

.badge--danger {
    background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(238, 9, 121, 0.3);
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
    font-weight: 700;
    color: var(--accent-blue);
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
        font-size: 14px;
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
        border-radius: 10px;
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