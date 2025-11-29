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
                <h4 class="text-white m-0"><i class="bi bi-cart-fill"></i> @lang('My Orders')</h4>
                <p class="text-white-50 small m-0">@lang('View your order history')</p>
            </div>
        </div>
    </div>
</div>

<div class="premium-card mb-4">
    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
        <h5 class="title text-white m-0"><i class="bi bi-list-ul"></i> @lang('Order List')</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table transection-table-2">
                <thead>
                    <tr>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Product')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Quantity')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Price')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Total Price')</th>
                        <th style="background: rgba(255,255,255,0.1); color: #fff;">@lang('Status')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr style="background: rgba(255,255,255,0.05);">
                            <td class="text-white">
                                @if (@$order->product)
                                    <a href="{{ route('product.details', ['id' => @$order->product->id, 'slug' => slug($order->product->name)]) }}" class="text-info fw-bold">
                                        {{ __(strLimit($order->product->name, '30')) }}</a>
                                @endif
                            </td>
                            <td class="text-white">{{ $order->quantity }}</td>
                            <td class="text-white">{{ showAmount($order->price) }}</td>
                            <td class="text-success fw-bold">{{ showAmount($order->total_price) }}</td>
                            <td>
                                @php echo $order->statusOrderBadge @endphp
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-white-50" colspan="100%">@lang('No order found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@if ($orders->hasPages())
    <div class="mt-4">
        {{ paginateLinks($orders) }}
    </div>
@endif
@endsection

@push('script')
<!-- Include Icon Enhancer -->
@include($activeTemplate . 'js.icon-enhancer')
@endpush
