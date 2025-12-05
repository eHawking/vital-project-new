@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
    /* Theme Text Colors */
    h1, h2, h3, h4, h5, h6 {
        color: #ffffff;
    }
    [data-theme="light"] h1,
    [data-theme="light"] h2,
    [data-theme="light"] h3,
    [data-theme="light"] h4,
    [data-theme="light"] h5,
    [data-theme="light"] h6 {
        color: #1a1f2e;
    }

    .page-subtitle {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .page-subtitle {
        color: #6c757d;
    }

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
        .top-cards-grid {
            grid-template-columns: 1fr !important;
        }
    }

    /* Top Cards Grid */
    .top-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    @media (max-width: 992px) {
        .top-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Top Card */
    .top-card {
        background: #1a1f2e;
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }
    [data-theme="light"] .top-card {
        background: #ffffff;
    }
    .top-card .card-username {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    [data-theme="light"] .top-card .card-username {
        color: #1a1f2e;
    }
    .top-card .card-subtext {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .top-card .card-subtext {
        color: #6c757d;
    }
    .top-card .decorative-icon {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 6rem;
        line-height: 1;
        transform: translate(20%, -20%);
        opacity: 0.1;
    }

    /* Table Custom */
    .table-custom {
        margin-bottom: 0;
    }
    .table-custom th {
        background: rgba(128,128,128,0.05) !important;
        color: rgba(255,255,255,0.6) !important;
        font-weight: 600;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.1) !important;
    }
    [data-theme="light"] .table-custom th {
        color: #6c757d !important;
    }
    .table-custom td {
        color: #ffffff !important;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(128,128,128,0.05) !important;
        vertical-align: middle;
    }
    [data-theme="light"] .table-custom td {
        color: #1a1f2e !important;
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
        color: #ffffff;
    }
    [data-theme="light"] .mobile-card-item {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .mobile-card-item .text-muted-custom {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .mobile-card-item .text-muted-custom {
        color: #6c757d;
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
        color: #ffffff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .premium-pagination .page-btn {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
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
        color: rgba(255,255,255,0.5);
        padding: 0 5px;
    }
    [data-theme="light"] .premium-pagination .page-dots {
        color: #6c757d;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-bell-fill"></i> @lang('Live Notifications')</h4>
        <p class="page-subtitle small m-0">@lang('Top performers and recent activities')</p>
    </div>

    <!-- Top Cards Grid -->
    <div class="top-cards-grid">
        {{-- Top Seller Card --}}
        @if($seller_user)
            <div class="top-card" style="border: 1px solid rgba(255, 215, 0, 0.3);">
                <div class="d-flex align-items-center justify-content-between position-relative">
                    <div class="flex-grow-1">
                        <h6 class="text-warning mb-2">@lang('Top Seller') 🏆</h6>
                        <h4 class="card-username mb-1">{{ $seller_user->username }}</h4>
                        <p class="card-subtext mb-2 small">{{ $seller_user->firstname . ' ' . $seller_user->lastname }}</p>
                        <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-warning bg-opacity-10 border border-warning border-opacity-25">
                            <i class="bi bi-bag-check-fill text-warning me-2"></i>
                            <span class="text-warning fw-bold">{{ $top_seller->order_count }} @lang('Sales')</span>
                        </div>
                    </div>
                    <div class="user-thumb">
                        <img src="{{ getImage('assets/images/user/profile/'. $seller_user->image, '350x300', true) }}" alt="top-seller" class="rounded-circle border border-2 border-warning" style="width: 70px; height: 70px; object-fit: cover; box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);">
                    </div>
                </div>
                <div class="decorative-icon text-warning"><i class="bi bi-trophy-fill"></i></div>
            </div>
        @endif

        {{-- Top Customer Card --}}
        @if($customer_user)
            <div class="top-card" style="border: 1px solid rgba(13, 202, 240, 0.3);">
                <div class="d-flex align-items-center justify-content-between position-relative">
                    <div class="flex-grow-1">
                        <h6 class="text-info mb-2">@lang('Top Customer') 👑</h6>
                        <h4 class="card-username mb-1">{{ $customer_user->username }}</h4>
                        <p class="card-subtext mb-2 small">{{ $customer_user->firstname . ' ' . $customer_user->lastname }}</p>
                        <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-info bg-opacity-10 border border-info border-opacity-25">
                            <i class="bi bi-cart-check-fill text-info me-2"></i>
                            <span class="text-info fw-bold">{{ $top_customer->order_count }} @lang('Purchases')</span>
                        </div>
                    </div>
                    <div class="user-thumb">
                        <img src="{{ getImage('assets/images/user/profile/'. $customer_user->image, '350x300', true) }}" alt="top-customer" class="rounded-circle border border-2 border-info" style="width: 70px; height: 70px; object-fit: cover; box-shadow: 0 0 15px rgba(13, 202, 240, 0.3);">
                    </div>
                </div>
                <div class="decorative-icon text-info"><i class="bi bi-crown"></i></div>
            </div>
        @endif

        {{-- Recent User Card --}}
        @if($recent_entry)
            <div class="top-card" style="border: 1px solid rgba(32, 201, 151, 0.3);">
                <div class="d-flex align-items-center justify-content-between position-relative">
                    <div class="flex-grow-1">
                        <h6 class="text-success mb-2">@lang('Recent User') ⭐</h6>
                        <h4 class="card-username mb-1">{{ $recent_entry->username }}</h4>
                        <p class="card-subtext mb-1 small">{{ $recent_entry->firstname . ' ' . $recent_entry->lastname }}</p>
                        <div class="d-flex flex-column gap-1">
                            <small class="card-subtext"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $recent_entry->city }}</small>
                            <small class="card-subtext"><i class="bi bi-calendar-event text-success me-1"></i> {{ date('j F Y', strtotime($recent_entry->created_at)) }}</small>
                        </div>
                    </div>
                    <div class="user-thumb">
                        <img src="{{ getImage('assets/images/user/profile/'. $recent_entry->image, '350x300', true) }}" alt="recent-user" class="rounded-circle border border-2 border-success" style="width: 70px; height: 70px; object-fit: cover; box-shadow: 0 0 15px rgba(32, 201, 151, 0.3);">
                    </div>
                </div>
                <div class="decorative-icon text-success"><i class="bi bi-star-fill"></i></div>
            </div>
        @endif
    </div>

    <!-- Transactions Card -->
    <div class="premium-card mb-4">
        <h5 class="mb-4"><i class="bi bi-clock-history"></i> @lang('Transactions in the Last 7 Days')</h5>

        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>@lang('Date & Time')</th>
                        <th>@lang('User Details')</th>
                        <th>@lang('City')</th>
                        <th>@lang('Transaction Details')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td><i class="bi bi-calendar3 text-muted me-1"></i> {{ date('d M Y h:i A', strtotime($transaction->created_at)) }}</td>
                            <td>
                                <div class="fw-bold text-info">{{ $transaction->username }}</div>
                                <small class="text-muted">{{ $transaction->name }}</small>
                            </td>
                            <td>{{ $transaction->city }}</td>
                            <td>{{ $transaction->details }}</td>
                            <td class="fw-bold text-success">{{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                @lang('No transactions found in the last 7 days')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none">
            @forelse($transactions as $transaction)
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-0 text-info fw-bold">{{ $transaction->username }}</h6>
                            <small class="text-muted-custom">{{ $transaction->name }}</small>
                        </div>
                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill fw-bold">{{ number_format($transaction->amount, 2) }}</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted-custom">@lang('City'):</small><br>
                            <strong>{{ $transaction->city }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom">@lang('Date'):</small><br>
                            <strong>{{ date('d M Y', strtotime($transaction->created_at)) }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted-custom">@lang('Details'):</small><br>
                            <small>{{ $transaction->details }}</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    <span class="text-muted-custom">@lang('No transactions found in the last 7 days')</span>
                </div>
            @endforelse
        </div>

        <!-- Premium Pagination -->
        @if($transactions->hasPages())
            <div class="premium-pagination mt-4">
                <div class="d-flex justify-content-center align-items-center gap-2">
                    @if ($transactions->onFirstPage())
                        <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                    @endif

                    @php
                        $currentPage = $transactions->currentPage();
                        $lastPage = $transactions->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $transactions->url(1) }}" class="page-btn">1</a>
                        @if($start > 2)<span class="page-dots">...</span>@endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ $transactions->url($i) }}" class="page-btn {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                    @endfor

                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)<span class="page-dots">...</span>@endif
                        <a href="{{ $transactions->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
                    @endif

                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">@lang('Page') {{ $transactions->currentPage() }} @lang('of') {{ $transactions->lastPage() }} ({{ $transactions->total() }} @lang('items'))</small>
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