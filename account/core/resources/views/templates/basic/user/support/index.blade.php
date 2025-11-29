@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h4 class="text-white m-0"><i class="las la-ticket-alt"></i> @lang('Support Tickets')</h4>
        <a class="btn btn-primary pulse-animation" href="{{ route('ticket.open') }}" style="background: var(--grad-primary); border: none;"> 
            <i class="fas fa-plus"></i> @lang('New Ticket')
        </a>
    </div>

    <div class="premium-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table transection-table-2">
                    <thead>
                        <tr>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Subject')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Status')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Priority')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Last Reply')</th>
                            <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supports as $support)
                            <tr style="background: rgba(255,255,255,0.05);">
                                <td> 
                                    <a class="fw-bold text-info" href="{{ route('ticket.view', $support->ticket) }}">
                                        [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} 
                                    </a>
                                </td>
                                <td>
                                    @php echo $support->statusBadge; @endphp
                                </td>
                                <td>
                                    @if ($support->priority == Status::PRIORITY_LOW)
                                        <span class="badge bg-secondary bg-opacity-25 text-white-50 border border-secondary border-opacity-25 rounded-pill">@lang('Low')</span>
                                    @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                        <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill">@lang('Medium')</span>
                                    @elseif($support->priority == Status::PRIORITY_HIGH)
                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill">@lang('High')</span>
                                    @endif
                                </td>
                                <td class="text-white-50">{{ diffForHumans($support->last_reply) }} </td>

                                <td>
                                    <a class="btn btn-sm btn-outline-light border-secondary" href="{{ route('ticket.view', $support->ticket) }}">
                                        <i class="las la-desktop"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-white-50" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if ($supports->hasPages())
        <div class="mt-4">
            {{ paginateLinks($supports) }}
        </div>
    @endif
@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush
