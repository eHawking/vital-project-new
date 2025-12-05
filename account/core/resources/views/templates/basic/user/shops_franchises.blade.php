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
                <h4 class="text-white m-0"><i class="bi bi-shop-window"></i> @lang('Shops & Franchises')</h4>
                <p class="text-white-50 small m-0">@lang('View all vendors, shops, and franchises in your network')</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row justify-content-center mb-4 g-3">
    <div class="col-lg-4 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Total Shops')</h6>
                    <h3 class="text-white m-0">{{ $shops }}</h3>
                </div>
                <div class="icon-box variant-blue" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-shop fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Total Franchises')</h6>
                    <h3 class="text-white m-0">{{ $franchises }}</h3>
                </div>
                <div class="icon-box variant-purple" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-building fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="premium-card stat-item h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1">@lang('Total Vendors')</h6>
                    <h3 class="text-white m-0">{{ $vendors }}</h3>
                </div>
                <div class="icon-box variant-pink" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendors Table -->
<div class="premium-card mb-4">
    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
        <h5 class="title text-white m-0"><i class="bi bi-list-ul"></i> @lang('Vendors List')</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table transection-table-2">
                <thead>
                    <tr>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-hash"></i> @lang('No.')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-person-badge"></i> @lang('Username')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-card-text"></i> @lang('Vendor ID')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-shop"></i> @lang('Vendor Name')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-tag"></i> @lang('Type')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-geo-alt"></i> @lang('City')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;"><i class="bi bi-pin-map"></i> @lang('Location')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellers as $key => $seller)
                        <tr style="background: rgba(255,255,255,0.05);">
                            <td data-label="@lang('No.')" class="text-white">{{ $sellers->firstItem() + $key }}</td>
                            <td data-label="@lang('Username')" class="text-white">
                                <strong class="text-info">{{ $seller->username }}</strong>
                            </td>
                            <td data-label="@lang('Vendor ID')" class="text-white">
                                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 rounded-pill">{{ $seller->vendor_id }}</span>
                            </td>
                            <td data-label="@lang('Vendor Name')" class="text-white">
                                {{ optional($seller->primaryShop)->name ?? 'N/A' }}
                            </td>
                            <td data-label="@lang('Type')" class="text-white">
                                @if($seller->vendor_type == 'Shop')
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-shop"></i> {{ $seller->vendor_type }}</span>
                                @elseif($seller->vendor_type == 'Franchise')
                                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 rounded-pill"><i class="bi bi-building"></i> {{ $seller->vendor_type }}</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 rounded-pill"><i class="bi bi-person"></i> {{ $seller->vendor_type }}</span>
                                @endif
                            </td>
                            <td data-label="@lang('City')" class="text-white">
                                <i class="bi bi-geo-alt-fill text-white-50"></i> {{ $seller->city }}
                            </td>
                            <td data-label="@lang('Location')" class="text-white">
                                <small class="text-white-50">{{ optional($seller->primaryShop)->address ?? 'N/A' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-white-50">
                                <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i><br>
                                @lang('No records found')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sellers->hasPages())
        <div class="p-3">
            {{ $sellers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Include Mobile Bottom Navigation -->
@include($activeTemplate . 'partials.mobile-bottom-nav')

@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush