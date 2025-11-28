@extends('layouts.admin.app')

@section('title', __('Add New Formula'))

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <h2 class="h1 mb-0 d-flex gap-2">
            <i class="tio-star"></i>
            {{ __('Add New Formula') }}
        </h2>
    </div>

    <!-- Form Start -->
    <form action="{{ route('admin.formulas.store') }}" method="POST" id="formula_create_form">
        @csrf

        <!-- DDS Formula Section -->
        <div class="card rest-part">
            <div class="card-header">
                <div class="d-flex gap-2">
                <i class="tio-star"></i>
                    <h4 class="mb-0">{{ __('DDS Formula') }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pv">{{ __('PV') }}</label>
                            <input type="number" step="0.01" name="pv" id="pv" class="form-control" value="{{ old('pv') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bv">{{ __('BV') }}</label>
                            <input type="number" step="0.01" name="bv" id="bv" class="form-control" value="{{ old('bv') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bonus Formula Section -->
        <div class="card mt-3 rest-part">
            <div class="card-header">
                <div class="d-flex gap-2">
                    <i class="tio-money"></i>
                    <h4 class="mb-0">{{ __('Bonus Formula') }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $bonusFields = [
                            'dds_ref_bonus' => 'DDS Referral Bonus',
                            'shop_bonus' => 'Shop Bonus',
                            'shop_reference' => 'Shop Reference',
                            'franchise_bonus' => 'Franchise Bonus',
                            'franchise_ref_bonus' => 'Franchise Referral Bonus',
                            'city_ref_bonus' => 'City Referral Bonus',
                            'leadership_bonus' => 'Leadership Bonus',
                            'company_partner_bonus' => 'Company Partner Bonus',
                            'product_partner_bonus' => 'Product Partner Bonus',
                            'product_partner_ref_bonus' => 'Product Partner Referral Bonus',
                            'vendor_ref_bonus' => 'Vendor Referral Bonus',
							'royalty_bonus' => 'Royalty Bonus',

                        ];
                    @endphp
                    @foreach ($bonusFields as $field => $label)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="{{ $field }}">{{ __($label) }}</label>
                                <input type="number" step="0.01" name="{{ $field }}" id="{{ $field }}" class="form-control" value="{{ old($field) }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Promo Formula Section -->
        <div class="card mt-3 rest-part">
            <div class="card-header">
                <div class="d-flex gap-2">
                    <i class="tio-gift"></i>
                    <h4 class="mb-0">{{ __('Promo Formula') }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="promo">{{ __('Promo') }}</label>
                            <input type="number" step="0.01" name="promo" id="promo" class="form-control" value="{{ old('promo') }}">
                        </div>
                        <div class="form-group">
                            <label for="user_promo">{{ __('User Promo') }}</label>
                            <input type="number" step="0.01" name="user_promo" id="user_promo" class="form-control" value="{{ old('user_promo') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="seller_promo">{{ __('Seller Promo') }}</label>
                            <input type="number" step="0.01" name="seller_promo" id="seller_promo" class="form-control" value="{{ old('seller_promo') }}">
                        </div>
                        <div class="form-group">
                            <label for="budget_promo">{{ __('Budget Promo') }}</label>
                            <input type="number" step="0.01" name="budget_promo" id="budget_promo" class="form-control" value="{{ old('budget_promo') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Formula Section -->
        <div class="card mt-3 rest-part">
            <div class="card-header">
                <div class="d-flex gap-2">
                    <i class="tio-credit-card"></i>
                    <h4 class="mb-0">{{ __('Expense Formula') }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $expenseFields = [
                            'shipping_expense' => 'Shipping Expense',
                            'bilty_expense' => 'Bilty Expense',
                            'office_expense' => 'Office Expense',
                            'event_expense' => 'Event Expense',
                            'fuel_expense' => 'Fuel Expense',
                            'visit_expense' => 'Visit Expense',
                        ];
                    @endphp
                    @foreach ($expenseFields as $field => $label)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="{{ $field }}">{{ __($label) }}</label>
                                <input type="number" step="0.01" name="{{ $field }}" id="{{ $field }}" class="form-control" value="{{ old($field) }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-3 text-right">
            <button type="reset" class="btn btn-secondary">{{ __('Reset') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Save Formula') }}</button>
        </div>
    </form>
</div>
@endsection
