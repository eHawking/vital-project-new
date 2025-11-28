@extends('layouts.admin.app')

@section('title', translate('Shop Orders'))

@push('css_or_js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h2 class="h1 mb-2 mb-md-0">{{ translate('Shop Orders') }}</h2>
        </div>

        <div class="card">
            <div class="card-header">
                <form action="{{ url()->current() }}" method="GET" class="d-flex flex-grow-1">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ translate('Search by Shop Name or Order ID') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Shop Name') }}</th>
                                <th>{{ translate('Order Amount') }}</th>
                                <th>{{ translate('Payment Status') }}</th>
                                <th>{{ translate('Order Status') }}</th>
                                <th class="text-center">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($shopOrders as $key => $order)
                                <tr>
                                    <td>{{ $key + $shopOrders->firstItem() }}</td>
                                    <td>{{ $order->shop_name }}</td>
                                    <td>
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->order_amount), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->payment_status == 'paid' ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                            <span class="legend-indicator {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}"></span>
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge
                                            {{ $order->status == 'approved' ? 'badge-soft-success' :
                                               ($order->status == 'cancelled' ? 'badge-soft-danger' : 'badge-soft-info') }}">
                                            <span class="legend-indicator {{ $order->status == 'approved' ? 'bg-success' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-info') }}"></span>
                                                {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)"
                                           class="btn btn-outline-info btn-sm"
                                           data-toggle="tooltip" data-placement="top" title="{{ translate('View Details') }}"
                                           data-details="{{ json_encode($order->order_details) }}"
                                           onclick="showOrderDetails(this)">
                                           <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($order->status == 'pending')
                                            <a href="javascript:void(0)"
                                               class="btn btn-outline-success btn-sm"
                                               data-toggle="tooltip" data-placement="top" title="{{ translate('Approve') }}"
                                               onclick="showConfirmationModal('{{ route('admin.shop.order.approve', ['id' => $order->id]) }}', '{{ translate('Are you sure you want to approve this order?') }}')">
                                               <i class="fas fa-check"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                               class="btn btn-outline-danger btn-sm"
                                               data-toggle="tooltip" data-placement="top" title="{{ translate('Cancel') }}"
                                               onclick="showConfirmationModal('{{ route('admin.shop.order.cancel', ['id' => $order->id]) }}', '{{ translate('Are you sure you want to cancel this order?') }}')">
                                               <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center p-4">
                                            <img class="mb-3" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="No Orders Found" style="width: 7rem;">
                                            <p class="mb-0">{{ translate('No orders found') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $shopOrders->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">{{ translate('Order Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ translate('Item') }}</th>
                                <th>{{ translate('Quantity') }}</th>
                                <th>{{ translate('Price') }}</th>
                            </tr>
                        </thead>
                        <tbody id="orderDetailsTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Confirm Action') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="confirmationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('No') }}</button>
                    <a href="#" class="btn btn-primary" id="confirmActionBtn">{{ translate('Yes') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    function showOrderDetails(button) {
        const details = JSON.parse(button.getAttribute('data-details')) || [];
        const tableBody = $('#orderDetailsTableBody');
        tableBody.empty();

        if (details.length > 0) {
            details.forEach(detail => {
                const row = `
                    <tr>
                        <td>${detail.product_name || 'N/A'}</td>
                        <td>${detail.quantity || 'N/A'}</td>
                        <td>${detail.subtotal || 'N/A'}</td>
                    </tr>
                `;
                tableBody.append(row);
            });
        } else {
            tableBody.append(`<tr><td colspan="3" class="text-center">{{ translate('No details found!') }}</td></tr>`);
        }
        $('#orderDetailsModal').modal('show');
    }

    function showConfirmationModal(actionUrl, message) {
        $('#confirmationMessage').text(message);
        $('#confirmActionBtn').attr('href', actionUrl);
        $('#confirmationModal').modal('show');
    }
</script>
@endpush