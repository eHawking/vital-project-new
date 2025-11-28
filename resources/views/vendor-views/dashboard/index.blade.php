@extends('layouts.vendor.app')
@section('title', translate('dashboard'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-0 border-0 mb-3">
            <div class="flex-between row align-items-center mx-1">
                <div>
                    <h1 class="page-header-title text-capitalize">{{translate('welcome').' '.auth('seller')->user()->f_name.' '.auth('seller')->user()->l_name}}</h1>
                    <p>{{ translate('monitor_your_business_analytics_and_statistics').'.'}}</p>
                </div>

               @if(auth('seller')->user()->vendor_type == 'franchise' || auth('seller')->user()->vendor_type == 'shop')
    <a class="btn btn--primary" href="{{ route('vendor.order.history.products.list') }}">
        <i class="tio-premium-outlined mr-1"></i> {{ translate('products') }}
    </a>
@else
    <div>
        <a class="btn btn--primary" href="{{ route('vendor.products.list', ['type' => 'all']) }}">
            <i class="tio-premium-outlined mr-1"></i> {{ translate('products') }}
        </a>
    </div>
@endif
            </div>
        </div>
		
		
		@if(in_array(auth('seller')->user()->vendor_type, ['franchise', 'shop']))
		<div class="card mb-3 remove-card-shadow">
    <div class="card-body">
        <div class="row justify-content-between align-items-center g-2 ">
            <div class="row g-2">
                <div class="col-lg-4">
                    <div class="card h-100 d-flex justify-content-center align-items-center">
                        <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
                            <img width="48" class="mb-2" src="{{ dynamicAsset('public/assets/back-end/img/inhouse-earning.png') }}" alt="">
                            <h3 class="for-card-count mb-0 fz-24">
                                {{ getCurrencySymbol() }}{{ getFormatCurrency(auth('customer')->user()->wallet_balance) }}
                            </h3>
                            <div class="text-capitalize mb-30">
                                {{ translate('Deposit Balance') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="card card-body h-100 justify-content-center">
                                <div class="d-flex gap-2 justify-content-between align-items-center">
                                    <div class="d-flex flex-column align-items-start">
                                        <h3 class="mb-1 fz-24">
                                            {{ getCurrencySymbol() }}{{ getFormatCurrency($dashboardData['totalDelivered'] ?? 0) }}
                                        </h3>
                                        <div class="text-capitalize mb-0">{{ translate('total_delivered_orders') }}</div>
                                    </div>
                                    <div>
                                        <img width="40" class="mb-2" src="{{ dynamicAsset('public/assets/back-end/img/delivered.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body h-100 justify-content-center">
                                <div class="d-flex gap-2 justify-content-between align-items-center">
                                    <div class="d-flex flex-column align-items-start">
                                        <h3 class="mb-1 fz-24">
                                            {{ $dashboardData['totalOrdersDelivered'] ?? 0 }}
                                        </h3>
                                        <div class="text-capitalize mb-0">{{ translate('total_orders_delivered') }}</div>
                                    </div>
                                    <div>
                                        <img width="40" class="mb-2" src="{{ dynamicAsset('public/assets/back-end/img/dce.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body h-100 justify-content-center">
                                <div class="d-flex gap-2 justify-content-between align-items-center">
                                    <div class="d-flex flex-column align-items-start">
                                        <h3 class="mb-1 fz-24">
                                            {{ getCurrencySymbol() }}{{ getFormatCurrency($dashboardData['totalInHouseDelivered'] ?? 0) }}
                                        </h3>
                                        <div class="text-capitalize mb-0">{{ translate('total_in-house_orders') }}</div>
                                    </div>
                                    <div>
                                        <img width="40" class="mb-2" src="{{ dynamicAsset('public/assets/back-end/img/ttc.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body h-100 justify-content-center">
                                <div class="d-flex gap-2 justify-content-between align-items-center">
                                    <div class="d-flex flex-column align-items-start">
                                        <h3 class="mb-1 fz-24">
                                            {{ $dashboardData['totalInHouseOrdersDelivered'] ?? 0 }}
                                        </h3>
                                        <div class="text-capitalize mb-0">{{ translate('total_in-house_orders_delivered') }}</div>
                                    </div>
                                    <div>
                                        <img width="40" class="mb-2" src="{{ dynamicAsset('public/assets/back-end/img/pa.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
		@endif
		
		@if(auth('seller')->user()->vendor_type == "vendor")
        <div class="card mb-3 remove-card-shadow">
            <div class="card-body">
                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business_analytics.png')}}" alt="">
                            {{translate('order_analytics')}}
                        </h4>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-sm-end">
                        <select class="custom-select w-auto" id="statistics_type" name="statistics_type">
                            <option value="overall">
                                {{translate('overall_Statistics')}}
                            </option>
                            <option value="today">
                                {{translate('todays_Statistics')}}
                            </option>
                            <option value="thisMonth">
                                {{translate('this_Months_Statistics')}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row g-2" id="order_stats">
                    @include('vendor-views.partials._dashboard-order-status',['orderStatus'=>$dashboardData['orderStatus']])
                </div>
            </div>
        </div>
        <div class="card mb-3 remove-card-shadow">
            <div class="card-body">
                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" class="mb-1" src="{{dynamicAsset(path: 'public/assets/back-end/img/admin-wallet.png')}}" alt="">
                            {{translate('vendor_Wallet')}}
                        </h4>
                    </div>
                </div>
                <div class="row g-2" id="order_stats">
                    @include('vendor-views.partials._dashboard-wallet-status',['dashboardData'=>$dashboardData])
                </div>
            </div>
        </div>

        <div class="modal fade" id="balance-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{translate('withdraw_Request')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('vendor.dashboard.withdraw-request')}}" method="post">
                        <div class="modal-body">
                            @csrf
                            <div class="">
                                <select class="form-control" id="withdraw_method" name="withdraw_method" required>
                                    @foreach($withdrawalMethods as $method)
                                        <option value="{{$method['id']}}" {{ $method['is_default'] ? 'selected':'' }}>{{$method['method_name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="" id="method-filed__div">

                            </div>

                            <div class="mt-1">
                                <label for="recipient-name" class="col-form-label fz-16">{{translate('amount')}}
                                    :</label>
                                <input type="number" name="amount" step=".01"
                                       value="{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['totalEarning']), currencyCode: getCurrencyCode(type: 'default'))}}"
                                       class="form-control" id="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit"
                                        class="btn btn--primary">{{translate('request')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		@endif
        <div class="row g-2">
            @php( $shippingMethod = getWebConfig('shipping_method'))
            <div class="col-12" id="earn-statistics-div">
                @include('vendor-views.dashboard.partials.earning-statistics')
            </div>
            <div class="col-lg-{{ $shippingMethod != 'sellerwise_shipping' ? '6':'4' }}">
                <div class="card h-100 remove-card-shadow">
                    @include('vendor-views.partials._top-rated-products',['topRatedProducts'=>$dashboardData['topRatedProducts']])
                </div>
            </div>
            <div class="col-lg-{{ $shippingMethod != 'sellerwise_shipping' ? '6':'4' }}">
                <div class="card h-100 remove-card-shadow">
                    @include('vendor-views.partials._top-selling-products',['topSell'=>$dashboardData['topSell']])
                </div>
            </div>
            @if($shippingMethod=='sellerwise_shipping')
                <div class="col-lg-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('vendor-views.partials._top-rated-delivery-man',['topRatedDeliveryMan'=>$dashboardData['topRatedDeliveryMan']])
                    </div>
                </div>
           @endif
        </div>
    </div>
    <span id="withdraw-method-url" data-url="{{ route('vendor.dashboard.method-list') }}"></span>
    <span id="order-status-url" data-url="{{ route('vendor.dashboard.order-status', ['type' => ':type']) }}"></span>
    <span id="seller-text" data-text="{{ translate('vendor')}}"></span>
    <span id="in-house-text" data-text="{{ translate('In-house')}}"></span>
    <span id="customer-text" data-text="{{ translate('customer')}}"></span>
    <span id="store-text" data-text="{{ translate('store')}}"></span>
    <span id="product-text" data-text="{{ translate('product')}}"></span>
    <span id="order-text" data-text="{{ translate('order')}}"></span>
    <span id="brand-text" data-text="{{ translate('brand')}}"></span>
    <span id="business-text" data-text="{{ translate('business')}}"></span>
    <span id="customers-text" data-text="{{ $dashboardData['customers'] }}"></span>
    <span id="products-text" data-text="{{ $dashboardData['products'] }}"></span>
    <span id="orders-text" data-text="{{ $dashboardData['orders'] }}"></span>
    <span id="brands-text" data-text="{{ $dashboardData['brands'] }}"></span>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/dashboard.js')}}"></script>
@endpush
