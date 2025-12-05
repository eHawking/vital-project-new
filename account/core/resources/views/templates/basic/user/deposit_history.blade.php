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
    .stats-card .icon-box.orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .stats-card .icon-box.cyan { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
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

    /* Search Bar */
    .search-box {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .search-input {
        background: #242938;
        border: 1px solid rgba(128,128,128,0.2);
        color: #ffffff;
        border-radius: 10px;
        padding: 10px 15px;
        min-width: 200px;
    }
    [data-theme="light"] .search-input {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.1);
        color: #1a1f2e;
    }
    .search-input:focus {
        border-color: var(--color-primary);
        outline: none;
    }
    .search-input::placeholder {
        color: rgba(255,255,255,0.4);
    }
    [data-theme="light"] .search-input::placeholder {
        color: #999;
    }
    .btn-search {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border: none;
        color: #ffffff;
        border-radius: 10px;
        padding: 10px 20px;
    }
    .btn-deposit {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: #ffffff;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        text-decoration: none;
    }
    .btn-deposit:hover {
        color: #ffffff;
        transform: translateY(-2px);
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
        color: rgba(255,255,255,0.5);
        font-family: monospace;
        font-size: 12px;
    }
    [data-theme="light"] .trx-code {
        color: #6c757d;
    }

    /* Gateway Name */
    .gateway-name {
        color: var(--color-primary);
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

    /* Status Badges */
    .badge-pending {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-success {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-danger {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Detail Button */
    .btn-detail {
        background: rgba(128,128,128,0.1);
        border: 1px solid rgba(128,128,128,0.2);
        color: rgba(255,255,255,0.7);
        border-radius: 8px;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }
    [data-theme="light"] .btn-detail {
        background: #f8f9fa;
        color: #6c757d;
    }
    .btn-detail:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--color-primary);
        color: var(--color-primary);
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

    /* Premium Modal */
    .premium-modal .modal-content {
        background: #1a1f2e;
        border: 1px solid rgba(128,128,128,0.2);
        border-radius: 16px;
    }
    [data-theme="light"] .premium-modal .modal-content {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .premium-modal .modal-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        border-radius: 16px 16px 0 0;
        padding: 20px;
        border: none;
    }
    .premium-modal .modal-title {
        color: #ffffff;
        font-weight: 600;
    }
    .premium-modal .modal-body {
        padding: 25px;
    }
    .premium-modal .list-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(128,128,128,0.1);
        color: #ffffff;
    }
    [data-theme="light"] .premium-modal .list-item {
        color: #1a1f2e;
    }
    .premium-modal .list-item:last-child {
        border-bottom: none;
    }
    .premium-modal .list-item .label {
        color: rgba(255,255,255,0.6);
    }
    [data-theme="light"] .premium-modal .list-item .label {
        color: #6c757d;
    }
</style>

<div class="container-fluid px-4 py-4 inner-dashboard-container">
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="m-0"><i class="bi bi-wallet2"></i> @lang('Deposit History')</h4>
        <p class="page-subtitle small m-0">@lang('View all your deposit transactions')</p>
    </div>

    <!-- Stats Grid -->
    @php
        $totalDeposits = $deposits->total() ?? count($deposits);
        $pendingDeposits = $deposits->where('status', 2)->count();
        $successDeposits = $deposits->where('status', 1)->count();
        $rejectedDeposits = $deposits->where('status', 3)->count();
    @endphp
    <div class="stats-grid">
        <div class="stats-card">
            <div class="icon-box blue"><i class="bi bi-list-check"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Total Deposits')</div>
                <div class="stats-value">{{ $totalDeposits }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box orange"><i class="bi bi-clock-history"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Pending')</div>
                <div class="stats-value">{{ $pendingDeposits }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box green"><i class="bi bi-check-circle"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Successful')</div>
                <div class="stats-value">{{ $successDeposits }}</div>
            </div>
        </div>
        <div class="stats-card">
            <div class="icon-box cyan"><i class="bi bi-x-circle"></i></div>
            <div class="stats-info">
                <div class="stats-label">@lang('Rejected')</div>
                <div class="stats-value">{{ $rejectedDeposits }}</div>
            </div>
        </div>
    </div>

    <!-- Search & Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <form class="search-box">
            <input class="search-input" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search TRX...')">
            <button class="btn-search" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
        <a class="btn-deposit" href="{{ route('user.deposit.index') }}">
            <i class="bi bi-plus-circle"></i> @lang('Deposit Now')
        </a>
    </div>

    <!-- Deposits Card -->
    <div class="premium-card">
        <h5 class="mb-4"><i class="bi bi-list-ul"></i> @lang('Deposit Transactions')</h5>

        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>@lang('Gateway | TRX')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Conversion')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deposits as $deposit)
                        <tr>
                            <td>
                                <span class="gateway-name">
                                    @if ($deposit->method_code < 5000)
                                        {{ __(@$deposit->gateway->name) }}
                                    @else
                                        @lang('Google Pay')
                                    @endif
                                </span>
                                <br>
                                <small class="trx-code">{{ $deposit->trx }}</small>
                            </td>
                            <td>
                                {{ showDateTime($deposit->created_at, 'd M Y') }}<br>
                                <small class="text-muted-custom">{{ diffForHumans($deposit->created_at) }}</small>
                            </td>
                            <td>
                                {{ showAmount($deposit->amount) }} + <span class="text-danger">{{ showAmount($deposit->charge) }}</span>
                                <br>
                                <strong class="text-success">{{ showAmount($deposit->amount + $deposit->charge) }}</strong>
                            </td>
                            <td>
                                1 = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->method_currency) }}
                                <br>
                                <strong>{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</strong>
                            </td>
                            <td>
                                @php echo $deposit->statusBadge @endphp
                            </td>
                            @php
                                $details = [];
                                if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                                    foreach (@$deposit->detail ?? [] as $key => $info) {
                                        $details[] = $info;
                                        if (@$info->type == 'file' && @$info->value) {
                                            @$details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . @$info->value));
                                        }
                                    }
                                }
                            @endphp
                            <td>
                                @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                    <button class="btn-detail detailBtn" data-info="{{ json_encode($details) }}"
                                        @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                        <i class="bi bi-eye"></i>
                                    </button>
                                @else
                                    <span class="badge-success"><i class="bi bi-check"></i> @lang('Auto')</span>
                                @endif
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
            @forelse($deposits as $deposit)
                @php
                    $details = [];
                    if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                        foreach (@$deposit->detail ?? [] as $key => $info) {
                            $details[] = $info;
                            if (@$info->type == 'file' && @$info->value) {
                                @$details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . @$info->value));
                            }
                        }
                    }
                @endphp
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="gateway-name">
                                @if ($deposit->method_code < 5000)
                                    {{ __(@$deposit->gateway->name) }}
                                @else
                                    @lang('Google Pay')
                                @endif
                            </span>
                            <br>
                            <small class="trx-code">{{ $deposit->trx }}</small>
                        </div>
                        @php echo $deposit->statusBadge @endphp
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Amount')</small>
                            <span class="card-value fw-bold text-success">{{ showAmount($deposit->amount + $deposit->charge) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Final')</small>
                            <span class="card-value fw-bold">{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Date')</small>
                            <span class="card-value">{{ showDateTime($deposit->created_at, 'd M Y') }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted-custom d-block">@lang('Charge')</small>
                            <span class="card-value text-danger">{{ showAmount($deposit->charge) }}</span>
                        </div>
                    </div>
                    @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                        <button class="btn-detail w-100 detailBtn" data-info="{{ json_encode($details) }}"
                            @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                            <i class="bi bi-eye"></i> @lang('View Details')
                        </button>
                    @endif
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                    <span class="text-muted-custom">{{ __($emptyMessage) }}</span>
                </div>
            @endforelse
        </div>

        <!-- Premium Pagination -->
        @if ($deposits->hasPages())
            <div class="premium-pagination">
                @if ($deposits->onFirstPage())
                    <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $deposits->previousPageUrl() }}" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif

                @foreach ($deposits->getUrlRange(1, $deposits->lastPage()) as $page => $url)
                    @if ($page == $deposits->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($deposits->hasMorePages())
                    <a href="{{ $deposits->nextPageUrl() }}" class="page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
            <div class="text-center mt-2">
                <small class="text-muted-custom">@lang('Showing') {{ $deposits->firstItem() }} - {{ $deposits->lastItem() }} @lang('of') {{ $deposits->total() }}</small>
            </div>
        @endif
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('modal')
    <!-- Premium Detail Modal -->
    <div class="modal fade premium-modal" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-info-circle"></i> @lang('Deposit Details')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="userData mb-3"></div>
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

            var userData = $(this).data('info');
            var html = '';
            if (userData) {
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <div class="list-item">
                            <span class="label">${element.name}</span>
                            <span>${element.value}</span>
                        </div>`;
                    } else {
                        html += `
                        <div class="list-item">
                            <span class="label">${element.name}</span>
                            <a href="${element.value}" class="text-info"><i class="bi bi-file-earmark"></i> @lang('Attachment')</a>
                        </div>`;
                    }
                });
            }

            modal.find('.userData').html(html);

            if ($(this).data('admin_feedback') != undefined) {
                var adminFeedback = `
                    <div class="mt-3 p-3 rounded" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3);">
                        <strong class="text-danger"><i class="bi bi-exclamation-triangle"></i> @lang('Admin Feedback')</strong>
                        <p class="mb-0 mt-2" style="color: rgba(255,255,255,0.7);">${$(this).data('admin_feedback')}</p>
                    </div>
                `;
            } else {
                var adminFeedback = '';
            }

            modal.find('.feedback').html(adminFeedback);
            modal.modal('show');
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

    })(jQuery);
</script>
@endpush
