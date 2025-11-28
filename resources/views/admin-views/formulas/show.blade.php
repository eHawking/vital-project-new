@extends('layouts.admin.app')

@section('title', __('Formula Details'))

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            {{ __('Formula Details') }}
        </h2>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formula Details -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Formula ID: {{ $formula->id }}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <!-- Display only relevant fields -->
                    <tr><th>BV</th><td>{{ $formula->bv }}</td></tr>
                    <tr><th>PV</th><td>{{ $formula->pv }}</td></tr>
                    <tr><th>DDS Referral Bonus</th><td>{{ $formula->dds_ref_bonus }}</td></tr>
                    <tr><th>Shop Bonus</th><td>{{ $formula->shop_bonus }}</td></tr>
                    <tr><th>Shop Reference</th><td>{{ $formula->shop_reference }}</td></tr>
                    <tr><th>Franchise Bonus</th><td>{{ $formula->franchise_bonus }}</td></tr>
                    <tr><th>Franchise Referral Bonus</th><td>{{ $formula->franchise_ref_bonus }}</td></tr>
                    <tr><th>City Referral Bonus</th><td>{{ $formula->city_ref_bonus }}</td></tr>
                    <tr><th>Leadership Bonus</th><td>{{ $formula->leadership_bonus }}</td></tr>
                    <tr><th>Promo</th><td>{{ $formula->promo }}</td></tr>
                    <tr><th>User Promo</th><td>{{ $formula->user_promo }}</td></tr>
                    <tr><th>Seller Promo</th><td>{{ $formula->seller_promo }}</td></tr>
                    <tr><th>Shipping Expense</th><td>{{ $formula->shipping_expense }}</td></tr>
                    <tr><th>Bilty Expense</th><td>{{ $formula->bilty_expense }}</td></tr>
                    <tr><th>Office Expense</th><td>{{ $formula->office_expense }}</td></tr>
                    <tr><th>Event Expense</th><td>{{ $formula->event_expense }}</td></tr>
                    <tr><th>Fuel Expense</th><td>{{ $formula->fuel_expense }}</td></tr>
                    <tr><th>Visit Expense</th><td>{{ $formula->visit_expense }}</td></tr>
                    <tr><th>Company Partner Bonus</th><td>{{ $formula->company_partner_bonus }}</td></tr>
                    <tr><th>Product Partner Bonus</th><td>{{ $formula->product_partner_bonus }}</td></tr>
                    <tr><th>Budget Promo</th><td>{{ $formula->budget_promo }}</td></tr>
                    <tr><th>Product Partner Referral Bonus</th><td>{{ $formula->product_partner_ref_bonus }}</td></tr>
                    <tr><th>Vendor Referral Bonus</th><td>{{ $formula->vendor_ref_bonus }}</td></tr>
					<tr><th>Royalty Bonus</th><td>{{ $formula->royalty_bonus }}</td></tr>
										    

                </tbody>
            </table>
        </div>
    </div>

    <!-- Formula Change History -->
    <h2>Change History</h2>
    <div class="card mb-4">
        <div class="card-body">
            @if ($formula->histories->isEmpty())
                <p>No changes recorded for this formula.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Edit Count</th>
                            <th>Date & Time of Change</th>
                            <th>Changed Fields & Values</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($formula->histories->sortByDesc('changed_at') as $history)
                            <tr>
                                <td>{{ $history->edit_count }}</td>
                                <td>{{ $history->changed_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <ul>
                                        @foreach ($history->details as $field => $change)
                                            <li>
                                                <strong>{{ ucwords(str_replace('_', ' ', $field)) }}:</strong> 
                                                Old: {{ $change['old'] ?? 'N/A' }} 
                                                → New: {{ $change['new'] ?? 'N/A' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <a href="{{ route('admin.formulas.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
