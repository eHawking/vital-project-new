@extends($activeTemplate . 'layouts.master')

@section('content')

<!-- Include Modern Finance Theme CSS -->
@include($activeTemplate . 'css.modern-finance-theme')
@include($activeTemplate . 'css.mobile-fixes')

<div class="row mb-4">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="text-white m-0"><i class="bi bi-people-fill"></i> @lang('My Referrals')</h4>
                <p class="text-white-50 small m-0">@lang('View all your direct referrals and their status')</p>
            </div>
        </div>
    </div>
</div>

<!-- Referral Stats Cards -->
<div class="row mb-4 g-3">
    <div class="col-lg-3 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Total Referrals')</h6>
                    <h3 class="text-white m-0">{{ $logs->total() }}</h3>
                </div>
                <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Active DSP')</h6>
                    <h3 class="text-white m-0">{{ $logs->where('plan_id', 1)->count() }}</h3>
                </div>
                <div class="icon-box variant-green" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Active BTP')</h6>
                    <h3 class="text-white m-0">{{ $logs->where('is_btp', 1)->count() }}</h3>
                </div>
                <div class="icon-box variant-purple" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-star-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('This Month')</h6>
                    <h3 class="text-white m-0">{{ $logs->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
                <div class="icon-box variant-orange" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-calendar-check-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Referrals Table -->
<div class="premium-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table transection-table-2">
                <thead>
                    <tr>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-hash"></i> @lang('No.')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-person-badge"></i> @lang('Username')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-person"></i> @lang('Name')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-envelope"></i> @lang('Email')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-shield-check"></i> @lang('DSP')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-award"></i> @lang('BTP')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-calendar-event"></i> @lang('Join Date')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentPage = $logs->currentPage();
                        $perPage = $logs->perPage();
                        $totalRecords = $logs->total();
                        $startNumber = $totalRecords - (($currentPage - 1) * $perPage);
                    @endphp
                    @forelse($logs as $key => $data)
                        <tr style="background: rgba(255,255,255,0.05);">
                            <td data-label="@lang('No.')" class="text-white">{{ $startNumber - $key }}</td>
                            <td data-label="@lang('Username')" class="text-white">
                                <strong class="text-info">{{ $data->username }}</strong>
                            </td>
                            <td data-label="@lang('Name')" class="text-white">{{ $data->fullname }}</td>
                            <td data-label="@lang('Email')" class="text-white">
                                <small>{{ $data->email }}</small>
                            </td>
                            <td data-label="@lang('DSP')" class="text-white">
                                @if($data->plan_id == 1)
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-check-circle-fill"></i> @lang('Active')</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill"><i class="bi bi-x-circle-fill"></i> @lang('Inactive')</span>
                                @endif
                            </td>
                            <td data-label="@lang('BTP')" class="text-white">
                                @if($data->is_btp == 1)
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-star-fill"></i> @lang('Active')</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill"><i class="bi bi-dash-circle-fill"></i> @lang('Inactive')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Join Date')" class="text-white">
                                <i class="bi bi-calendar3 text-white-50"></i> {{ showDateTime($data->created_at, 'd M Y') }}<br>
                                <small class="text-white-50">{{ $data->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-white-50 text-center" colspan="7">
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

@if ($logs->hasPages())
    <div class="mt-4">
        {{ paginateLinks($logs) }}
    </div>
@endif

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush