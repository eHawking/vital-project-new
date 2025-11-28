@extends('layouts.vendor.app')

@section('title', translate('Order History'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h1 mb-0">{{ translate('Order History') }}</h2>
            <a class="btn btn-primary" href="{{ route('vendor.buy.products') }}">
                <i class="tio-add"></i> {{ translate('Buy More Products') }}
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ translate('Your Orders') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>{{ translate('Order_Amount') }}</th>
                                <th>{{ translate('Payment_Status') }}</th>
                                <th>{{ translate('Order_Status') }}</th>
                                <th>{{ translate('Order_Date') }}</th>
                                <th>{{ translate('Order_Detail') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orderHistory as $key => $order)
                                <tr class="text-center">
                                    <td>{{ $orderHistory->firstItem() + $key }}</td>
                                    <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->order_amount), currencyCode: getCurrencyCode()) }}</td>
                                    <td>
                                        <span class="badge badge-soft-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-{{ $order->status == 'approved' ? 'success' : ($order->status == 'pending' ? 'info' : 'danger') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>
                                        @php
                                            $orderDetails = $order->order_details;
                                            // Ensure order_details is an array. If it's a string, decode it.
                                            if (is_string($orderDetails)) {
                                                $orderDetails = json_decode($orderDetails, true);
                                            }
                                        @endphp
                                        <button class="btn btn-outline-primary btn-sm"
                                           data-details='{{ json_encode($orderDetails) }}'
                                           onclick="showOrderDetails(this)">
                                            <i class="tio-visible"></i> {{ translate('View Details') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4">
                                        <img src="{{ dynamicAsset('public/assets/back-end/img/empty-box.png') }}" class="w-120 mb-3" alt="">
                                        <p class="text-muted">{{ translate('No orders found!') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                {{ $orderHistory->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">{{ translate('Order Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>{{ translate('Item') }}</th>
                                    <th>{{ translate('Quantity') }}</th>
                                    <th>{{ translate('Unit_Price') }}</th>
                                    <th>{{ translate('Subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody id="orderDetailsTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
function showOrderDetails(button) {
    const details = JSON.parse(button.getAttribute('data-details'));
    const tableBody = document.getElementById('orderDetailsTableBody');
    tableBody.innerHTML = '';

    if (Array.isArray(details) && details.length > 0) {
        details.forEach(detail => {
            const row = `
                <tr class="text-center">
                    <td>${detail.product_name || 'N/A'}</td>
                    <td>${detail.quantity || 0}</td>
                    <td>${detail.unit_price || 0} {{ getCurrencyCode() }}</td>
                    <td>${detail.subtotal || 0} {{ getCurrencyCode() }}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    } else {
        tableBody.innerHTML = `<tr><td colspan="4" class="text-center">{{ translate('No details found!') }}</td></tr>`;
    }

    const modalInstance = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    modalInstance.show();
}
</script>
@endpush
