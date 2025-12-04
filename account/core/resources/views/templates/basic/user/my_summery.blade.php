@extends($activeTemplate.'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
    /* Mobile Full Width Adjustments */
    @media (max-width: 768px) {
        .inner-dashboard-container,
        .container-fluid.px-4 {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            width: 100% !important;
            margin: 0 !important;
        }
        .stats-grid {
            grid-template-columns: 1fr !important;
            display: flex !important;
            flex-direction: column;
            gap: 15px;
            width: 100% !important;
            margin-bottom: 20px !important;
        }
        .stat-item, .premium-card {
            width: 100% !important;
            margin-bottom: 10px;
            border-radius: 12px !important;
        }
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .nav-pills {
            flex-direction: column;
            gap: 5px;
        }
        .nav-pills .nav-item {
            width: 100%;
        }
    }

    /* Theme-aware text colors */
    h1, h2, h3, h4, h5, h6, .premium-title {
        color: var(--text-primary) !important;
    }
    .table-custom {
        color: var(--text-primary) !important;
    }
    .table-custom th, .table-custom td {
        background: transparent !important;
        vertical-align: middle;
        padding: 15px;
        color: inherit !important;
        white-space: nowrap;
    }
    .table-custom tbody tr {
        transition: background 0.2s ease;
    }
    .table-custom tbody tr:hover {
        background: rgba(128,128,128,0.05) !important;
    }

    /* Tab styling */
    .premium-tabs {
        background: var(--bg-card) !important;
        border: 1px solid rgba(128,128,128,0.1) !important;
        padding: 8px;
        border-radius: 12px;
    }
    .premium-tabs .nav-link {
        color: var(--text-muted) !important;
        border-radius: 8px !important;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }
    .premium-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%) !important;
        color: #fff !important;
    }
    .premium-tabs .nav-link:hover:not(.active) {
        background: rgba(128,128,128,0.1);
    }

    /* Mobile Card Item */
    .mobile-card-item {
        background: var(--bg-card);
        border: 1px solid rgba(128,128,128,0.1);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
    }
    
    /* Premium Pagination */
    .premium-pagination .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 8px;
        background: rgba(128,128,128,0.1);
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .premium-pagination .page-btn:hover:not(.disabled):not(.active) {
        background: rgba(var(--rgb-primary), 0.2);
        color: var(--color-primary);
    }
    .premium-pagination .page-btn.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        color: #fff;
        box-shadow: 0 4px 10px rgba(var(--rgb-primary), 0.3);
    }
    .premium-pagination .page-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    .premium-pagination .page-dots {
        color: var(--text-muted);
        padding: 0 5px;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">

    {{-- Tab Navigation --}}
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills premium-tabs d-flex flex-row" id="mySummaryTab" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link {{ !request()->get('tab') ? 'active' : '' }} w-100" id="own-summary-tab" data-bs-toggle="tab" data-bs-target="#own-summary" type="button" role="tab">
                        <i class="bi bi-person-circle me-2"></i> @lang('My Own')
                    </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link {{ request()->get('tab') == 'referred-users' ? 'active' : '' }} w-100" id="referred-users-tab" data-bs-toggle="tab" data-bs-target="#referred-users" type="button" role="tab">
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
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Total Free Users')</h6>
                    <h3>{{ $totalFreeUsers }}</h3>
                </div>
                <div class="icon-box variant-blue mb-0">
                    <i class="bi bi-people fs-3"></i>
                </div>
            </div>
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Total Paid Users')</h6>
                    <h3>{{ $totalPaidUsers }}</h3>
                </div>
                <div class="icon-box variant-green mb-0">
                    <i class="bi bi-person-check-fill fs-3"></i>
                </div>
            </div>
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('My Total DSP')</h6>
                    <h3>{{ $myTotalDsp }}</h3>
                </div>
                <div class="icon-box variant-purple mb-0">
                    <i class="bi bi-briefcase-fill fs-3"></i>
                </div>
            </div>
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('My Vendors')</h6>
                    <h3>{{ $myVendors }}</h3>
                </div>
                <div class="icon-box variant-orange mb-0">
                    <i class="bi bi-shop fs-3"></i>
                </div>
            </div>
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Total Earnings')</h6>
                    <h3>{{ showAmount($totalEarnings, currencyFormat: true) }}</h3>
                </div>
                <div class="icon-box variant-pink mb-0">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
            </div>
            <div class="premium-card stat-item">
                <div class="stat-info">
                    <h6 class="text-muted text-uppercase">@lang('Total Reference Earnings')</h6>
                    <h3>{{ showAmount($totalReferenceEarnings, currencyFormat: true) }}</h3>
                </div>
                <div class="icon-box variant-blue mb-0">
                    <i class="bi bi-person-plus-fill fs-3"></i>
                </div>
            </div>
        </div>

        {{-- My Details --}}
        <div class="premium-card mb-4">
            <h5 class="mb-4"><i class="bi bi-person-vcard"></i> @lang('My Details')</h5>
            
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-custom">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(128,128,128,0.1);">
                            <th class="text-muted">@lang('Username')</th>
                            <th class="text-muted">@lang('Name')</th>
                            <th class="text-muted">@lang('DSP')</th>
                            <th class="text-muted">@lang('Under User')</th>
                            <th class="text-muted">@lang('City')</th>
                            <th class="text-muted">@lang('Position')</th>
                            <th class="text-muted">@lang('Rank')</th>
                            <th class="text-muted">@lang('Level')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid rgba(128,128,128,0.05);">
                            <td class="fw-bold text-primary">{{ $username }}</td>
                            <td>{{ $name }}</td>
                            <td>
                                @if($user->plan_id == 1)
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill">@lang('Active')</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill">@lang('Inactive')</span>
                                @endif
                            </td>
                            <td>{{ $underUser }}</td>
                            <td>{{ $city }}</td>
                            <td>{{ $position }}</td>
                            <td>{{ $rank }}</td>
                            <td>{{ $level }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="d-block d-md-none">
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="mb-0 text-primary fw-bold">{{ $username }}</h6>
                            <small class="text-muted">{{ $name }}</small>
                        </div>
                        @if($user->plan_id == 1)
                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill">@lang('Active')</span>
                        @else
                            <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill">@lang('Inactive')</span>
                        @endif
                    </div>
                    <div class="row g-2">
                        <div class="col-6"><small class="text-muted">@lang('Under User'):</small><br><strong>{{ $underUser }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('City'):</small><br><strong>{{ $city }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('Position'):</small><br><strong>{{ $position }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('Rank'):</small><br><strong>{{ $rank }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('Level'):</small><br><strong>{{ $level }}</strong></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- My DSP Users List --}}
        <div class="premium-card mb-4">
            <h5 class="mb-4"><i class="bi bi-diagram-3-fill"></i> @lang('My DSP Users')</h5>
            
            @php
                $startNumber = $dspUsers->total() - ($dspUsers->currentPage() - 1) * $dspUsers->perPage();
            @endphp
            
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-custom">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(128,128,128,0.1);">
                            <th class="text-muted">@lang('S No.')</th>
                            <th class="text-muted">@lang('Username')</th>
                            <th class="text-muted">@lang('Name')</th>
                            <th class="text-muted">@lang('Under User')</th>
                            <th class="text-muted">@lang('City')</th>
                            <th class="text-muted">@lang('Position')</th>
                            <th class="text-muted">@lang('Rank')</th>
                            <th class="text-muted">@lang('Level')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $dspStartNum = $startNumber; @endphp
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
                            <tr style="border-bottom: 1px solid rgba(128,128,128,0.05);">
                                <td class="fw-bold">{{ $dspStartNum-- }}</td>
                                <td class="text-primary fw-bold">{{ $dspUser->username }}</td>
                                <td>{{ $dspUser->refBy->fullname ?? 'N/A' }}</td>
                                <td>{{ $positionalUser->username ?? 'N/A' }}</td>
                                <td>{{ $dspUser->city ?? 'N/A' }}</td>
                                <td>{{ $positionName }}</td>
                                <td>{{ $userRank }}</td>
                                <td>{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-4" colspan="8">
                                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                    @lang('No DSP users found.')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="d-block d-md-none">
                @php $dspMobileNum = $startNumber; @endphp
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
                    <div class="mobile-card-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-muted small">#{{ $dspMobileNum-- }}</span>
                                <h6 class="mb-0 text-primary fw-bold">{{ $dspUser->username }}</h6>
                                <small class="text-muted">{{ $dspUser->refBy->fullname ?? 'N/A' }}</small>
                            </div>
                            <span class="badge bg-info bg-opacity-25 text-info rounded-pill">{{ $userRank }}</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-6"><small class="text-muted">@lang('Under'):</small><br><strong>{{ $positionalUser->username ?? 'N/A' }}</strong></div>
                            <div class="col-6"><small class="text-muted">@lang('City'):</small><br><strong>{{ $dspUser->city ?? 'N/A' }}</strong></div>
                            <div class="col-6"><small class="text-muted">@lang('Position'):</small><br><strong>{{ $positionName }}</strong></div>
                            <div class="col-6"><small class="text-muted">@lang('Level'):</small><br><strong>{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</strong></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                        @lang('No DSP users found.')
                    </div>
                @endforelse
            </div>

            <!-- Premium Pagination -->
            @if($dspUsers->hasPages())
                <div class="premium-pagination mt-4">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        @if ($dspUsers->onFirstPage())
                            <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                        @else
                            <a href="{{ $dspUsers->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                        @endif

                        @php
                            $currentPage = $dspUsers->currentPage();
                            $lastPage = $dspUsers->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp

                        @if($start > 1)
                            <a href="{{ $dspUsers->url(1) }}" class="page-btn">1</a>
                            @if($start > 2)<span class="page-dots">...</span>@endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            <a href="{{ $dspUsers->url($i) }}" class="page-btn {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                        @endfor

                        @if($end < $lastPage)
                            @if($end < $lastPage - 1)<span class="page-dots">...</span>@endif
                            <a href="{{ $dspUsers->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
                        @endif

                        @if ($dspUsers->hasMorePages())
                            <a href="{{ $dspUsers->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                        @else
                            <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                        @endif
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">@lang('Page') {{ $dspUsers->currentPage() }} @lang('of') {{ $dspUsers->lastPage() }} ({{ $dspUsers->total() }} @lang('items'))</small>
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- "My Referred Users" Tab Pane --}}
    <div class="tab-pane fade {{ request()->get('tab') == 'referred-users' ? 'show active' : '' }}" id="referred-users" role="tabpanel" aria-labelledby="referred-users-tab">
        <div class="premium-card mb-4">
            <h5 class="mb-4"><i class="bi bi-people-fill"></i> @lang('My Referred Users')</h5>
            
            @php
                $refStartNumber = $referredUsers->total() - ($referredUsers->currentPage() - 1) * $referredUsers->perPage();
            @endphp
            
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-custom">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(128,128,128,0.1);">
                            <th class="text-muted">@lang('S No.')</th>
                            <th class="text-muted">@lang('Username')</th>
                            <th class="text-muted">@lang('Name')</th>
                            <th class="text-muted">@lang('Under User')</th>
                            <th class="text-muted">@lang('City')</th>
                            <th class="text-muted">@lang('Position')</th>
                            <th class="text-muted">@lang('Rank')</th>
                            <th class="text-muted">@lang('Level')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $refDesktopNum = $refStartNumber; @endphp
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
                            <tr style="border-bottom: 1px solid rgba(128,128,128,0.05);">
                                <td class="fw-bold">{{ $refDesktopNum-- }}</td>
                                <td class="text-primary fw-bold">{{ $referredUser->username }}</td>
                                <td>{{ $referredUser->fullname }}</td>
                                <td>{{ $positionalUser->username ?? 'N/A' }}</td>
                                <td>{{ $referredUser->city ?? 'N/A' }}</td>
                                <td>{{ $positionName }}</td>
                                <td>{{ $userRank }}</td>
                                <td>{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-4" colspan="8">
                                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                    @lang('No referred users found.')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="d-block d-md-none">
                @php $refMobileNum = $refStartNumber; @endphp
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
                    <div class="mobile-card-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-muted small">#{{ $refMobileNum-- }}</span>
                                <h6 class="mb-0 text-primary fw-bold">{{ $referredUser->username }}</h6>
                                <small class="text-muted">{{ $referredUser->fullname }}</small>
                            </div>
                            <span class="badge bg-info bg-opacity-25 text-info rounded-pill">{{ $userRank }}</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-6"><small class="text-muted">@lang('Under'):</small><br><strong>{{ $positionalUser->username ?? 'N/A' }}</strong></div>
                            <div class="col-6"><small class="text-muted">@lang('City'):</small><br><strong>{{ $referredUser->city ?? 'N/A' }}</strong></div>
                            <div class="col-6"><small class="text-muted">@lang('Position'):</small><br><strong>{{ $positionName }}</strong></div>
                            <div class="col-6"><small class="text-muted">@lang('Level'):</small><br><strong>{{ $perfectLevel != -1 ? $perfectLevel : 'N/A' }}</strong></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                        @lang('No referred users found.')
                    </div>
                @endforelse
            </div>

            <!-- Premium Pagination -->
            @if($referredUsers->hasPages())
                <div class="premium-pagination mt-4">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        @if ($referredUsers->onFirstPage())
                            <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                        @else
                            <a href="{{ $referredUsers->appends(['tab' => 'referred-users'])->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                        @endif

                        @php
                            $refCurrentPage = $referredUsers->currentPage();
                            $refLastPage = $referredUsers->lastPage();
                            $refStart = max(1, $refCurrentPage - 2);
                            $refEnd = min($refLastPage, $refCurrentPage + 2);
                        @endphp

                        @if($refStart > 1)
                            <a href="{{ $referredUsers->appends(['tab' => 'referred-users'])->url(1) }}" class="page-btn">1</a>
                            @if($refStart > 2)<span class="page-dots">...</span>@endif
                        @endif

                        @for ($i = $refStart; $i <= $refEnd; $i++)
                            <a href="{{ $referredUsers->appends(['tab' => 'referred-users'])->url($i) }}" class="page-btn {{ $i == $refCurrentPage ? 'active' : '' }}">{{ $i }}</a>
                        @endfor

                        @if($refEnd < $refLastPage)
                            @if($refEnd < $refLastPage - 1)<span class="page-dots">...</span>@endif
                            <a href="{{ $referredUsers->appends(['tab' => 'referred-users'])->url($refLastPage) }}" class="page-btn">{{ $refLastPage }}</a>
                        @endif

                        @if ($referredUsers->hasMorePages())
                            <a href="{{ $referredUsers->appends(['tab' => 'referred-users'])->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                        @else
                            <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                        @endif
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">@lang('Page') {{ $referredUsers->currentPage() }} @lang('of') {{ $referredUsers->lastPage() }} ({{ $referredUsers->total() }} @lang('items'))</small>
                    </div>
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