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
                <h2 class="page-title"><i class="bi bi-shop-window"></i> @lang('Shops & Franchises')</h2>
                <p class="page-subtitle">@lang('View all vendors, shops, and franchises in your network')</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-1">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Total Shops')</h6>
                        <h3 class="ammount theme-two">{{ $shops }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-shop"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-2">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Total Franchises')</h6>
                        <h3 class="ammount theme-two">{{ $franchises }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-building"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-3">
                <div class="dashboard-item-header">
                    <div class="header-left">
                        <h6 class="title">@lang('Total Vendors')</h6>
                        <h3 class="ammount theme-two">{{ $vendors }}</h3>
                    </div>
                    <div class="right-content">
                        <div class="icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-item mb-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title"><i class="bi bi-list-ul"></i> @lang('Vendors List')</h4>
                </div>
                <div class="table-responsive">
                    <table class="table transection-table-2">
                        <thead>
                            <tr>
                                <th scope="col"><i class="bi bi-hash"></i> @lang('No.')</th>
                                <th scope="col"><i class="bi bi-person-badge"></i> @lang('Username')</th>
                                <th scope="col"><i class="bi bi-card-text"></i> @lang('Vendor ID')</th>
                                <th scope="col"><i class="bi bi-shop"></i> @lang('Vendor Name')</th>
                                <th scope="col"><i class="bi bi-tag"></i> @lang('Type')</th>
                                <th scope="col"><i class="bi bi-geo-alt"></i> @lang('City')</th>
                                <th scope="col"><i class="bi bi-pin-map"></i> @lang('Location')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellers as $key => $seller)
                                <tr>
                                    <td data-label="@lang('No.')">{{ $sellers->firstItem() + $key }}</td>
                                    <td data-label="@lang('Username')">
                                        <strong class="text-primary">{{ $seller->username }}</strong>
                                    </td>
                                    <td data-label="@lang('Vendor ID')">
                                        <span class="badge bg-info">{{ $seller->vendor_id }}</span>
                                    </td>
                                    <td data-label="@lang('Vendor Name')">
                                        {{ optional($seller->primaryShop)->name ?? 'N/A' }}
                                    </td>
                                    <td data-label="@lang('Type')">
                                        @if($seller->vendor_type == 'Shop')
                                            <span class="badge badge--success"><i class="bi bi-shop"></i> {{ $seller->vendor_type }}</span>
                                        @elseif($seller->vendor_type == 'Franchise')
                                            <span class="badge badge--primary"><i class="bi bi-building"></i> {{ $seller->vendor_type }}</span>
                                        @else
                                            <span class="badge badge--warning"><i class="bi bi-person"></i> {{ $seller->vendor_type }}</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('City')">
                                        <i class="bi bi-geo-alt-fill"></i> {{ $seller->city }}
                                    </td>
                                    <td data-label="@lang('Location')">
                                        <small>{{ optional($seller->primaryShop)->address ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                        @lang('No records found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($sellers->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    {{ $sellers->links() }}
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
/* Shops & Franchises Page Custom Styles */
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

.badge--primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.badge--warning {
    background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(242, 153, 74, 0.3);
}

.badge i {
    font-size: 10px;
}

.bg-info {
    background: linear-gradient(135deg, #17a2b8 0%, #56c0d1 100%);
    color: #ffffff;
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