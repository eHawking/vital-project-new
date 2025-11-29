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
                <h2 class="page-title"><i class="bi bi-arrow-left-right"></i> @lang('Transactions')</h2>
                <p class="page-subtitle">@lang('View all your transaction history')</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Filter Toggle -->
            <div class="show-filter mb-3 text-end">
                <button class="btn btn-primary showFilterBtn" type="button">
                    <i class="bi bi-funnel"></i> @lang('Filter Transactions')
                </button>
            </div>
            
            <!-- Filter Card -->
            <div class="premium-card responsive-filter-card mb-4" style="display: none;">
                <div class="dashboard-item-header mb-3">
                    <h5 class="title text-white"><i class="bi bi-funnel-fill"></i> @lang('Filter Options')</h5>
                </div>
                <form>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-white-50"><i class="bi bi-search"></i> @lang('Transaction Number')</label>
                            <input class="form-control bg-transparent text-white border-secondary" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search TRX...')">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white-50"><i class="bi bi-plus-slash-minus"></i> @lang('Type')</label>
                            <select class="form-control select2 bg-transparent text-white border-secondary" name="trx_type" data-minimum-results-for-search="-1">
                                <option value="" class="text-dark">@lang('All Types')</option>
                                <option value="+" class="text-dark" @selected(request()->trx_type == '+')>@lang('➕ Plus (Credit)')</option>
                                <option value="-" class="text-dark" @selected(request()->trx_type == '-')>@lang('➖ Minus (Debit)')</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white-50"><i class="bi bi-tag"></i> @lang('Remark')</label>
                            <select class="form-control select2 bg-transparent text-white border-secondary" name="remark" data-minimum-results-for-search="-1">
                                <option value="" class="text-dark">@lang('All Remarks')</option>
                                @foreach ($remarks as $remark)
                                    <option value="{{ $remark->remark }}" class="text-dark" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit" style="background: var(--grad-primary); border: none;">
                                <i class="bi bi-funnel"></i> @lang('Apply')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Transactions Table -->
            <div class="premium-card mb-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title text-white"><i class="bi bi-list-ul"></i> @lang('Transaction History')</h4>
                </div>
                <div class="table-responsive">
                    <table class="transection-table-2">
                        <thead>
                            <tr>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-hash"></i> @lang('TRX')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-calendar-event"></i> @lang('Date & Time')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-cash-coin"></i> @lang('Amount')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-wallet2"></i> @lang('Balance')</th>
                                <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-info-circle"></i> @lang('Details')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr style="background: rgba(255,255,255,0.05);">
                                    <td data-label="@lang('TRX')" class="text-white">
                                        <code class="trx-code text-info bg-transparent border border-info">{{ $trx->trx }}</code>
                                    </td>

                                    <td data-label="@lang('Date & Time')" class="text-white">
                                        <small>{{ showDateTime($trx->created_at, 'd M Y, h:i A') }}</small><br>
                                        <small class="text-white-50">{{ diffForHumans($trx->created_at) }}</small>
                                    </td>

                                    <td data-label="@lang('Amount')" class="text-white">
                                        <div>
                                            <span class="fw-bold @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                                                {{ $trx->trx_type == '+' ? '+' : '-' }} {{ showAmount($trx->amount) }}
                                            </span>
                                            @if($trx->charge > 0)
                                                <br><small class="text-warning"><i class="bi bi-info-circle"></i> Charge: {{ showAmount($trx->charge) }}</small>
                                            @endif
                                        </div>
                                    </td>

                                    <td data-label="@lang('Balance')" class="text-white">
                                        <strong>{{ showAmount($trx->post_balance) }}</strong>
                                    </td>

                                    <td data-label="@lang('Details')" class="text-white">
                                        <span class="badge badge--primary" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);">{{ __($trx->details) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-white-50">
                                        <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                        {{ __($emptyMessage) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if ($transactions->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    {{ paginateLinks($transactions) }}
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
