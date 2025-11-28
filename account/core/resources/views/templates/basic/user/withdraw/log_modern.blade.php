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
                <h2 class="page-title"><i class="bi bi-clock-history"></i> @lang('Withdraw History')</h2>
                <p class="page-subtitle">@lang('View all your withdrawal transactions')</p>
            </div>
        </div>
    </div>

    <!-- Search & Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-item">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <form class="flex-grow-1" style="max-width: 400px;">
                        <div class="input-group">
                            <input class="form-control" name="search" type="search" value="{{ request()->search }}" 
                                   placeholder="@lang('Search by transactions...')">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <a class="btn btn-success" href="{{ route('user.withdraw') }}">
                        <i class="bi bi-plus-circle"></i> @lang('Withdraw Now')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Table -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-item mb-4">
                <div class="dashboard-item-header mb-3">
                    <h4 class="title"><i class="bi bi-list-ul"></i> @lang('Withdrawal Transactions')</h4>
                </div>
                <div class="table-responsive">
                    <table class="transection-table-2">
                        <thead>
                            <tr>
                                <th><i class="bi bi-bank"></i> @lang('Gateway')</th>
                                <th><i class="bi bi-calendar-event"></i> @lang('Initiated')</th>
                                <th><i class="bi bi-cash-stack"></i> @lang('Amount')</th>
                                <th><i class="bi bi-arrow-left-right"></i> @lang('Conversion')</th>
                                <th><i class="bi bi-check-circle"></i> @lang('Status')</th>
                                <th><i class="bi bi-eye"></i> @lang('Action')</th>
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
                                    <td data-label="@lang('Gateway')">
                                        <div>
                                            <strong class="text-primary">{{ __(@$withdraw->method->name) }}</strong><br>
                                            <small class="text-muted">{{ $withdraw->trx }}</small>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Initiated')">
                                        <div>
                                            <small>{{ showDateTime($withdraw->created_at, 'd M Y') }}</small><br>
                                            <small class="text-muted">{{ diffForHumans($withdraw->created_at) }}</small>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Amount')">
                                        <div>
                                            {{ showAmount($withdraw->amount) }}<br>
                                            <small class="text-danger">- {{ showAmount($withdraw->charge) }}</small><br>
                                            <strong class="text-success">{{ showAmount($withdraw->amount - $withdraw->charge) }}</strong>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Conversion')">
                                        <div>
                                            <small>1 = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}</small><br>
                                            <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</strong>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Status')">
                                        @php echo $withdraw->statusBadge @endphp
                                    </td>
                                    <td data-label="@lang('Action')">
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
                                    <td colspan="6" class="text-center">
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

    @if ($withdraws->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="mt-4">
                    {{ paginateLinks($withdraws) }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('modal')
    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modern-modal">
                <div class="modal-header gradient-header">
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

<style>
/* Withdraw History Page Custom Styles */
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

/* Search Bar Enhancement */
.input-group .form-control {
    border-radius: 10px 0 0 10px;
    border: 2px solid rgba(102, 126, 234, 0.2);
    padding: 12px 20px;
}

.input-group .form-control:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group .btn {
    border-radius: 0 10px 10px 0;
    padding: 12px 20px;
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

.text-primary {
    color: var(--accent-blue) !important;
}

/* Modern Modal */
.modern-modal .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.gradient-header {
    background: var(--gradient-purple-blue);
    color: #ffffff;
    padding: 20px;
    border: none;
}

.gradient-header .modal-title {
    font-weight: 700;
    font-size: 18px;
}

.list-group-item {
    background: var(--card-bg);
    border-color: rgba(0, 0, 0, 0.1);
    color: var(--text-primary);
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
}
</style>

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
                    <div class="alert alert-info">
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

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    })(jQuery);
</script>
@endpush
