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
                <h2 class="page-title">@lang('Live Notifications')</h2>
                <p class="page-subtitle">@lang('Top performers and recent activities')</p>
            </div>
        </div>
    </div>
</div>

<div class="container">
<div class="row mb-4">
    {{-- Top Seller Card --}}
    @if($seller_user)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-1">
                <div class="dashboard-item-header d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="title mb-2">@lang('Top Seller') 🏆</h6>
                        <h4 class="ammount theme-two text-uppercase mb-1">{{ $seller_user->username }}</h4>
                        <p class="text-white mb-1">{{ $seller_user->firstname . ' ' . $seller_user->lastname }}</p>
                        <h5 class="ammount"><i class="bi bi-bag-check-fill"></i> {{ $top_seller->order_count }} @lang('Sales')</h5>
                    </div>
                    <div class="user-thumb">
                        <img id="seller-output" src="{{ getImage('assets/images/user/profile/'. $seller_user->image, '350x300', true) }}" alt="top-seller" class="img-fluid rounded-circle" style="width: 90px; height: 90px; border: 4px solid rgba(255, 255, 255, 0.3); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);">
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Top Customer Card --}}
    @if($customer_user)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-2">
                <div class="dashboard-item-header d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="title mb-2">@lang('Top Customer') 👑</h6>
                        <h4 class="ammount theme-two text-uppercase mb-1">{{ $customer_user->username }}</h4>
                        <p class="text-white mb-1">{{ $customer_user->firstname . ' ' . $customer_user->lastname }}</p>
                        <h5 class="ammount"><i class="bi bi-cart-check-fill"></i> {{ $top_customer->order_count }} @lang('Purchases')</h5>
                    </div>
                    <div class="user-thumb">
                        <img id="customer-output" src="{{ getImage('assets/images/user/profile/'. $customer_user->image, '350x300', true) }}" alt="top-customer" class="img-fluid rounded-circle" style="width: 90px; height: 90px; border: 4px solid rgba(255, 255, 255, 0.3); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);">
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Recent User Card --}}
    @if($recent_entry)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="dashboard-item gradient-card-3">
                <div class="dashboard-item-header d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="title mb-2">@lang('Recent User') ⭐</h6>
                        <h4 class="ammount theme-two text-uppercase mb-1">{{ $recent_entry->username }}</h4>
                        <p class="text-white mb-1">{{ $recent_entry->firstname . ' ' . $recent_entry->lastname }}</p>
                        <p class="text-white mb-1"><i class="bi bi-geo-alt-fill"></i> {{ $recent_entry->city }}</p>
                        <h6 class="ammount"><i class="bi bi-calendar-event"></i> {{ date('j F Y', strtotime($recent_entry->created_at)) }}</h6>
                    </div>
                    <div class="user-thumb">
                        <img id="recent-output" src="{{ getImage('assets/images/user/profile/'. $recent_entry->image, '350x300', true) }}" alt="recent-user" class="img-fluid rounded-circle" style="width: 90px; height: 90px; border: 4px solid rgba(255, 255, 255, 0.3); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);">
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-item mb-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title"><i class="bi bi-clock-history"></i> @lang('Transactions in the Last 7 Days')</h4>
                </div>
                <div class="table-responsive">
                    <table class="transection-table-2">
    <thead>
        <tr>
            <th scope="col">@lang('Date & Time')</th>
            <th scope="col">@lang('User Details')</th>
            <th scope="col">@lang('City')</th>
            <th scope="col">@lang('Transaction Details')</th>
            <th scope="col">@lang('Amount')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $transaction)
            <tr style="margin-bottom:20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <td data-label="@lang('Date & Time')">
                    <i class="las la-calendar"></i> {{ date('d M Y h:i A', strtotime($transaction->created_at)) }}
                </td>
                <td data-label="@lang('User Details')">
                    <div>{{ $transaction->username }}</div>
                    <div>{{ $transaction->name }}</div>
                </td>
                <td data-label="@lang('City')">{{ $transaction->city }}</td>
                <td data-label="@lang('Transaction Details')">{{ $transaction->details }}</td>
                <td data-label="@lang('Amount')">{{ number_format($transaction->amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">@lang('No transactions found in the last 7 days')!</td>
            </tr>
        @endforelse
    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($transactions->hasPages())
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    {{ paginateLinks($transactions) }}
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

<style>
/* Notifications Page Custom Styles */
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

/* User Cards Enhanced Styling */
.user-thumb {
    flex-shrink: 0;
}

.user-thumb img {
    transition: all 0.3s ease;
}

.dashboard-item:hover .user-thumb img {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5) !important;
}

/* Transaction Table Styling */
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
}

.transection-table-2 tbody td:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    font-weight: 700;
    color: var(--accent-blue);
}

.transection-table-2 tbody td i {
    color: var(--accent-cyan);
    margin-right: 5px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
    }
    
    .user-thumb img {
        width: 70px !important;
        height: 70px !important;
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