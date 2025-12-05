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
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
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
    .stats-card .icon-box.orange { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); }
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

    /* Search Bar */
    .search-bar {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
    }
    [data-theme="light"] .search-bar {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .search-bar .form-control {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        color: #ffffff;
        border-radius: 8px 0 0 8px;
        padding: 10px 15px;
    }
    [data-theme="light"] .search-bar .form-control {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .search-bar .form-control::placeholder {
        color: rgba(255,255,255,0.4);
    }
    [data-theme="light"] .search-bar .form-control::placeholder {
        color: #999;
    }
    .search-bar .btn-search {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        border-radius: 0 8px 8px 0;
        padding: 10px 20px;
    }
    .search-bar .btn-withdraw {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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

    /* Modal Premium */
    .modal-premium .modal-content {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
        overflow: hidden;
    }
    [data-theme="light"] .modal-premium .modal-content {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .modal-premium .modal-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        padding: 20px;
    }
    .modal-premium .modal-header .modal-title {
        color: #ffffff;
        font-weight: 700;
    }
    .modal-premium .modal-body {
        padding: 25px;
    }
    .modal-premium .list-group-item {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.1);
        color: #ffffff;
        border-radius: 8px !important;
        margin-bottom: 8px;
    }
    [data-theme="light"] .modal-premium .list-group-item {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.05);
        color: #1a1f2e;
    }

    /* Badge Styling */
    .badge--success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge--warning {
        background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge--danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge--primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-clock-history"></i> @lang('Withdraw History')</h4>
        <p class="page-subtitle small m-0">@lang('View all your withdrawal transactions')</p>
    </div>

    <!-- Stats Grid -->
    @php
        $totalWithdraws = $withdraws->total() ?? count($withdraws);
        $pendingWithdraws = $withdraws->where('status', 2)->count();
        $approvedWithdraws = $withdraws->where('status', 1)->count();
        $rejectedWithdraws = $withdraws->where('status', 3)->count();
    @endphp
    <div class="stats-grid">
        <div class="stats-card">
            <div class="icon-box blue"><i class="bi bi-list-check"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Total Requests')</div>
                <div class="stats-value">{{ $totalWithdraws }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box orange"><i class="bi bi-hourglass-split"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Pending')</div>
                <div class="stats-value">{{ $pendingWithdraws }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box green"><i class="bi bi-check-circle"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Approved')</div>
                <div class="stats-value">{{ $approvedWithdraws }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box pink"><i class="bi bi-x-circle"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Rejected')</div>
                <div class="stats-value">{{ $rejectedWithdraws }}</div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <form class="flex-grow-1" style="max-width: 400px;">
                <div class="input-group">
                    <input class="form-control" name="search" type="search" value="{{ request()->search }}" 
                           placeholder="@lang('Search by transactions...')">
                    <button class="btn-search" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            <a class="btn-withdraw" href="{{ route('user.withdraw') }}">
                <i class="bi bi-plus-circle"></i> @lang('Withdraw Now')
            </a>
        </div>
    </div>

    <!-- Transactions Card -->
    <div class="premium-card">
        <h5 class="mb-4"><i class="bi bi-list-ul"></i> @lang('Withdrawal Transactions')</h5>

        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>@lang('Gateway')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Conversion')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdraws as $withdraw)
                        @php
                            $details = [];
                            foreach ($withdraw->withdraw_information as $key => $info) {
                                $details[] = $info;
                                if ($info->type == 'file' && @$info->value) {
                                    $details[$key]->value = route(
                                        'user.download.attachment',
                                        encrypt(getFilePath('verify') . '/' . $info->value),
                                    );
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <strong class="text-primary">{{ __(@$withdraw->method->name) }}</strong><br>
                                <small class="text-muted-custom">{{ $withdraw->trx }}</small>
                            </td>
                            <td>
                                {{ showDateTime($withdraw->created_at, 'd M Y') }}<br>
                                <small class="text-muted-custom">{{ diffForHumans($withdraw->created_at) }}</small>
                            </td>
                            <td>
                                {{ showAmount($withdraw->amount) }}<br>
                                <small class="text-danger">- {{ showAmount($withdraw->charge) }}</small><br>
                                <strong class="text-success">{{ showAmount($withdraw->amount - $withdraw->charge) }}</strong>
                            </td>
                            <td>
                                <small class="text-muted-custom">1 = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}</small><br>
                                <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</strong>
                            </td>
                            <td>
                                @php echo $withdraw->statusBadge @endphp
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary detailBtn" 
                                        data-user_data="{{ json_encode($details) }}"
                                        @if ($withdraw->status == Status::PAYMENT_REJECT) 
                                            data-admin_feedback="{{ $withdraw->admin_feedback }}" 
                                        @endif>
                                    <i class="bi bi-eye"></i> @lang('Details')
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
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
            @forelse($withdraws as $withdraw)
                @php
                    $details = [];
                    foreach ($withdraw->withdraw_information as $key => $info) {
                        $details[] = $info;
                        if ($info->type == 'file' && @$info->value) {
                            $details[$key]->value = route(
                                'user.download.attachment',
                                encrypt(getFilePath('verify') . '/' . $info->value),
                            );
                        }
                    }
                @endphp
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="card-title">{{ __(@$withdraw->method->name) }}</span><br>
                            <small class="text-muted-custom">{{ $withdraw->trx }}</small>
                        </div>
                        @php echo $withdraw->statusBadge @endphp
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Amount')</small>
                            <span class="card-value">{{ showAmount($withdraw->amount) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('After Charge')</small>
                            <span class="card-value text-success">{{ showAmount($withdraw->amount - $withdraw->charge) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Final Amount')</small>
                            <span class="card-value">{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Date')</small>
                            <span class="card-value">{{ showDateTime($withdraw->created_at, 'd M Y') }}</span>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary w-100 detailBtn" 
                            data-user_data="{{ json_encode($details) }}"
                            @if ($withdraw->status == Status::PAYMENT_REJECT) 
                                data-admin_feedback="{{ $withdraw->admin_feedback }}" 
                            @endif>
                        <i class="bi bi-eye"></i> @lang('View Details')
                    </button>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    <span class="text-muted-custom">{{ __($emptyMessage) }}</span>
                </div>
            @endforelse
        </div>

        <!-- Premium Pagination -->
        @if ($withdraws->hasPages())
            <div class="premium-pagination">
                {{-- Previous Button --}}
                @if ($withdraws->onFirstPage())
                    <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $withdraws->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($withdraws->getUrlRange(1, $withdraws->lastPage()) as $page => $url)
                    @if ($page == $withdraws->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Button --}}
                @if ($withdraws->hasMorePages())
                    <a href="{{ $withdraws->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
            <div class="text-center mt-2">
                <small class="text-muted-custom">@lang('Showing') {{ $withdraws->firstItem() }} - {{ $withdraws->lastItem() }} @lang('of') {{ $withdraws->total() }}</small>
            </div>
        @endif
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('modal')
    <div class="modal fade modal-premium" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-info-circle"></i> @lang('Withdrawal Details')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-3"></ul>
                    <div class="feedback"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')

<script>
    (function($) {
        "use strict";
        $('.detailBtn').on('click', function() {
            var modal = $('#detailModal');
            var userData = $(this).data('user_data');
            var html = ``;
            userData.forEach(element => {
                if (element.type != 'file') {
                    html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><strong>${element.name}</strong></span>
                        <span>${element.value}</span>
                    </li>`;
                } else {
                    html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><strong>${element.name}</strong></span>
                        <span><a href="${element.value}" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark"></i> @lang('Download')</a></span>
                    </li>`;
                }
            });
            modal.find('.userData').html(html);

            if ($(this).data('admin_feedback') != undefined) {
                var adminFeedback = `
                    <div class="alert alert-danger mt-3">
                        <h6 class="alert-heading"><i class="bi bi-info-circle"></i> @lang('Admin Feedback')</h6>
                        <p class="mb-0">${$(this).data('admin_feedback')}</p>
                    </div>
                `;
            } else {
                var adminFeedback = '';
            }

            modal.find('.feedback').html(adminFeedback);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
