@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
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

    /* Stat Card */
    .stat-card {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        padding: 20px;
    }
    [data-theme="light"] .stat-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Table Custom */
    .table-custom {
        margin-bottom: 0;
    }
    .table-custom th {
        background: rgba(128,128,128,0.05) !important;
        color: var(--text-muted) !important;
        font-weight: 600;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.1) !important;
    }
    .table-custom td {
        color: var(--text-primary) !important;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.05) !important;
        vertical-align: middle;
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
    }
    [data-theme="light"] .mobile-card-item {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Premium Pagination */
    .premium-pagination .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border-radius: 10px;
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .premium-pagination .page-btn {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .premium-pagination .page-btn:hover {
        background: var(--color-primary);
        border-color: var(--color-primary);
        color: #fff;
    }
    .premium-pagination .page-btn.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-color: transparent;
        color: #fff;
    }
    .premium-pagination .page-btn.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
    .premium-pagination .page-dots {
        color: var(--text-muted);
        padding: 0 5px;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-shop-window"></i> @lang('Shops & Franchises')</h4>
        <p class="text-muted small m-0">@lang('View all vendors, shops, and franchises in your network')</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small">@lang('Total Shops')</p>
                    <h3 class="mb-0 fw-bold">{{ $shops }}</h3>
                </div>
                <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-shop fs-4"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small">@lang('Total Franchises')</p>
                    <h3 class="mb-0 fw-bold">{{ $franchises }}</h3>
                </div>
                <div class="icon-box variant-purple" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-building fs-4"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small">@lang('Total Vendors')</p>
                    <h3 class="mb-0 fw-bold">{{ $vendors }}</h3>
                </div>
                <div class="icon-box variant-pink" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendors List Card -->
    <div class="premium-card mb-4">
        <h5 class="mb-4"><i class="bi bi-list-ul"></i> @lang('Vendors List')</h5>

        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>@lang('No.')</th>
                        <th>@lang('Username')</th>
                        <th>@lang('Vendor ID')</th>
                        <th>@lang('Vendor Name')</th>
                        <th>@lang('Type')</th>
                        <th>@lang('City')</th>
                        <th>@lang('Location')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellers as $key => $seller)
                        <tr>
                            <td class="fw-bold">{{ $sellers->firstItem() + $key }}</td>
                            <td><strong class="text-info">{{ $seller->username }}</strong></td>
                            <td><span class="badge bg-info bg-opacity-25 text-info rounded-pill">{{ $seller->vendor_id }}</span></td>
                            <td>{{ optional($seller->primaryShop)->name ?? 'N/A' }}</td>
                            <td>
                                @if($seller->vendor_type == 'Shop')
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-shop"></i> {{ $seller->vendor_type }}</span>
                                @elseif($seller->vendor_type == 'Franchise')
                                    <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill"><i class="bi bi-building"></i> {{ $seller->vendor_type }}</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill"><i class="bi bi-person"></i> {{ $seller->vendor_type }}</span>
                                @endif
                            </td>
                            <td><i class="bi bi-geo-alt-fill text-muted"></i> {{ $seller->city }}</td>
                            <td><small class="text-muted">{{ optional($seller->primaryShop)->address ?? 'N/A' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                @lang('No records found')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none">
            @forelse($sellers as $key => $seller)
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small">#{{ $sellers->firstItem() + $key }}</span>
                            <h6 class="mb-0 text-info fw-bold">{{ $seller->username }}</h6>
                            <small class="text-muted">{{ optional($seller->primaryShop)->name ?? 'N/A' }}</small>
                        </div>
                        @if($seller->vendor_type == 'Shop')
                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill"><i class="bi bi-shop"></i> {{ $seller->vendor_type }}</span>
                        @elseif($seller->vendor_type == 'Franchise')
                            <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill"><i class="bi bi-building"></i> {{ $seller->vendor_type }}</span>
                        @else
                            <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill"><i class="bi bi-person"></i> {{ $seller->vendor_type }}</span>
                        @endif
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">@lang('Vendor ID'):</small><br>
                            <span class="badge bg-info bg-opacity-25 text-info rounded-pill">{{ $seller->vendor_id }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">@lang('City'):</small><br>
                            <strong><i class="bi bi-geo-alt-fill text-muted"></i> {{ $seller->city }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">@lang('Location'):</small><br>
                            <small>{{ optional($seller->primaryShop)->address ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    @lang('No records found')
                </div>
            @endforelse
        </div>

        <!-- Premium Pagination -->
        @if($sellers->hasPages())
            <div class="premium-pagination mt-4">
                <div class="d-flex justify-content-center align-items-center gap-2">
                    @if ($sellers->onFirstPage())
                        <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                    @else
                        <a href="{{ $sellers->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                    @endif

                    @php
                        $currentPage = $sellers->currentPage();
                        $lastPage = $sellers->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $sellers->url(1) }}" class="page-btn">1</a>
                        @if($start > 2)<span class="page-dots">...</span>@endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ $sellers->url($i) }}" class="page-btn {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                    @endfor

                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)<span class="page-dots">...</span>@endif
                        <a href="{{ $sellers->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
                    @endif

                    @if ($sellers->hasMorePages())
                        <a href="{{ $sellers->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">@lang('Page') {{ $sellers->currentPage() }} @lang('of') {{ $sellers->lastPage() }} ({{ $sellers->total() }} @lang('items'))</small>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush