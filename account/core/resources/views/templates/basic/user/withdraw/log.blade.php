@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')

<!-- Include Mobile Fixes CSS -->
@include($activeTemplate . 'css.mobile-fixes')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <h4 class="text-white m-0"><i class="bi bi-clock-history"></i> @lang('Withdraw History')</h4>
    <div class="d-flex gap-3 align-items-center flex-wrap">
        <form class="flex-grow-1">
            <div class="input-group">
                <input class="form-control bg-transparent text-white border-secondary" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search by transactions...')" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                <button class="input-group-text bg-primary text-white border-primary" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                    <i class="las la-search"></i>
                </button>
            </div>
        </form>
        <a class="btn btn-primary pulse-animation" href="{{ route('user.withdraw') }}" style="background: var(--grad-primary); border: none; border-radius: 10px;">
            <i class="las la-plus-circle"></i> @lang('Withdraw Now')
        </a>
    </div>
</div>

<div class="premium-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table transection-table-2">
                <thead>
                    <tr>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-bank"></i> @lang('Gateway')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-calendar-event"></i> @lang('Initiated')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-cash-stack"></i> @lang('Amount')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-arrow-left-right"></i> @lang('Conversion')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-check-circle"></i> @lang('Status')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-eye"></i> @lang('Action')</th>
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
                        <tr style="background: rgba(255,255,255,0.05);">
                            <td data-label="@lang('Gateway')" class="text-white">
                                <div>
                                    <strong class="text-primary">{{ __(@$withdraw->method->name) }}</strong><br>
                                    <small class="text-white-50">{{ $withdraw->trx }}</small>
                                </div>
                            </td>
                            <td data-label="@lang('Initiated')" class="text-white">
                                <div>
                                    <small>{{ showDateTime($withdraw->created_at, 'd M Y') }}</small><br>
                                    <small class="text-white-50">{{ diffForHumans($withdraw->created_at) }}</small>
                                </div>
                            </td>
                            <td data-label="@lang('Amount')" class="text-white">
                                <div>
                                    {{ showAmount($withdraw->amount) }}<br>
                                    <small class="text-danger">- {{ showAmount($withdraw->charge) }}</small><br>
                                    <strong class="text-success">{{ showAmount($withdraw->amount - $withdraw->charge) }}</strong>
                                </div>
                            </td>
                            <td data-label="@lang('Conversion')" class="text-white">
                                <div>
                                    <small>1 = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}</small><br>
                                    <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</strong>
                                </div>
                            </td>
                            <td data-label="@lang('Status')" class="text-white">
                                @php echo $withdraw->statusBadge @endphp
                            </td>
                            <td data-label="@lang('Action')" class="text-white">
                                <button class="btn btn-sm btn-outline-light detailBtn" 
                                        data-user_data="{{ json_encode($details) }}"
                                        @if ($withdraw->status == Status::PAYMENT_REJECT) 
                                            data-admin_feedback="{{ $withdraw->admin_feedback }}" 
                                        @endif
                                        style="border: 1px solid rgba(255,255,255,0.2);">
                                    <i class="bi bi-eye"></i> @lang('Details')
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-white-50">
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

@if ($withdraws->hasPages())
    <div class="mt-4">
        {{ paginateLinks($withdraws) }}
    </div>
@endif

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('modal')
    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1);">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white"><i class="bi bi-info-circle"></i> @lang('Withdrawal Details')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-3 bg-transparent"></ul>
                    <div class="feedback text-white-50"></div>
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
