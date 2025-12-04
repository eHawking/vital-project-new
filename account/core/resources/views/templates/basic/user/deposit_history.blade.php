@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h4 class="text-white m-0"><i class="bi bi-wallet2"></i> @lang('Deposit History')</h4>
        <div class="d-flex gap-3 align-items-center flex-wrap">
            <form class="flex-grow-1">
                <div class="input-group">
                    <input class="form-control bg-transparent text-white border-secondary" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search TRX...')" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                    <button class="input-group-text bg-primary text-white border-primary" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </form>
            <a class="btn btn-primary pulse-animation" href="{{ route('user.deposit.index') }}" style="background: var(--grad-primary); border: none; border-radius: 10px;">
                <i class="las la-plus-circle"></i> @lang('Deposit Now')
            </a>
        </div>
    </div>

    <div class="premium-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table transection-table-2">
                    <thead>
                        <tr>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Gateway | Transaction')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Initiated')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Amount')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Conversion')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Status')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            <tr style="background: rgba(255,255,255,0.05);">
                                <td class="text-white">
                                    <span class="fw-bold">
                                        <span class="text-info">
                                            @if ($deposit->method_code < 5000)
                                                {{ __(@$deposit->gateway->name) }}
                                            @else
                                                @lang('Google Pay')
                                            @endif
                                        </span>
                                    </span>
                                    <br>
                                    <small class="text-white-50"> {{ $deposit->trx }} </small>
                                </td>

                                <td class="text-white">
                                    {{ showDateTime($deposit->created_at) }}<br><small class="text-white-50">{{ diffForHumans($deposit->created_at) }}</small>
                                </td>
                                <td class="text-white">
                                    {{ showAmount($deposit->amount) }} + <span class="text-danger" data-bs-toggle="tooltip"
                                        title="@lang('Processing Charge')">{{ showAmount($deposit->charge) }} </span>
                                    <br>
                                    <strong data-bs-toggle="tooltip" title="@lang('Amount with charge')">
                                        {{ showAmount($deposit->amount + $deposit->charge) }}
                                    </strong>
                                </td>
                                <td class="text-white">
                                    {{ showAmount(1) }} = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->method_currency) }}
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
                                                @$details[$key]->value = route(
                                                    'user.download.attachment',
                                                    encrypt(getFilePath('verify') . '/' . @$info->value),
                                                );
                                            }
                                        }
                                    }
                                @endphp

                                <td>
                                    @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                        <a class="btn btn-sm detailBtn" data-info="{{ json_encode($details) }}" href="javascript:void(0)"
                                            @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif
                                            style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                                            <i class="fas fa-desktop"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-success btn-sm" data-bs-toggle="tooltip" type="button" title="@lang('Automatically processed')" style="border-radius: 50%;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center text-white-50">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @if ($deposits->hasPages())
        <div class="mt-4">
            {{ paginateLinks($deposits) }}
        </div>
    @endif
@endsection

@push('modal')
    {{-- APPROVE MODAL --}}
    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1);">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">@lang('Details')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2 bg-transparent">
                    </ul>
                    <div class="feedback text-white-50"></div>
                </div>
            </div>
        </div>
    </div>
@endpush


@push('script')
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
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        } else {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary">
                                <span>${element.name}</span>
                                <span"><a href="${element.value}" class="text-info"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong class="text-white">@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
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
