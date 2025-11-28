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
        <div class="col-12">
            <!-- Page Header -->
            <div class="dashboard-header mb-4">
                <h2 class="page-title"><i class="bi bi-people-fill"></i> @lang('My Referrals')</h2>
                <p class="page-subtitle">@lang('View all your direct referrals and their status')</p>
            </div>
        </div>
    </div>

    <!-- Referral Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-item gradient-card-1">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Total Referrals')</h6>
                        <h3 class="ammount theme-two">{{ $logs->total() }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-item gradient-card-2">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Active DSP')</h6>
                        <h3 class="ammount theme-two">{{ $logs->where('plan_id', 1)->count() }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-check-circle-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-item gradient-card-3">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Active BTP')</h6>
                        <h3 class="ammount theme-two">{{ $logs->where('is_btp', 1)->count() }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-star-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-item gradient-card-4">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('This Month')</h6>
                        <h3 class="ammount theme-two">{{ $logs->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-calendar-check-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referrals Table -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-item mb-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title"><i class="bi bi-list-ul"></i> @lang('Referral List')</h4>
                </div>
                <div class="table-responsive">
                    <table class="transection-table-2">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> @lang('No.')</th>
                                <th><i class="bi bi-person-badge"></i> @lang('Username')</th>
                                <th><i class="bi bi-person"></i> @lang('Name')</th>
                                <th><i class="bi bi-envelope"></i> @lang('Email')</th>
                                <th><i class="bi bi-shield-check"></i> @lang('DSP')</th>
                                <th><i class="bi bi-award"></i> @lang('BTP')</th>
                                <th><i class="bi bi-calendar-event"></i> @lang('Join Date')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentPage = $logs->currentPage();
                                $perPage = $logs->perPage();
                                $totalRecords = $logs->total();
                                $startNumber = $totalRecords - (($currentPage - 1) * $perPage);
                            @endphp
                            @forelse($logs as $key => $data)
                                <tr>
                                    <td data-label="@lang('No.')">{{ $startNumber - $key }}</td>
                                    <td data-label="@lang('Username')">
                                        <strong class="text-primary">{{ $data->username }}</strong>
                                    </td>
                                    <td data-label="@lang('Name')">{{ $data->fullname }}</td>
                                    <td data-label="@lang('Email')">
                                        <small>{{ $data->email }}</small>
                                    </td>
                                    <td data-label="@lang('DSP')">
                                        @if($data->plan_id == 1)
                                            <span class="badge badge--success"><i class="bi bi-check-circle-fill"></i> @lang('Active')</span>
                                        @else
                                            <span class="badge badge--danger"><i class="bi bi-x-circle-fill"></i> @lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('BTP')">
                                        @if($data->is_btp == 1)
                                            <span class="badge badge--success"><i class="bi bi-star-fill"></i> @lang('Active')</span>
                                        @else
                                            <span class="badge badge--warning"><i class="bi bi-dash-circle-fill"></i> @lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Join Date')">
                                        <i class="bi bi-calendar3"></i> {{ showDateTime($data->created_at, 'd M Y') }}<br>
                                        <small class="text-muted">{{ $data->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="7">
                                        <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                        {{ __($emptyMessage) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($logs->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    {{ paginateLinks($logs) }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

<style>
/* Referrals Page Custom Styles */
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

.badge--danger {
    background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(238, 9, 121, 0.3);
}

.badge--warning {
    background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(242, 153, 74, 0.3);
}

.badge i {
    font-size: 10px;
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
    font-weight: 700;
    color: var(--accent-blue);
}

.transection-table-2 tbody td:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

.transection-table-2 tbody td i {
    color: var(--accent-cyan);
    margin-right: 5px;
}

.text-primary {
    color: var(--accent-blue) !important;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
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
    
    .badge {
        font-size: 10px;
        padding: 4px 10px;
    }
}
</style>
@endpush