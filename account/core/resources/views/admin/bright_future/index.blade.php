@extends('admin.layouts.app')
@section('panel')
<style>
@media (max-width: 767px) {
          table.style--two tbody tr {
    border: 2px dashed #0000005c;
    display: block;
    margin-bottom: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);

    transition: box-shadow 0.3s ease-in-out;
}
table.style--two tbody tr:hover {
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.4);
	transition: box-shadow 0.3s ease-in-out;
}
</style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Email-Mobile')</th>
                                <th>@lang('Bright Future Balance')</th>
                                <th>@lang('Main Balance')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{$user->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a>
                                    </span>
                                </td>
                                <td>
                                    {{ $user->email }}<br>{{ $user->mobileNumber }}
                                </td>
                                <td>
                                    <span class="fw-bold">{{ showAmount($user->bright_future_balance) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ showAmount($user->balance) }}</span>
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a>
                                    </div>
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
                @if ($users->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Manual Profit Modal -->
    <div class="modal fade" id="manualProfitModal" tabindex="-1" role="dialog" aria-labelledby="manualProfitModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualProfitModalLabel">@lang('Manual Record Profit')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.bright.future.manual.profit') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Username')</label>
                            <input type="text" name="username" class="form-control" required placeholder="@lang('Enter Username')">
                        </div>
                        <div class="form-group">
                            <label>@lang('Profit Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" required placeholder="@lang('Enter Amount')">
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Date and Time')</label>
                            <input type="datetime-local" name="date" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#manualProfitModal">
        <i class="las la-plus"></i> @lang('Manual Record Profit')
    </button>
    <x-search-form placeholder="Search users" />
@endpush
