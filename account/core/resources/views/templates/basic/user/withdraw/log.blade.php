@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<style>
    @media (max-width: 768px) {
        .inner-dashboard-container { padding: 10px !important; }
        .premium-card { border-radius: 12px !important; }
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions form { width: 100%; }
    }
    .table-custom th { background: rgba(128,128,128,0.05) !important; color: var(--text-muted) !important; font-weight: 600; padding: 12px 15px; border-bottom: 1px solid rgba(128,128,128,0.1) !important; }
    .table-custom td { padding: 12px 15px; border-bottom: 1px solid rgba(128,128,128,0.05) !important; color: var(--text-primary) !important; }
    .table-custom tbody tr:hover { background: rgba(128,128,128,0.03) !important; }
    .mobile-card-item { background: rgba(128,128,128,0.03); border: 1px solid rgba(128,128,128,0.1); border-radius: 12px; padding: 15px; margin-bottom: 10px; }
    .search-input { background: rgba(128,128,128,0.05) !important; border: 1px solid rgba(128,128,128,0.2) !important; color: var(--text-primary) !important; border-radius: 10px 0 0 10px !important; }
    .search-btn { background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%) !important; border: none !important; border-radius: 0 10px 10px 0 !important; color: #fff !important; }
</style>

<div class="container-fluid px-4 py-3 inner-dashboard-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="m-0"><i class="bi bi-clock-history"></i> @lang('Withdraw History')</h4>
            <p class="text-muted small m-0">@lang('View your withdrawal transactions')</p>
        </div>
        <div class="d-flex gap-3 align-items-center flex-wrap header-actions">
            <form class="flex-grow-1">
                <div class="input-group">
                    <input class="form-control search-input" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search TRX...')">
                    <button class="input-group-text search-btn"><i class="bi bi-search"></i></button>
                </div>
            </form>
            <a class="btn" href="{{ route('user.withdraw') }}" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border: none; border-radius: 10px; color: #fff; white-space: nowrap;">
                <i class="bi bi-cash-stack"></i> @lang('Withdraw')
            </a>
        </div>
    </div>

    <div class="premium-card">
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
                                    $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                }
                            }
                        @endphp
                        <tr>
                            <td><strong class="text-primary">{{ __(@$withdraw->method->name) }}</strong><br><small class="text-muted">{{ $withdraw->trx }}</small></td>
                            <td><small>{{ showDateTime($withdraw->created_at, 'd M Y') }}</small><br><small class="text-muted">{{ diffForHumans($withdraw->created_at) }}</small></td>
                            <td>
                                {{ showAmount($withdraw->amount) }}<br>
                                <small class="text-danger">- {{ showAmount($withdraw->charge) }}</small><br>
                                <strong class="text-success">{{ showAmount($withdraw->amount - $withdraw->charge) }}</strong>
                            </td>
                            <td><small>1 = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}</small><br><strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</strong></td>
                            <td>@php echo $withdraw->statusBadge @endphp</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary detailBtn" data-user_data="{{ json_encode($details) }}" @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>{{ __($emptyMessage) }}</td></tr>
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
                            $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                        }
                    }
                @endphp
                <div class="mobile-card-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <strong class="text-primary">{{ __(@$withdraw->method->name) }}</strong>
                            <small class="text-muted d-block">{{ $withdraw->trx }}</small>
                        </div>
                        @php echo $withdraw->statusBadge @endphp
                    </div>
                    <div class="row g-2">
                        <div class="col-6"><small class="text-muted">@lang('Amount'):</small><br><strong>{{ showAmount($withdraw->amount) }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('Charge'):</small><br><span class="text-danger">-{{ showAmount($withdraw->charge) }}</span></div>
                        <div class="col-6"><small class="text-muted">@lang('Receivable'):</small><br><strong class="text-success">{{ showAmount($withdraw->amount - $withdraw->charge) }}</strong></div>
                        <div class="col-6"><small class="text-muted">@lang('Date'):</small><br><strong>{{ showDateTime($withdraw->created_at, 'd M Y') }}</strong></div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary w-100 mt-2 detailBtn" data-user_data="{{ json_encode($details) }}" @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                        <i class="bi bi-eye"></i> @lang('View Details')
                    </button>
                </div>
            @empty
                <div class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>{{ __($emptyMessage) }}</div>
            @endforelse
        </div>
    </div>

    @if ($withdraws->hasPages())
        <div class="mt-3">{{ paginateLinks($withdraws) }}</div>
    @endif
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('modal')
    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: var(--bg-card); border: 1px solid rgba(128,128,128,0.1); border-radius: 16px;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%); border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title text-white"><i class="bi bi-info-circle me-2"></i> @lang('Withdrawal Details')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
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
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary">
                        <span><strong>${element.name}</strong></span>
                        <span>${element.value}</span>
                    </li>`;
                } else {
                    html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary">
                        <span><strong>${element.name}</strong></span>
                        <span><a href="${element.value}" class="btn btn-sm btn-outline-info"><i class="bi bi-file-earmark"></i> @lang('Download')</a></span>
                    </li>`;
                }
            });
            modal.find('.userData').html(html);

            if ($(this).data('admin_feedback') != undefined) {
                var adminFeedback = `
                    <div class="alert alert-info bg-transparent border-info text-info">
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
