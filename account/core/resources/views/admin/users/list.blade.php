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
                                <th>@lang('Ref By')</th>
                                <th>@lang('Email-Mobile')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Balance')</th>
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
									 <span class="fw-bold">{{$user->refBy->fullname ?? 'N/A'}}</span>
                                    <br>
									<a href="{{ route('admin.users.detail', @$user->refBy->id) }}">{{ $user->refBy->username ?? '' }}</a>
                                </td>
                                <td>
                                    {{ $user->email }}<br>{{ $user->mobileNumber }}
                                </td>
								  <td>
                                    {{ $user->city ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="fw-bold" title="{{ @$user->country_name }}">{{ $user->country_code }}</span>
                                </td>



                                <td>
                                    {{ showDateTime($user->created_at) }} <br> {{ diffForHumans($user->created_at) }}
                                </td>


                                <td>
                                    <span class="fw-bold">

                                    {{ showAmount($user->balance) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a>
                                        @if (request()->routeIs('admin.users.kyc.pending'))
                                        <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank" class="btn btn-sm btn-outline--dark">
                                            <i class="las la-user-check"></i>@lang('KYC Data')
                                        </a>
                                        @endif
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
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Search users" />
@endpush
