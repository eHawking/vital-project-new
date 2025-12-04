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
    .filter-input { background: rgba(128,128,128,0.05) !important; border: 1px solid rgba(128,128,128,0.2) !important; color: var(--text-primary) !important; border-radius: 10px !important; padding: 10px 15px !important; }
</style>

<div class="container-fluid px-4 py-3 inner-dashboard-container">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="m-0"><i class="bi bi-arrow-left-right"></i> @lang('Transactions')</h4>
            <p class="text-muted small m-0">@lang('View all your transaction history')</p>
        </div>
        <button class="btn btn-sm showFilterBtn" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border: none; color: #fff; border-radius: 10px; padding: 8px 16px;">
            <i class="bi bi-funnel"></i> @lang('Filter')
        </button>
    </div>
            
    <!-- Filter Card -->
    <div class="premium-card responsive-filter-card mb-4" style="display: none;">
        <h6 class="mb-3"><i class="bi bi-funnel-fill"></i> @lang('Filter Options')</h6>
        <form>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-muted small text-uppercase">@lang('Transaction Number')</label>
                    <input class="form-control filter-input" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search TRX...')">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase">@lang('Type')</label>
                    <select class="form-control filter-input" name="trx_type">
                        <option value="">@lang('All Types')</option>
                        <option value="+" @selected(request()->trx_type == '+')>@lang('➕ Credit')</option>
                        <option value="-" @selected(request()->trx_type == '-')>@lang('➖ Debit')</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase">@lang('Remark')</label>
                    <select class="form-control filter-input" name="remark">
                        <option value="">@lang('All Remarks')</option>
                        @foreach ($remarks as $remark)
                            <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn w-100" type="submit" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border: none; color: #fff; border-radius: 10px;">
                        <i class="bi bi-funnel"></i> @lang('Apply')
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="premium-card mb-4">
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
                            <td><code class="text-info" style="background: rgba(13,202,240,0.1); padding: 5px 10px; border-radius: 5px;">{{ $trx->trx }}</code></td>
                            <td><small>{{ showDateTime($trx->created_at, 'd M Y, h:i A') }}</small><br><small class="text-muted">{{ diffForHumans($trx->created_at) }}</small></td>
                            <td>
                                <span class="fw-bold @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                                    {{ $trx->trx_type == '+' ? '+' : '-' }} {{ showAmount($trx->amount) }}
                                </span>
                                @if($trx->charge > 0)<br><small class="text-warning">Charge: {{ showAmount($trx->charge) }}</small>@endif
                            </td>
                            <td><strong>{{ showAmount($trx->post_balance) }}</strong></td>
                            <td><span class="badge" style="background: rgba(128,128,128,0.1); color: var(--text-primary);">{{ __($trx->details) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>{{ __($emptyMessage) }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none">
            @forelse($transactions as $trx)
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <code class="text-info small" style="background: rgba(13,202,240,0.1); padding: 3px 8px; border-radius: 5px;">{{ $trx->trx }}</code>
                        <span class="fw-bold @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                            {{ $trx->trx_type == '+' ? '+' : '-' }} {{ showAmount($trx->amount) }}
                        </span>
                    </div>
                    <div class="row g-2">
                        <div class="col-6"><small class="text-muted">@lang('Date'):</small><br><strong>{{ showDateTime($trx->created_at, 'd M Y') }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('Balance'):</small><br><strong>{{ showAmount($trx->post_balance) }}</strong></div>
                        <div class="col-12"><small class="text-muted">{{ __($trx->details) }}</small></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>{{ __($emptyMessage) }}</div>
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

<style>
/* Transactions Page Custom Styles */
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

/* TRX Code Styling */
.trx-code {
    background: rgba(102, 126, 234, 0.1);
    padding: 6px 10px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    font-weight: 600;
    color: var(--accent-blue);
}

/* Badge Styling */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge--primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
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
}

.transection-table-2 tbody td:last-child {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

.select2-container {
    width: 100% !important;
}

/* Filter Card */
.responsive-filter-card {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

<script>
    (function($) {
        "use strict";
        $('.showFilterBtn').on('click', function() {
            $('.responsive-filter-card').slideToggle(300);
            $(this).find('i').toggleClass('bi-funnel bi-funnel-fill');
        });
    })(jQuery);
</script>
@endpush
