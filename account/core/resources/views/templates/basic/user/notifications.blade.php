@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="row mb-4">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="text-white m-0">@lang('Live Notifications')</h4>
                <p class="text-white-50 small m-0">@lang('Top performers and recent activities')</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Top Seller Card --}}
    @if($seller_user)
        <div class="col-lg-4 col-md-6">
            <div class="premium-card h-100 position-relative overflow-hidden" style="border: 1px solid rgba(255, 215, 0, 0.3);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1 z-index-1">
                            <h6 class="text-warning mb-2">@lang('Top Seller') 🏆</h6>
                            <h4 class="text-white text-uppercase mb-1">{{ $seller_user->username }}</h4>
                            <p class="text-white-50 mb-2 small">{{ $seller_user->firstname . ' ' . $seller_user->lastname }}</p>
                            <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                <i class="bi bi-bag-check-fill text-warning me-2"></i>
                                <span class="text-warning fw-bold">{{ $top_seller->order_count }} @lang('Sales')</span>
                            </div>
                        </div>
                        <div class="user-thumb position-relative z-index-1">
                            <img id="seller-output" src="{{ getImage('assets/images/user/profile/'. $seller_user->image, '350x300', true) }}" alt="top-seller" class="img-fluid rounded-circle border border-2 border-warning" style="width: 80px; height: 80px; object-fit: cover; box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);">
                        </div>
                    </div>
                    <!-- Decorative Background Element -->
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; line-height: 1; transform: translate(20%, -20%); color: #ffd700;">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Top Customer Card --}}
    @if($customer_user)
        <div class="col-lg-4 col-md-6">
            <div class="premium-card h-100 position-relative overflow-hidden" style="border: 1px solid rgba(13, 202, 240, 0.3);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1 z-index-1">
                            <h6 class="text-info mb-2">@lang('Top Customer') 👑</h6>
                            <h4 class="text-white text-uppercase mb-1">{{ $customer_user->username }}</h4>
                            <p class="text-white-50 mb-2 small">{{ $customer_user->firstname . ' ' . $customer_user->lastname }}</p>
                            <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-info bg-opacity-10 border border-info border-opacity-25">
                                <i class="bi bi-cart-check-fill text-info me-2"></i>
                                <span class="text-info fw-bold">{{ $top_customer->order_count }} @lang('Purchases')</span>
                            </div>
                        </div>
                        <div class="user-thumb position-relative z-index-1">
                            <img id="customer-output" src="{{ getImage('assets/images/user/profile/'. $customer_user->image, '350x300', true) }}" alt="top-customer" class="img-fluid rounded-circle border border-2 border-info" style="width: 80px; height: 80px; object-fit: cover; box-shadow: 0 0 15px rgba(13, 202, 240, 0.3);">
                        </div>
                    </div>
                    <!-- Decorative Background Element -->
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; line-height: 1; transform: translate(20%, -20%); color: #0dcaf0;">
                        <i class="bi bi-crown"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Recent User Card --}}
    @if($recent_entry)
        <div class="col-lg-4 col-md-6">
            <div class="premium-card h-100 position-relative overflow-hidden" style="border: 1px solid rgba(32, 201, 151, 0.3);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1 z-index-1">
                            <h6 class="text-success mb-2">@lang('Recent User') ⭐</h6>
                            <h4 class="text-white text-uppercase mb-1">{{ $recent_entry->username }}</h4>
                            <p class="text-white-50 mb-1 small">{{ $recent_entry->firstname . ' ' . $recent_entry->lastname }}</p>
                            <div class="d-flex flex-column gap-1">
                                <small class="text-white-50"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $recent_entry->city }}</small>
                                <small class="text-white-50"><i class="bi bi-calendar-event text-success me-1"></i> {{ date('j F Y', strtotime($recent_entry->created_at)) }}</small>
                            </div>
                        </div>
                        <div class="user-thumb position-relative z-index-1">
                            <img id="recent-output" src="{{ getImage('assets/images/user/profile/'. $recent_entry->image, '350x300', true) }}" alt="recent-user" class="img-fluid rounded-circle border border-2 border-success" style="width: 80px; height: 80px; object-fit: cover; box-shadow: 0 0 15px rgba(32, 201, 151, 0.3);">
                        </div>
                    </div>
                    <!-- Decorative Background Element -->
                    <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 8rem; line-height: 1; transform: translate(20%, -20%); color: #20c997;">
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="premium-card mb-4">
    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
        <h5 class="title text-white m-0"><i class="bi bi-clock-history"></i> @lang('Transactions in the Last 7 Days')</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table transection-table-2">
                <thead>
                    <tr>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Date & Time')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('User Details')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('City')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Transaction Details')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr style="background: rgba(255,255,255,0.05);">
                            <td data-label="@lang('Date & Time')" class="text-white">
                                <i class="las la-calendar text-white-50"></i> {{ date('d M Y h:i A', strtotime($transaction->created_at)) }}
                            </td>
                            <td data-label="@lang('User Details')" class="text-white">
                                <div class="fw-bold text-info">{{ $transaction->username }}</div>
                                <div class="small text-white-50">{{ $transaction->name }}</div>
                            </td>
                            <td data-label="@lang('City')" class="text-white">{{ $transaction->city }}</td>
                            <td data-label="@lang('Transaction Details')" class="text-white">{{ $transaction->details }}</td>
                            <td data-label="@lang('Amount')" class="fw-bold text-success">{{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-white-50">@lang('No transactions found in the last 7 days')!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($transactions->hasPages())
    <div class="mt-4">
        {{ paginateLinks($transactions) }}
    </div>
@endif

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush