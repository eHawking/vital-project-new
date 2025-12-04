@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
    @media (max-width: 768px) {
        .inner-dashboard-container { padding: 10px !important; }
        .premium-card { border-radius: 12px !important; }
    }
    .table-custom th { background: rgba(128,128,128,0.05) !important; color: var(--text-muted) !important; font-weight: 600; padding: 12px 15px; border-bottom: 1px solid rgba(128,128,128,0.1) !important; }
    .table-custom td { padding: 12px 15px; border-bottom: 1px solid rgba(128,128,128,0.05) !important; color: var(--text-primary) !important; }
    .table-custom tbody tr:hover { background: rgba(128,128,128,0.03) !important; }
    .mobile-card-item { background: rgba(128,128,128,0.03); border: 1px solid rgba(128,128,128,0.1); border-radius: 12px; padding: 15px; margin-bottom: 10px; }
    .performer-card { position: relative; overflow: hidden; }
    .performer-card .bg-icon { position: absolute; top: 0; right: 0; font-size: 6rem; line-height: 1; transform: translate(20%, -20%); opacity: 0.1; }
</style>

<div class="container-fluid px-4 py-3 inner-dashboard-container">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="m-0"><i class="bi bi-bell-fill"></i> @lang('Live Notifications')</h4>
            <p class="text-muted small m-0">@lang('Top performers and recent activities')</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Top Seller Card --}}
        @if($seller_user)
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 performer-card" style="border: 1px solid rgba(255, 215, 0, 0.3);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <h6 class="text-warning mb-2">@lang('Top Seller') 🏆</h6>
                            <h4 class="text-uppercase mb-1 fw-bold">{{ $seller_user->username }}</h4>
                            <p class="text-muted mb-2 small">{{ $seller_user->firstname . ' ' . $seller_user->lastname }}</p>
                            <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                <i class="bi bi-bag-check-fill text-warning me-2"></i>
                                <span class="text-warning fw-bold">{{ $top_seller->order_count }} @lang('Sales')</span>
                            </div>
                        </div>
                        <img src="{{ getImage('assets/images/user/profile/'. $seller_user->image, '350x300', true) }}" alt="top-seller" class="rounded-circle border border-2 border-warning" style="width: 70px; height: 70px; object-fit: cover; box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);">
                    </div>
                    <div class="bg-icon text-warning"><i class="bi bi-trophy-fill"></i></div>
                </div>
            </div>
        @endif

        {{-- Top Customer Card --}}
        @if($customer_user)
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 performer-card" style="border: 1px solid rgba(13, 202, 240, 0.3);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <h6 class="text-info mb-2">@lang('Top Customer') 👑</h6>
                            <h4 class="text-uppercase mb-1 fw-bold">{{ $customer_user->username }}</h4>
                            <p class="text-muted mb-2 small">{{ $customer_user->firstname . ' ' . $customer_user->lastname }}</p>
                            <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-info bg-opacity-10 border border-info border-opacity-25">
                                <i class="bi bi-cart-check-fill text-info me-2"></i>
                                <span class="text-info fw-bold">{{ $top_customer->order_count }} @lang('Purchases')</span>
                            </div>
                        </div>
                        <img src="{{ getImage('assets/images/user/profile/'. $customer_user->image, '350x300', true) }}" alt="top-customer" class="rounded-circle border border-2 border-info" style="width: 70px; height: 70px; object-fit: cover; box-shadow: 0 0 15px rgba(13, 202, 240, 0.3);">
                    </div>
                    <div class="bg-icon text-info"><i class="bi bi-crown"></i></div>
                </div>
            </div>
        @endif

        {{-- Recent User Card --}}
        @if($recent_entry)
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 performer-card" style="border: 1px solid rgba(32, 201, 151, 0.3);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <h6 class="text-success mb-2">@lang('Recent User') ⭐</h6>
                            <h4 class="text-uppercase mb-1 fw-bold">{{ $recent_entry->username }}</h4>
                            <p class="text-muted mb-1 small">{{ $recent_entry->firstname . ' ' . $recent_entry->lastname }}</p>
                            <div class="d-flex flex-column gap-1">
                                <small class="text-muted"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $recent_entry->city }}</small>
                                <small class="text-muted"><i class="bi bi-calendar-event text-success me-1"></i> {{ date('j F Y', strtotime($recent_entry->created_at)) }}</small>
                            </div>
                        </div>
                        <img src="{{ getImage('assets/images/user/profile/'. $recent_entry->image, '350x300', true) }}" alt="recent-user" class="rounded-circle border border-2 border-success" style="width: 70px; height: 70px; object-fit: cover; box-shadow: 0 0 15px rgba(32, 201, 151, 0.3);">
                    </div>
                    <div class="bg-icon text-success"><i class="bi bi-star-fill"></i></div>
                </div>
            </div>
        @endif
    </div>

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
                            <td><i class="bi bi-calendar text-muted me-1"></i> {{ date('d M Y h:i A', strtotime($transaction->created_at)) }}</td>
                            <td><strong class="text-primary">{{ $transaction->username }}</strong><br><small class="text-muted">{{ $transaction->name }}</small></td>
                            <td>{{ $transaction->city }}</td>
                            <td>{{ $transaction->details }}</td>
                            <td class="fw-bold text-success">{{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>@lang('No transactions found')</td></tr>
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
                            <strong class="text-primary">{{ $transaction->username }}</strong>
                            <small class="text-muted d-block">{{ $transaction->name }}</small>
                        </div>
                        <span class="fw-bold text-success">{{ number_format($transaction->amount, 2) }}</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-6"><small class="text-muted">@lang('City'):</small><br><strong>{{ $transaction->city }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('Date'):</small><br><strong>{{ date('d M Y', strtotime($transaction->created_at)) }}</strong></div>
                        <div class="col-12"><small class="text-muted">{{ $transaction->details }}</small></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>@lang('No transactions found')</div>
            @endforelse
        </div>
    </div>

    @if ($transactions->hasPages())
        <div class="mt-3">{{ paginateLinks($transactions) }}</div>
    @endif
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush