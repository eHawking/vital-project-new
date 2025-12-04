@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="container-fluid px-4 py-4 inner-dashboard-container">

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h3 class="premium-title mb-0">@lang('My Referrals')</h3>
                    <p class="text-muted small m-0">@lang('View all your direct referrals and their status')</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Stats Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- Total Referrals -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6 class="text-muted text-uppercase">@lang('Total Referrals')</h6>
                <h3>{{ $logs->total() }}</h3>
            </div>
            <div class="icon-box variant-blue mb-0">
                <i class="bi bi-people-fill fs-3"></i>
            </div>
        </div>

        <!-- Active DSP -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6 class="text-muted text-uppercase">@lang('Active DSP')</h6>
                <h3>{{ $logs->where('plan_id', 1)->count() }}</h3>
            </div>
            <div class="icon-box variant-green mb-0">
                <i class="bi bi-check-circle-fill fs-3"></i>
            </div>
        </div>

        <!-- Active BTP -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6 class="text-muted text-uppercase">@lang('Active BTP')</h6>
                <h3>{{ $logs->where('is_btp', 1)->count() }}</h3>
            </div>
            <div class="icon-box variant-purple mb-0">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
        </div>

        <!-- This Month -->
        <div class="premium-card stat-item">
            <div class="stat-info">
                <h6 class="text-muted text-uppercase">@lang('This Month')</h6>
                <h3>{{ $logs->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
            </div>
            <div class="icon-box variant-orange mb-0">
                <i class="bi bi-calendar-check-fill fs-3"></i>
            </div>
        </div>
    </div>

    <!-- Referrals Table -->
    <div class="premium-card">
        <h5 class="mb-4">@lang('Referral List')</h5>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(128,128,128,0.1);">
                        <th class="text-muted"><i class="bi bi-hash"></i> @lang('No.')</th>
                        <th class="text-muted"><i class="bi bi-person-badge"></i> @lang('Username')</th>
                        <th class="text-muted"><i class="bi bi-person"></i> @lang('Name')</th>
                        <th class="text-muted"><i class="bi bi-envelope"></i> @lang('Email')</th>
                        <th class="text-muted"><i class="bi bi-shield-check"></i> @lang('DSP')</th>
                        <th class="text-muted"><i class="bi bi-award"></i> @lang('BTP')</th>
                        <th class="text-muted"><i class="bi bi-calendar-event"></i> @lang('Join Date')</th>
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
                        <tr style="border-bottom: 1px solid rgba(128,128,128,0.05);">
                            <td data-label="@lang('No.')" class="fw-bold">{{ $startNumber - $key }}</td>
                            <td data-label="@lang('Username')">
                                <strong class="text-primary">{{ $data->username }}</strong>
                            </td>
                            <td data-label="@lang('Name')">{{ $data->fullname }}</td>
                            <td data-label="@lang('Email')">
                                <small class="text-muted">{{ $data->email }}</small>
                            </td>
                            <td data-label="@lang('DSP')">
                                @if($data->plan_id == 1)
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Active')</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill"><i class="bi bi-x-circle-fill"></i> @lang('Inactive')</span>
                                @endif
                            </td>
                            <td data-label="@lang('BTP')">
                                @if($data->is_btp == 1)
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-star-fill"></i> @lang('Active')</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill"><i class="bi bi-dash-circle-fill"></i> @lang('Inactive')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Join Date')">
                                <div>
                                    <i class="bi bi-calendar3 text-muted"></i> {{ showDateTime($data->created_at, 'd M Y') }}
                                </div>
                                <small class="text-muted">{{ $data->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center py-4" colspan="7">
                                <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                {{ __($emptyMessage) }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="mt-4">
                {{ paginateLinks($logs) }}
            </div>
        @endif
    </div>

</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

<style>
    /* Ensure table styling matches theme */
    .table-custom {
        color: var(--text-primary) !important;
    }
    .table-custom th, .table-custom td {
        background: transparent !important;
        vertical-align: middle;
        padding: 15px;
        color: inherit !important;
    }
    /* Ensure headings and other text inherit correct color */
    h1, h2, h3, h4, h5, h6, .premium-title {
        color: var(--text-primary) !important;
    }
    
    /* Mobile Full Width Adjustments */
    @media (max-width: 768px) {
        .container-fluid.px-4 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .inner-dashboard-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .premium-card {
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
        }
    }
</style>

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush