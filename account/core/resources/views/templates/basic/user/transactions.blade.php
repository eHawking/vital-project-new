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

    /* Mobile Full Width */
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
            grid-template-columns: repeat(2, 1fr) !important;
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
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Stats Card */
    .stats-card {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    [data-theme="light"] .stats-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .stats-card .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #ffffff;
    }
    .stats-card .icon-box.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stats-card .icon-box.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .stats-card .icon-box.pink { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .stats-card .stats-info .stats-label {
        font-size: 12px;
        color: rgba(255,255,255,0.6);
        margin-bottom: 2px;
    }
    [data-theme="light"] .stats-card .stats-info .stats-label {
        color: #6c757d;
    }
    .stats-card .stats-info .stats-value {
        font-size: 18px;
        font-weight: 700;
        color: #ffffff;
    }
    [data-theme="light"] .stats-card .stats-info .stats-value {
        color: #1a1f2e;
    }

    /* Premium Card */
    .premium-card {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        padding: 25px;
    }
    [data-theme="light"] .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Filter Card */
    .filter-card {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        animation: slideDown 0.3s ease;
    }
    [data-theme="light"] .filter-card {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Form Controls */
    .form-control-custom {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        color: #ffffff;
        border-radius: 8px;
        padding: 10px 15px;
    }
    [data-theme="light"] .form-control-custom {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .form-control-custom:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        outline: none;
    }
    .form-control-custom::placeholder {
        color: rgba(255,255,255,0.4);
    }
    [data-theme="light"] .form-control-custom::placeholder {
        color: #999;
    }
    .form-label-custom {
        color: rgba(255,255,255,0.6);
        font-size: 13px;
        margin-bottom: 6px;
    }
    [data-theme="light"] .form-label-custom {
        color: #6c757d;
    }

    /* Filter Button */
    .btn-filter {
        background: rgba(128,128,128,0.1);
        border: 1px solid rgba(128,128,128,0.2);
        color: rgba(255,255,255,0.8);
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .btn-filter {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .btn-filter:hover, .btn-filter.active {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--color-primary);
        color: var(--color-primary);
    }
    .btn-apply {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        border-radius: 8px;
        padding: 10px 20px;
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
        font-size: 12px;
        text-transform: uppercase;
    }
    [data-theme="light"] .table-custom th {
        color: #6c757d !important;
    }
    .table-custom td {
        color: #ffffff !important;
        padding: 15px;
        border-bottom: 1px solid rgba(128,128,128,0.05) !important;
        vertical-align: middle;
    }
    [data-theme="light"] .table-custom td {
        color: #1a1f2e !important;
    }
    .table-custom tbody tr:hover {
        background: rgba(128,128,128,0.03) !important;
    }
    .table-custom .text-muted-custom {
        color: rgba(255,255,255,0.5) !important;
    }
    [data-theme="light"] .table-custom .text-muted-custom {
        color: #6c757d !important;
    }

    /* TRX Code */
    .trx-code {
        background: rgba(139, 92, 246, 0.15);
        color: var(--color-primary);
        padding: 5px 10px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
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
    .mobile-card-item .card-title {
        color: #ffffff;
        font-weight: 600;
    }
    [data-theme="light"] .mobile-card-item .card-title {
        color: #1a1f2e;
    }
    .mobile-card-item .text-muted-custom {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .mobile-card-item .text-muted-custom {
        color: #6c757d;
    }
    .mobile-card-item .card-value {
        color: #ffffff;
    }
    [data-theme="light"] .mobile-card-item .card-value {
        color: #1a1f2e;
    }

    /* Badge Styling */
    .badge-details {
        background: rgba(139, 92, 246, 0.15);
        color: var(--color-primary);
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }

    /* Premium Pagination */
    .premium-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .premium-pagination .page-btn {
        min-width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid rgba(128,128,128,0.2);
        background: #1a1f2e;
        color: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .premium-pagination .page-btn {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
        color: #6c757d;
    }
    .premium-pagination .page-btn:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--color-primary);
        color: var(--color-primary);
    }
    .premium-pagination .page-btn.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
    }
    .premium-pagination .page-btn.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-arrow-left-right"></i> @lang('Transactions')</h4>
        <p class="page-subtitle small m-0">@lang('View all your transaction history')</p>
    </div>

    <!-- Stats Grid -->
    @php
        $totalTransactions = $transactions->total() ?? count($transactions);
        $creditTransactions = $transactions->where('trx_type', '+')->count();
        $debitTransactions = $transactions->where('trx_type', '-')->count();
    @endphp
    <div class="stats-grid">
        <div class="stats-card">
            <div class="icon-box blue"><i class="bi bi-list-check"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Total Transactions')</div>
                <div class="stats-value">{{ $totalTransactions }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box green"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Credit (In)')</div>
                <div class="stats-value">{{ $creditTransactions }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box pink"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Debit (Out)')</div>
                <div class="stats-value">{{ $debitTransactions }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Toggle -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn-filter showFilterBtn" type="button">
            <i class="bi bi-funnel"></i> @lang('Filter Transactions')
        </button>
    </div>

    <!-- Filter Card -->
    <div class="filter-card responsive-filter-card" style="display: none;">
        <h6 class="mb-3"><i class="bi bi-funnel-fill"></i> @lang('Filter Options')</h6>
        <form>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label-custom">@lang('Transaction Number')</label>
                    <input class="form-control form-control-custom" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search TRX...')">
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">@lang('Type')</label>
                    <select class="form-control form-control-custom" name="trx_type">
                        <option value="">@lang('All Types')</option>
                        <option value="+" @selected(request()->trx_type == '+')>@lang('Credit (+)')</option>
                        <option value="-" @selected(request()->trx_type == '-')>@lang('Debit (-)')</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">@lang('Remark')</label>
                    <select class="form-control form-control-custom" name="remark">
                        <option value="">@lang('All Remarks')</option>
                        @foreach ($remarks as $remark)
                            <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn-apply w-100" type="submit">
                        <i class="bi bi-funnel"></i> @lang('Apply')
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions Card -->
    <div class="premium-card">
        <h5 class="mb-4"><i class="bi bi-list-ul"></i> @lang('Transaction History')</h5>

        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>@lang('TRX')</th>
                        <th>@lang('Date & Time')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Balance')</th>
                        <th>@lang('Details')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td>
                                <span class="trx-code">{{ $trx->trx }}</span>
                            </td>
                            <td>
                                {{ showDateTime($trx->created_at, 'd M Y, h:i A') }}<br>
                                <small class="text-muted-custom">{{ diffForHumans($trx->created_at) }}</small>
                            </td>
                            <td>
                                <span class="fw-bold @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                                    {{ $trx->trx_type == '+' ? '+' : '-' }} {{ showAmount($trx->amount) }}
                                </span>
                                @if($trx->charge > 0)
                                    <br><small class="text-warning"><i class="bi bi-info-circle"></i> Charge: {{ showAmount($trx->charge) }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ showAmount($trx->post_balance) }}</strong>
                            </td>
                            <td>
                                <span class="badge-details">{{ __($trx->details) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                <span class="text-muted-custom">{{ __($emptyMessage) }}</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none">
            @forelse($transactions as $trx)
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="trx-code">{{ $trx->trx }}</span>
                        </div>
                        <span class="fw-bold @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                            {{ $trx->trx_type == '+' ? '+' : '-' }} {{ showAmount($trx->amount) }}
                        </span>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Balance')</small>
                            <span class="card-value fw-bold">{{ showAmount($trx->post_balance) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Date')</small>
                            <span class="card-value">{{ showDateTime($trx->created_at, 'd M Y') }}</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge-details">{{ __($trx->details) }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    <span class="text-muted-custom">{{ __($emptyMessage) }}</span>
                </div>
            @endforelse
        </div>

        <!-- Premium Pagination -->
        @if ($transactions->hasPages())
            <div class="premium-pagination">
                {{-- Previous Button --}}
                @if ($transactions->onFirstPage())
                    <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if ($page == $transactions->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Button --}}
                @if ($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
            <div class="text-center mt-2">
                <small class="text-muted-custom">@lang('Showing') {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} @lang('of') {{ $transactions->total() }}</small>
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

<script>
    (function($) {
        "use strict";
        $('.showFilterBtn').on('click', function() {
            $('.responsive-filter-card').slideToggle(300);
            $(this).toggleClass('active');
            $(this).find('i').toggleClass('bi-funnel bi-funnel-fill');
        });
    })(jQuery);
</script>
@endpush
