@extends('layouts.admin.app')

@section('title', translate('Wallets History'))

@section('content')
<div class="content container-fluid">
   <div class="d-flex align-items-center gap-3 mb-3">
    <i class="tio-wallet" style="font-size: 24px;"></i>
    <h2 class="h1 mb-0">{{ translate('wallets_history') }}</h2>
</div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('id') }}</th>
                            <th>{{ translate('customer') }}</th>
                            <th>{{ translate('type') }}</th>
                            <th>{{ translate('vendor') }}</th>
                            <th>{{ translate('location') }}</th>
                            <th>{{ translate('products') }}</th>
                            <th>{{ translate('bonus_summary') }}</th>
                            <th>{{ translate('date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
				

@if($walletsHistory->isEmpty())
    <tr>
        <td colspan="8" class="text-center">
            <div class="py-4">
                <i class="tio-wallet-outlined mb-3" style="font-size: 48px; opacity: 0.5;"></i>
                <div class="text-muted">
                    {{ translate('no_wallet_transactions_found') }}
                </div>
            </div>
        </td>
    </tr>
@endif

                        @foreach($walletsHistory as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>
                                <div>{{ $record->username }}</div>
                                <small class="text-muted">{{ $record->customer_name }}</small>
                            </td>
                            <td>{{ $record->type }}</td>
                            <td>
								<div>{{ $record->vendor_id }}</div>
								<small class="text-muted">{{ $record->shop_name }}</small>
                                <div>{{ $record->vendor_username }}</div>
                                <small class="text-muted">{{ $record->vendor_name }}</small>
                            </td>
                            <td>{{ $record->city }}</td>
                            <td>
                                <button class="btn btn-outline-info btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productsModal-{{ $record->id }}">
                                    {{ translate('view_details') }}
                                </button>
                            </td>
                            <td>
    <div class="d-flex justify-content-between align-items-center">
        @php
            $totalDistributed = array_sum(array_column($record->bonus_types, 'distributed'));
            $totalRemaining = array_sum(array_column($record->bonus_types, 'remaining'));

            // Calculate the total budget by summing the budget of each product line item
            $summaryTotalBudget = collect($record->products)->sum(function ($product) {
                return ($product['quantity'] ?? 1) * ($product['budget'] ?? 0);
            });
        @endphp

        <div>
            <div class="text-info" style="font-weight: bold;">{{ translate('total_budget') }}: {{ number_format($summaryTotalBudget, 2) }}</div>
            <div class="text-success">{{ translate('distributed') }}: {{ number_format($totalDistributed, 2) }}</div>
            <div class="text-primary">{{ translate('remaining') }}: {{ number_format($totalRemaining, 2) }}</div>
        </div>

        <button class="btn btn-outline-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#bonusModal-{{ $record->id }}">
            <i class="fi fi-rr-eye"></i>
        </button>
    </div>
</td>
                          <td>
    {{ date('d M Y', strtotime($record->created_at)) }}<br>
    {{ date('h:i A', strtotime($record->created_at)) }}
</td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                {{ $walletsHistory->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($walletsHistory as $record)
<div class="modal fade" id="bonusModal-{{ $record->id }}" tabindex="-1" 
     aria-labelledby="bonusModalLabel-{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title">{{ translate('bonus_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('bonus_type') }}</th>
                                <th>{{ translate('distributed') }}</th>
                                <th>{{ translate('remaining') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record->bonus_types as $bonus => $values)
                            <tr>
                                <td>{{ strtoupper(str_replace('_', ' ', $bonus)) }}</td>
                                <td class="text-success">{{ $values['distributed'] }}</td>
                                <td class="text-primary">{{ $values['remaining'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Products Modal --}}
<div class="modal fade" id="productsModal-{{ $record->id }}" tabindex="-1"
     aria-labelledby="productsModalLabel-{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title">{{ translate('product_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('product_name') }}</th>
                                <th>{{ translate('quantity') }}</th>
                                <th>{{ translate('budget') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($record->products as $product)
                            <tr>
                                <td>{{ $product['name'] ?? 'N/A' }}</td>
                                <td>{{ $product['quantity'] ?? 0 }}</td>
                                <td>{{ number_format($product['line_budget'] ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ translate('no_products_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('script')
{{-- You can add custom javascript here if needed in the future --}}
@endpush