@extends('layouts.admin.app')

@section('title', translate('Bonus Wallets'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
   <div class="mb-3">
    <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
        <i class="fi fi-sr-wallet text-primary" style="font-size: 24px;"></i>
        {{ translate('Bonus Wallets') }}
    </h2>
</div>
    <!-- Wallet Categories -->
    @foreach([
        'User Wallets' => ['group' => ['bv', 'pv']],
        'Bonus Wallets' => [
            'group' => ['dds_ref_bonus', 'shop_bonus', 'shop_reference', 'franchise_bonus', 'franchise_ref_bonus', 
                        'city_ref_bonus', 'leadership_bonus', 'company_partner_bonus', 'product_partner_bonus', 
                        'product_partner_ref_bonus', 'vendor_ref_bonus', 'royalty_bonus']
        ],
        'Promo Wallets' => [
            'group' => ['promo', 'user_promo', 'seller_promo', 'budget_promo']
        ],
        'Expense Wallets' => [
            'group' => ['shipping_expense', 'bilty_expense', 'office_expense', 'event_expense', 'fuel_expense', 
                        'visit_expense']
        ]
    ] as $groupTitle => $wallets)
    
    <div class="card mb-4">
       <div class="card-header" style="background-color: #000000;">
    <h5 class="mb-0" style="color: #ffffff;">{{ translate($groupTitle) }}</h5>
</div>
        <div class="card-body">
            <div class="row g-3">
                @php
                $displayNameMap = [
                            'bv' => 'BV',
                            'pv' => 'PV',
                            'dds_ref_bonus' => 'DDS Ref Bonus',
                            'shop_bonus' => 'Shop Bonus',
                            'shop_reference' => 'Shop Reference',
                            'franchise_bonus' => 'Franchise Bonus',
                            'franchise_ref_bonus' => 'Franchise Ref Bonus',
                            'city_ref_bonus' => 'City Ref Bonus',
                            'leadership_bonus' => 'Leadership Bonus',
                            'company_partner_bonus' => 'Company Partner Bonus',
                            'product_partner_bonus' => 'Product Partner Bonus',
                            'product_partner_ref_bonus' => 'Product Partner Ref Bonus',
                            'vendor_ref_bonus' => 'Vendor Ref Bonus',
                            'royalty_bonus' => 'Royalty Bonus',
                            'promo' => 'Promo',
                            'user_promo' => 'User Promo',
                            'seller_promo' => 'Seller Promo',
                            'budget_promo' => 'Budget Promo',
                            'shipping_expense' => 'Shipping Expense',
                            'bilty_expense' => 'Bilty Expense',
                            'office_expense' => 'Office Expense',
                            'event_expense' => 'Event Expense',
                            'fuel_expense' => 'Fuel Expense',
                            'visit_expense' => 'Visit Expense'
                        ];
                @endphp
                
                @foreach($wallets['group'] as $key)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-3 text-center">
                            <i class="@switch($key)
                                @case('bv') fi fi-sr-chart-line-up text-success @break
                                @case('pv') fi fi-sr-chart-pie-alt text-info @break
                                @case('dds_ref_bonus') fi fi-sr-gift text-warning @break
                                @case('shop_bonus') fi fi-sr-shopping-bag text-danger @break
                                @case('shop_reference') fi fi-sr-link text-primary @break
                                @case('franchise_bonus') fi fi-sr-briefcase text-secondary @break
                                @case('franchise_ref_bonus') fi fi-sr-user-check text-primary @break
                                @case('city_ref_bonus') fi fi-sr-city text-info @break
                                @case('leadership_bonus') fi fi-sr-trophy text-warning @break
                                @case('promo') fi fi-sr-bullhorn text-danger @break
                                @case('user_promo') fi fi-sr-user text-primary @break
                                @case('seller_promo') fi fi-sr-gift text-secondary @break
                                @case('shipping_expense') fi fi-sr-plane text-success @break
                                @case('bilty_expense') fi fi-sr-document text-info @break
                                @case('office_expense') fi fi-sr-building text-warning @break
                                @case('event_expense') fi fi-sr-calendar text-danger @break
                                @case('fuel_expense') fi fi-sr-gas-pump text-primary @break
                                @case('visit_expense') fi fi-sr-walking text-secondary @break
                                @case('company_partner_bonus') fi fi-sr-users text-success @break
                                @case('product_partner_bonus') fi fi-sr-box text-info @break
                                @case('budget_promo') fi fi-sr-wallet text-warning @break
                                @case('product_partner_ref_bonus') fi fi-sr-hand-holding-usd text-danger @break
                                @case('vendor_ref_bonus') fi fi-sr-user-check text-primary @break
                                @case('royalty_bonus') fi fi-sr-crown text-secondary @break
                                @default fi fi-sr-wallet text-muted
                            @endswitch fs-2 mb-2"></i>
                            
                            <h5 class="fs-4 text-primary mb-2">
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data[$key] ?? 0), currencyCode: getCurrencyCode()) }}
                            </h5>
                            
                            <span class="text-capitalize text-muted d-block">
                                {{ $displayNameMap[$key] ?? ucwords(str_replace('_', ' ', $key)) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection