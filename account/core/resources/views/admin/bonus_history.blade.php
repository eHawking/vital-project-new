@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('User')</th>
                            <th>@lang('Voucher Code')</th>
                            <th>@lang('Distributed Bonuses')</th>
                            <th>@lang('Remaining Pool Bonuses')</th>
                            <th>@lang('Date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($histories as $history)
                        <tr>
                            <td>
                                <span class="fw-bold">{{@$history->user->fullname}}</span>
                                <br>
                                <span class="small">
                                <a href="{{ route('admin.users.detail', $history->user_id) }}"><span>@</span>{{ @$history->user->username }}</a>
                                </span>
                            </td>
                            <td>
                                {{ @$history->voucher->code ?? 'N/A' }}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline--primary view-bonuses"
                                        data-bonuses="{{ json_encode($history->distributed_bonuses) }}"
                                        data-modal-title="Distributed Bonuses">
                                    <i class="las la-eye"></i> @lang('View')
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline--info view-bonuses"
                                        data-bonuses="{{ json_encode($history->remaining_bonuses) }}"
                                        data-modal-title="Remaining Pool Bonuses">
                                    <i class="las la-eye"></i> @lang('View')
                                </button>
                            </td>
                            <td>
                                {{ showDateTime($history->created_at) }}<br>{{ diffForHumans($history->created_at) }}
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($histories->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($histories) }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Bonus Details MODAL --}}
<div class="modal fade" id="bonusModal" tabindex="-1" role="dialog" aria-labelledby="bonusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bonusModalLabel">@lang('Bonus Details')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    {{-- Bonus details will be appended here by jQuery --}}
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.view-bonuses').on('click', function () {
            var modal = $('#bonusModal');
            var bonuses = $(this).data('bonuses');
            var modalTitle = $(this).data('modal-title');
            var bonusList = modal.find('.modal-body .list-group');
            bonusList.empty();

            if (bonuses && Object.keys(bonuses).length > 0) {
                for (const [key, value] of Object.entries(bonuses)) {
                    let formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    let listItem = `<li class="list-group-item d-flex justify-content-between align-items-center">
                                        ${formattedKey}
                                        <span class="badge badge--primary rounded-pill">${value}</span>
                                    </li>`;
                    bonusList.append(listItem);
                }
            } else {
                bonusList.append('<li class="list-group-item text-center">@lang('No bonuses to show.')</li>');
            }

            modal.find('.modal-title').text(modalTitle);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
