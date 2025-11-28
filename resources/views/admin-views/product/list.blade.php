@extends('layouts.admin.app')

@section('title', translate('product_List'))

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/inhouse-product-list.png') }}" alt="">
                @if($type == 'in_house')
                    {{ translate('in_House_Product_List') }}
                @elseif($type == 'seller')
                    {{ translate('vendor_Product_List') }}
                @endif
                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">{{ $products->total() }}</span>
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url()->current() }}" method="GET">
                    <input type="hidden" value="{{ request('status') }}" name="status">
                    <div class="row g-2">
                        <div class="col-12">
                            <h3 class="mb-3">{{ translate('filter_Products') }}</h3>
                        </div>
                        @if (request('type') == 'seller')
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="form-label" for="store">{{ translate('store') }}</label>
                                    <select name="seller_id" class="custom-select"
                                            data-placeholder="Select from dropdown">
                                        <option></option>
                                        <option value="" selected>{{ translate('all_store') }}</option>
                                        @foreach ($sellers as $seller)
                                            <option
                                                value="{{ $seller->id}}"{{request('seller_id')==$seller->id ? 'selected' :''}}>
                                                {{ $seller->shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="form-label" for="store">{{ translate('brand') }}</label>
                                <select name="brand_id" class="custom-select" data-placeholder="Select from dropdown">
                                    <option></option>
                                    <option value="" selected>{{ translate('all_brand') }}</option>
                                    @foreach ($brands as $brand)
                                        <option
                                            value="{{ $brand->id}}" {{request('brand_id')==$brand->id ? 'selected' :''}}>{{ $brand->default_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="form-label">{{ translate('category') }}</label>
                                <select class="custom-select action-get-request-onchange"
                                        data-placeholder="Select from dropdown" name="category_id"
                                        data-url-prefix="{{ url('/admin/products/get-categories?parent_id=') }}"
                                        data-element-id="sub-category-select"
                                        data-element-type="select">
                                    <option value="{{ old('category_id') }}" selected
                                            disabled>{{ translate('select_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}"
                                            {{ request('category_id') == $category['id'] ? 'selected' : '' }}>
                                            {{ $category['defaultName'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="form-label">{{ translate('sub_Category') }}</label>
                                <select class="custom-select action-get-request-onchange"
                                        data-placeholder="Select from dropdown" name="sub_category_id"
                                        id="sub-category-select"
                                        data-url-prefix="{{ url('/admin/products/get-categories?parent_id=') }}"
                                        data-element-id="sub-sub-category-select"
                                        data-element-type="select">
                                    <option
                                        value="{{request('sub_category_id') != null ? request('sub_category_id') : null}}"
                                        selected {{request('sub_category_id') != null ? '' : 'disabled'}}>{{request('sub_category_id') != null ? $subCategory['defaultName']: translate('select_Sub_Category') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="form-label">{{ translate('sub_Sub_Category') }}</label>
                                <select class="custom-select" data-placeholder="Select from dropdown"
                                        name="sub_sub_category_id"
                                        id="sub-sub-category-select">
                                    <option
                                        value="{{request('sub_sub_category_id') != null ? request('sub_sub_category_id') : null}}"
                                        selected {{request('sub_sub_category_id') != null ? '' : 'disabled'}}>{{request('sub_sub_category_id') != null ? $subSubCategory['defaultName'] : translate('select_Sub_Sub_Category') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-end mt-4">
                                <a href="{{ route('admin.products.list',['type'=>request('type')]) }}"
                                   class="btn btn-secondary px-4">
                                    {{ translate('reset') }}
                                </a>
                                <button type="submit" class="btn btn-primary px-4 action-get-element-type">
                                    {{ translate('show_data') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-4">

                                <div class="flex-grow-1 max-w-280">
                                    <form action="{{ url()->current() }}" method="get">
                                        <input type="hidden" value="{{ request('status') }}" name="status">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="searchValue"
                                                   class="form-control"
                                                   placeholder="{{ translate('search_by_Product_Name') }}"
                                                   aria-label="Search orders"
                                                   value="{{ request('searchValue') }}">
                                            <div class="input-group-append search-submit">
                                                <button type="submit">
                                                    <i class="fi fi-rr-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-8 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">
								<button id="review-products-btn" class="btn btn-outline-warning">
    								{{ translate('Review Products') }}
								</button>
								<button id="bulk-edit-btn" class="btn btn-outline--primary">
    								{{ translate('Bulk Edit') }}
								</button>

								<button id="bulk-save-btn" class="btn btn-primary d-none">
    								{{ translate('Save Changes') }}
								</button>
								
								<button id="apply-bulk-formula-btn" class="btn btn-outline--primary">
    								{{ translate('Apply Bulk Formula') }}
								</button>
								<div class="modal fade" id="bulkFormulaModal" tabindex="-1" role="dialog" aria-labelledby="bulkFormulaModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<form id="bulkFormulaForm" action="{{ route('admin.products.bulk-formula') }}" method="POST">
											@csrf
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="bulkFormulaModalLabel">{{ translate('Apply Bulk Formula') }}</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label for="formula_id">{{ translate('Select Formula') }}</label>
														<select name="formula_id" id="formula_id" class="form-control">
															<option value="" disabled selected>{{ translate('Select Formula') }}</option>
															@foreach ($formulas as $formula)
																<option value="{{ $formula->id }}">
																	{{ translate('Formula ID:') }} {{ $formula->id }}
																</option>
															@endforeach
														</select>
													</div>
													
													<div class="form-group">
														<label>{{ translate('Apply To') }}</label>
														<div class="form-check">
															<input type="radio" name="apply_scope" id="apply_to_all" value="all" class="form-check-input" checked>
															<label class="form-check-label" for="apply_to_all">{{ translate('All Products') }}</label>
														</div>
														<div class="form-check">
															<input type="radio" name="apply_scope" id="apply_to_current_page" value="current_page" class="form-check-input">
															<label class="form-check-label" for="apply_to_current_page">{{ translate('Current Page Products') }}</label>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">
														{{ translate('Cancel') }}
													</button>
													<button type="submit" class="btn btn-primary">
														{{ translate('Apply Formula') }}
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
                                <div class="dropdown">
                                    <a type="button" class="btn btn-outline-primary text-nowrap"
                                       href="{{ route('admin.products.export-excel',['type'=>request('type')]) }}?brand_id={{request('brand_id') }}&searchValue={{ request('searchValue') }}&category_id={{request('category_id') }}&sub_category_id={{request('sub_category_id') }}&sub_sub_category_id={{request('sub_sub_category_id') }}&seller_id={{request('seller_id') }}&status={{request('status') }}">
                                        <img width="14"
                                             src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/excel.png')}}"
                                             class="excel" alt="">
                                        <span class="ps-2">{{ translate('export') }}</span>
                                    </a>
                                </div>
                                @if($type == 'in_house')
                                    <a href="{{ route('admin.products.stock-limit-list',['in_house']) }}"
                                       class="btn btn-info text-white">
                                        <span class="text">{{ translate('limited_Stocks') }}</span>
                                    </a>
                                    <a href="{{ route('admin.products.add') }}" class="btn btn-primary">
                                        <i class="fi fi-sr-add"></i>
                                        <span class="text">{{ translate('add_new_product') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered align-middle">
                            <thead class="text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('product Name') }}</th>
                                <th class="text-center">{{ translate('product Type') }}</th>
								<th class="text-center">{{ translate('Weight') }}</th>
                                <th class="text-center">{{ translate('Weight Unit') }}</th>
                                <th class="text-center">{{ translate('Purchase Price') }}</th>
                                <th class="text-center">{{ translate('Unit Price') }}</th>
                                <th class="text-center">{{ translate('Product Budget') }}</th>
			                    <th class="text-center">{{ translate('vendor_margin') }}</th>
                                <th class="text-center">{{ translate('Guest Price') }}</th>
                                <th class="text-center">{{ translate('Wholesale Price') }}</th>
                                <th class="text-center">{{ translate('Selling Price') }}</th>
                                <th class="text-center">{{ translate('Manual Price') }}</th>
                                <th class="text-center">{{ translate('Company Fee') }}</th>
                                <th class="text-center">{{ translate('Delivery Fee') }}</th>
                                <th class="text-center">{{ translate('User Promo') }}</th>
                                <th class="text-center">{{ translate('User Promo Expiry') }}</th>
                                <th class="text-center">{{ translate('Seller Promo') }}</th>
                                <th class="text-center">{{ translate('Seller Promo Expiry') }}</th>
                                <th class="text-center" style="min-width: 130px;">{{ translate('Promo') }}</th>
                                <th class="text-center" style="min-width: 130px;">{{ translate('BV') }}</th>
                                <th class="text-center" style="min-width: 130px;">{{ translate('PV') }}</th>
                                <th class="text-center">{{ translate('DDS Ref Bonus') }}</th>
                                <th class="text-center">{{ translate('Shop Bonus') }}</th>
                                <th class="text-center">{{ translate('Shop Reference') }}</th>
                                <th class="text-center">{{ translate('Product Partner Bonus') }}</th>
                                <th class="text-center">{{ translate('Product Partner Ref Bonus') }}</th>
                                <th class="text-center">{{ translate('Company Partner Bonus') }}</th>
                                <th class="text-center">{{ translate('Franchise Bonus') }}</th>
                                <th class="text-center">{{ translate('Franchise Ref Bonus') }}</th>
                                <th class="text-center">{{ translate('City Ref Bonus') }}</th>
                                <th class="text-center">{{ translate('Leadership Bonus') }}</th>
                                <th class="text-center">{{ translate('Vendor Ref Bonus') }}</th>
                                <th class="text-center">{{ translate('Vendor Bonus') }}</th>
                                <th class="text-center">{{ translate('Bilty Expense') }}</th>
                                <th class="text-center">{{ translate('Fuel Expense') }}</th>
                                <th class="text-center">{{ translate('Visit Expense') }}</th>
                                <th class="text-center">{{ translate('Shipping Expense') }}</th>
                                <th class="text-center">{{ translate('Office Expense') }}</th>
                                <th class="text-center">{{ translate('Event Expense') }}</th>
                                <th class="text-center">{{ translate('Budget Promo') }}</th>
			                    <th class="text-center">{{ translate('Royalty Bonus') }}</th>
                                <th class="text-center">{{ translate('Current Stock') }}</th>
                                <th class="text-center">{{ translate('Recently Sold') }}</th>
                                <th class="text-center">{{ translate('Manual') }}</th>
                                <th class="text-center">{{ translate('Verified') }}</th>
                                <th class="text-center">{{ translate('Choice') }}</th>
                                <th class="text-center">{{ translate('Pro') }}</th>
                                <th class="text-center">{{ translate('VIP') }}</th>
                                <th class="text-center">{{ translate('Adult') }}</th>
                                <th class="text-center">{{ translate('show_as_featured') }}</th>
                                <th class="text-center">{{ translate('active_status') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key => $product)
                                <tr data-product-id="{{ $product['id'] }}">
                                    <th scope="row">{{ $products->firstItem()+$key}}</th>
                                    <td>
                                        <a href="{{ route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']]) }}"
                                           class="media align-items-center gap-2">
                                            <img
                                                src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'backend-product') }}"
                                                class="avatar border object-fit-cover" alt="">
                                            <div>
                                                <div class="media-body text-dark text-hover-primary">
                                                    {{ Str::limit($product['name'], 20) }}
                                                </div>
                                                @if($product?->clearanceSale)
                                                    <div class="badge text-bg-warning badge-warning user-select-none">
                                                        {{ translate('Clearance_Sale') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ translate(str_replace('_',' ',$product['product_type'])) }}
                                    </td>
									<td class="text-center">
                <input type="text" class="form-control weight" value="{{ $product['weight'] }}" disabled>
            </td>
           <td class="text-center">
    <select class="form-control custom-select weight-unit" disabled>
        {{-- Count-based Unit --}}
        <option value="pc" {{ ($product->weight_unit ?? '') == 'pc' ? 'selected' : '' }}>
            {{ translate('pc (piece)') }}
        </option>

        {{-- Solid Weight Units --}}
        <option value="kg" {{ ($product->weight_unit ?? '') == 'kg' ? 'selected' : '' }}>
            {{ translate('kg (kilogram)') }}
        </option>
        <option value="gm" {{ ($product->weight_unit ?? '') == 'gm' ? 'selected' : '' }}>
            {{ translate('gm (gram)') }}
        </option>
        <option value="lb" {{ ($product->weight_unit ?? '') == 'lb' ? 'selected' : '' }}>
            {{ translate('lb (pound)') }}
        </option>
        <option value="oz" {{ ($product->weight_unit ?? '') == 'oz' ? 'selected' : '' }}>
            {{ translate('oz (weight)') }}
        </option>

        {{-- Liquid Volume Units --}}
        <option value="l" {{ ($product->weight_unit ?? '') == 'l' ? 'selected' : '' }}>
            {{ translate('L (liter)') }}
        </option>
        <option value="ml" {{ ($product->weight_unit ?? '') == 'ml' ? 'selected' : '' }}>
            {{ translate('ml (milliliter)') }}
        </option>
        <option value="fl_oz" {{ ($product->weight_unit ?? '') == 'fl_oz' ? 'selected' : '' }}>
            {{ translate('fl oz (fluid ounce)') }}
        </option>
    </select>
</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control purchase-price" value="{{ $product['purchase_price'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control unit-price" value="{{ $product['unit_price'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control product-budget" value="{{ $product['budget'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                       <input type="text" class="form-control vendor-margin" value="{{ $product['vendor_margin'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control guest-price" value="{{ $product['guest_price'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control wholesale-price" value="{{ $product['wholesale_price'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control selling-price" value="{{ $product['selling_price'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control manual-price" value="{{ $product['manual_price'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control company-fee" value="{{ $product['company_fee'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control delivery-fee" value="{{ $product['delivery_fee'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control user-promo" value="{{ $product['user_promo'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control user-promo-expiry" value="{{ $product['user_promo_expiry'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control seller-promo" value="{{ $product['seller_promo'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control seller-promo-expiry" value="{{ $product['seller_promo_expiry'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control promo" value="{{ $product['promo'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control bv" value="{{ $product['bv'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control pv" value="{{ $product['pv'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control dds-ref-bonus" value="{{ $product['dds_ref_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control shop-bonus" value="{{ $product['shop_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control shop-reference" value="{{ $product['shop_reference'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control product-partner-bonus" value="{{ $product['product_partner_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control product-partner-ref-bonus" value="{{ $product['product_partner_ref_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control company-partner-bonus" value="{{ $product['company_partner_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control franchise-bonus" value="{{ $product['franchise_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control franchise-ref-bonus" value="{{ $product['franchise_ref_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control city-ref-bonus" value="{{ $product['city_ref_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control leadership-bonus" value="{{ $product['leadership_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control vendor-ref-bonus" value="{{ $product['vendor_ref_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control vendor-bonus" value="{{ $product['vendor_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control bilty-expense" value="{{ $product['bilty_expense'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control fuel-expense" value="{{ $product['fuel_expense'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control visit-expense" value="{{ $product['visit_expense'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control shipping-expense" value="{{ $product['shipping_expense'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control office-expense" value="{{ $product['office_expense'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control event-expense" value="{{ $product['event_expense'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control budget-promo" value="{{ $product['budget_promo'] }}" disabled>
                                    </td>
                                     <td class="text-center">
                                        <input type="text" class="form-control royalty-bonus" value="{{ $product['royalty_bonus'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control current-stock" value="{{ $product['current_stock'] }}" disabled>
                                    </td> 
                                    <td class="text-center">
                                        <input type="text" class="form-control recently-sold" value="{{ $product['recently_sold'] }}" disabled>
                                    </td>
                                    <td class="text-center">
                                        @php($productName = str_replace("'",'`',$product['name']))
                                        <form action="{{ route('admin.products.manual-status-update') }}" method="post" id="product-manual-{{$product['id']}}-form" class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input custom-modal-plugin" name="status" value="1"
                                                       id="product-manual-{{$product['id']}}"
                                                       {{ $product['manual'] == 1 ? 'checked' : '' }}
                                                       data-modal-type="input-change-form"
                                                       data-modal-form="#product-manual-{{$product['id']}}-form"
                                                       data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                       data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                       data-on-title="{{ translate('Want_to_enable_Manual_editing_for_this_product?') }}"
                                                       data-off-title="{{ translate('Want_to_disable_Manual_editing_for_this_product?') }}"
                                                       data-on-message="<p>{{ translate('If_you_enable_this_the_product_will_no_longer_be_updated_by_bulk_formulas') }}</p>"
                                                       data-off-message="<p>{{ translate('If_you_disable_this_the_product_will_be_updated_by_bulk_formulas_again') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
									<td class="text-center">
                                        <form action="{{ route('admin.products.verified-status-update') }}" method="post" id="product-verified{{ $product['id'] }}-form" class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input custom-modal-plugin" name="is_verified"
                                                       id="product-verified{{ $product['id'] }}" value="1"
                                                       {{ $product['is_verified'] == 1 ? 'checked' : '' }}
                                                       data-modal-type="input-change-form"
                                                       data-modal-form="#product-verified{{$product['id']}}-form"
                                                       data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                       data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                       data-on-title="{{ translate('Want_to_Mark_This_Product_As_Verified') }}"
                                                       data-off-title="{{ translate('Want_to_Unmark_This_Product_As_Verified') }}"
                                                       data-on-message="<p>{{ translate('If_enabled_this_product_will_be_marked_as_verified') }}</p>"
                                                       data-off-message="<p>{{ translate('If_disabled_this_product_will_be_unmarked_as_verified') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('admin.products.choice-status-update') }}" method="post" id="product-choice{{ $product['id'] }}-form" class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input custom-modal-plugin" name="is_choice"
                                                       id="product-choice{{ $product['id'] }}" value="1"
                                                       {{ $product['is_choice'] == 1 ? 'checked' : '' }}
                                                       data-modal-type="input-change-form"
                                                       data-modal-form="#product-choice{{$product['id']}}-form"
                                                       data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                       data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                       data-on-title="{{ translate('Want_to_Mark_This_Product_As_Choice') }}"
                                                       data-off-title="{{ translate('Want_to_Unmark_This_Product_As_Choice') }}"
                                                       data-on-message="<p>{{ translate('If_enabled_this_product_will_be_marked_as_choice') }}</p>"
                                                       data-off-message="<p>{{ translate('If_disabled_this_product_will_be_unmarked_as_choice') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('admin.products.pro-status-update') }}" method="post" id="product-pro{{ $product['id'] }}-form" class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input custom-modal-plugin" name="is_pro"
                                                       id="product-pro{{ $product['id'] }}" value="1"
                                                       {{ $product['is_pro'] == 1 ? 'checked' : '' }}
                                                       data-modal-type="input-change-form"
                                                       data-modal-form="#product-pro{{$product['id']}}-form"
                                                       data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                       data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                       data-on-title="{{ translate('Want_to_Mark_This_Product_As_Pro') }}"
                                                       data-off-title="{{ translate('Want_to_Unmark_This_Product_As_Pro') }}"
                                                       data-on-message="<p>{{ translate('If_enabled_this_product_will_be_marked_as_pro') }}</p>"
                                                       data-off-message="<p>{{ translate('If_disabled_this_product_will_be_unmarked_as_pro') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('admin.products.vip-status-update') }}" method="post" id="product-vip{{ $product['id'] }}-form" class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input custom-modal-plugin" name="is_vip"
                                                       id="product-vip{{ $product['id'] }}" value="1"
                                                       {{ $product['is_vip'] == 1 ? 'checked' : '' }}
                                                       data-modal-type="input-change-form"
                                                       data-modal-form="#product-vip{{$product['id']}}-form"
                                                       data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                       data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                       data-on-title="{{ translate('Want_to_Mark_This_Product_As_VIP') }}"
                                                       data-off-title="{{ translate('Want_to_Unmark_This_Product_As_VIP') }}"
                                                       data-on-message="<p>{{ translate('If_enabled_this_product_will_be_marked_as_VIP') }}</p>"
                                                       data-off-message="<p>{{ translate('If_disabled_this_product_will_be_unmarked_as_VIP') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('admin.products.adult-status-update') }}" method="post" id="product-adult{{ $product['id'] }}-form" class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input custom-modal-plugin" name="is_adult"
                                                       id="product-adult{{ $product['id'] }}" value="1"
                                                       {{ $product['is_adult'] == 1 ? 'checked' : '' }}
                                                       data-modal-type="input-change-form"
                                                       data-modal-form="#product-adult{{$product['id']}}-form"
                                                       data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                       data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                       data-on-title="{{ translate('Want_to_Mark_This_Product_As_Adult') }}"
                                                       data-off-title="{{ translate('Want_to_Unmark_This_Product_As_Adult') }}"
                                                       data-on-message="<p>{{ translate('If_enabled_this_product_will_be_marked_as_for_adult_audiences_only') }}</p>"
                                                       data-off-message="<p>{{ translate('If_disabled_this_product_will_be_unmarked_as_adult_product') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                
                                    <td class="text-center">
                                        <form action="{{ route('admin.products.featured-status') }}" method="post"
                                              id="product-featured-{{ $product['id'] }}-form"
                                              class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto"
                                                   for="products-featured-update-{{ $product['id'] }}">
                                                <input
                                                    class="switcher_input custom-modal-plugin"
                                                    type="checkbox" value="1" name="status"
                                                    id="products-featured-update-{{ $product['id'] }}"
                                                    {{ $product['featured'] == 1 ? 'checked' : '' }}
                                                    data-modal-type="input-change-form"
                                                    data-modal-form="#product-featured-{{ $product['id'] }}-form"
                                                    data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                    data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                    data-on-title="{{ translate('Want_to_Add').' '.$productName.' '.translate('to_the_featured_section') }}"
                                                    data-off-title="{{ translate('Want_to_Remove').' '.$productName.' '.translate('to_the_featured_section') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_product_will_be_shown_in_the_featured_product_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_product_will_be_removed_from_the_featured_product_section_of_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>

                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.products.status-update') }}" method="post"
                                              id="product-status{{ $product['id'] }}-form"
                                              class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto"
                                                   for="products-status-update-{{ $product['id'] }}">
                                                <input
                                                    class="switcher_input custom-modal-plugin"
                                                    type="checkbox" value="1" name="status"
                                                    id="products-status-update-{{ $product['id'] }}"
                                                    {{ $product['status'] == 1 ? 'checked' : '' }}
                                                    data-modal-type="input-change-form"
                                                    data-modal-form="#product-status{{ $product['id'] }}-form"
                                                    data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                    data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                    data-on-title="{{ translate('Want_to_Turn_ON').' '.$productName.' '.translate('status') }}"
                                                    data-off-title="{{ translate('Want_to_Turn_OFF').' '.$productName.' '.translate('status') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline-info icon-btn"
                                               title="{{ translate('barcode') }}"
                                               href="{{ route('admin.products.barcode', [$product['id']]) }}">
                                                <i class="fi fi-sr-barcode"></i>
                                            </a>
                                            <a class="btn btn-outline-info icon-btn" title="View"
                                               href="{{ route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']]) }}">
                                                <i class="fi fi-sr-eye"></i>
                                            </a>
                                            <a class="btn btn-outline-primary icon-btn"
                                               title="{{ translate('edit') }}"
                                               href="{{ route('admin.products.update',[$product['id']]) }}">
                                                <i class="fi fi-sr-pencil"></i>
                                            </a>
                                            <span class="btn btn-outline-danger icon-btn delete-data"
                                                  title="{{ translate('delete') }}"
                                                  data-id="product-{{ $product['id'] }}">
                                               <i class="fi fi-rr-trash"></i>
                                            </span>
                                        </div>
                                        <form action="{{ route('admin.products.delete',[$product['id']]) }}"
                                              method="post" id="product-{{ $product['id'] }}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {{ $products->links() }}
                        </div>
                    </div>

                    @if(count($products)==0)
                        @include('layouts.admin.partials._empty-state',['text' => 'no_product_found'],['image' => 'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
	<span id="message-select-word" data-text="{{ translate('select') }}"></span>

	<div class="modal fade" id="reviewProductsModal" tabindex="-1" role="dialog" aria-labelledby="reviewProductsModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="reviewProductsModalLabel">{{ translate('Incomplete Product Report') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>{{ translate('The following active products have missing or zero values in critical fields. You can choose to deactivate them.') }}</p>
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>{{ translate('Product Name') }}</th>
									<th>{{ translate('Missing Fields') }}</th>
									<th class="text-center">{{ translate('Deactivate') }}</th>
								</tr>
							</thead>
							<tbody id="reviewProductsTableBody">
								</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">
						{{ translate('Cancel') }}
					</button>
					<button type="button" id="deactivate-selected-products-btn" class="btn btn-danger">
						{{ translate('Deactivate Selected') }}
					</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('script')
<script>
// Function to format values as currency
function formatCurrency(value) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: '{{ getCurrencyCode() }}' }).format(value);
}

// Initially, format only price fields as currency values on page load
document.addEventListener('DOMContentLoaded', function() {
    // Format price fields as currency
    document.querySelectorAll('.unit-price, .purchase-price, .product-budget, .vendor-margin,.guest-price, .wholesale-price, .selling-price, .manual-price, .company-fee, .delivery-fee, .user-promo, .user-promo-expiry, .seller-promo, .seller-promo-expiry, .promo, .bv, .pv, .dds-ref-bonus, .shop-bonus, .shop-reference, .product-partner-bonus, .product-partner-ref-bonus, .company-partner-bonus, .franchise-bonus, .franchise-ref-bonus, .city-ref-bonus, .leadership-bonus, .vendor-ref-bonus, .vendor-bonus, .bilty-expense, .fuel-expense, .visit-expense, .shipping-expense, .office-expense, .event-expense, .budget-promo, .royalty-bonus').forEach(function(input) {
        input.setAttribute('data-original-value', input.value); // Store original value for editing
        input.value = formatCurrency(input.value); // Show currency formatted value
    });

    // Do not apply currency formatting to stock and boolean fields
    document.querySelectorAll('.current-stock, .recently-sold, .weight, .weight-unit').forEach(function(input) {
        input.setAttribute('data-original-value', input.value); // Store original value for editing
        input.value = input.value; // Just show the numeric value (no currency formatting)
    });

});

// Bulk Edit Button - Makes the input fields editable
document.getElementById('bulk-edit-btn').addEventListener('click', function() {
    document.querySelectorAll('.unit-price, .purchase-price, .product-budget, .vendor-margin, .guest-price, .wholesale-price, .selling-price, .manual-price, .company-fee, .delivery-fee, .user-promo, .user-promo-expiry, .seller-promo, .seller-promo-expiry, .promo, .bv, .pv, .dds-ref-bonus, .shop-bonus, .shop-reference, .product-partner-bonus, .product-partner-ref-bonus, .company-partner-bonus, .franchise-bonus, .franchise-ref-bonus, .city-ref-bonus, .leadership-bonus, .vendor-ref-bonus, .vendor-bonus, .bilty-expense, .fuel-expense, .visit-expense, .shipping-expense, .office-expense, .event-expense, .budget-promo, .royalty-bonus, .current-stock, .weight, .weight-unit').forEach(function(input) {
        input.value = input.getAttribute('data-original-value'); // Show original value for editing
        input.removeAttribute('disabled'); // Make the fields editable
    });

    document.getElementById('bulk-save-btn').classList.remove('d-none'); // Show save button
    this.classList.add('d-none'); // Hide the edit button
});

// Save Changes Button - Collects data and sends it to the server via AJAX
document.getElementById('bulk-save-btn').addEventListener('click', function() {
    // Disable the save button to prevent multiple clicks
    const saveButton = this;
    saveButton.setAttribute('disabled', true); // Disable the button
    saveButton.innerHTML = 'Saving...'; // Optionally change the button text

    let products = [];

    // Collect all updated product values
    document.querySelectorAll('tr[data-product-id]').forEach(function(row) {
        let productId = row.getAttribute('data-product-id');
        let unitPrice = row.querySelector('.unit-price').value;
        let purchasePrice = row.querySelector('.purchase-price').value;
        let productBudget = row.querySelector('.product-budget').value;
		let vendorMargin = row.querySelector('.vendor-margin').value;
        let guestPrice = row.querySelector('.guest-price').value;
        let wholesalePrice = row.querySelector('.wholesale-price').value;
        let sellingPrice = row.querySelector('.selling-price').value;
        let manualPrice = row.querySelector('.manual-price').value;
        let companyFee = row.querySelector('.company-fee').value;
        let deliveryFee = row.querySelector('.delivery-fee').value;
        let userPromo = row.querySelector('.user-promo').value;
        let userPromoExpiry = row.querySelector('.user-promo-expiry').value;
        let sellerPromo = row.querySelector('.seller-promo').value;
        let sellerPromoExpiry = row.querySelector('.seller-promo-expiry').value;
        let promo = row.querySelector('.promo').value;
        let bv = row.querySelector('.bv').value;
        let pv = row.querySelector('.pv').value;
        let ddsRefBonus = row.querySelector('.dds-ref-bonus').value;
        let shopBonus = row.querySelector('.shop-bonus').value;
        let shopReference = row.querySelector('.shop-reference').value;
        let productPartnerBonus = row.querySelector('.product-partner-bonus').value;
        let productPartnerRefBonus = row.querySelector('.product-partner-ref-bonus').value;
        let companyPartnerBonus = row.querySelector('.company-partner-bonus').value;
        let franchiseBonus = row.querySelector('.franchise-bonus').value;
        let franchiseRefBonus = row.querySelector('.franchise-ref-bonus').value;
        let cityRefBonus = row.querySelector('.city-ref-bonus').value;
        let leadershipBonus = row.querySelector('.leadership-bonus').value;
        let vendorRefBonus = row.querySelector('.vendor-ref-bonus').value;
        let vendorBonus = row.querySelector('.vendor-bonus').value;
        let biltyExpense = row.querySelector('.bilty-expense').value;
        let fuelExpense = row.querySelector('.fuel-expense').value;
        let visitExpense = row.querySelector('.visit-expense').value;
        let shippingExpense = row.querySelector('.shipping-expense').value;
        let officeExpense = row.querySelector('.office-expense').value;
        let eventExpense = row.querySelector('.event-expense').value;
        let budgetPromo = row.querySelector('.budget-promo').value;
		let royaltyBonus = row.querySelector('.royalty-bonus').value;
        let currentStock = row.querySelector('.current-stock').value;
        let recentlySold = row.querySelector('.recently-sold').value;
		let weight = row.querySelector('.weight').value;
        let weightUnit = row.querySelector('.weight-unit').value;
      

        // Push product data into the products array
        if (productId) {
            products.push({
                id: productId,
                unit_price: unitPrice,
                purchase_price: purchasePrice,
                budget: productBudget,
				vendor_margin: vendorMargin,
                guest_price: guestPrice,
                wholesale_price: wholesalePrice,
                selling_price: sellingPrice,
                manual_price: manualPrice,
                company_fee: companyFee,
                delivery_fee: deliveryFee,
                user_promo: userPromo,
                user_promo_expiry: userPromoExpiry,
                seller_promo: sellerPromo,
                seller_promo_expiry: sellerPromoExpiry,
                promo: promo,
                bv: bv,
                pv: pv,
                dds_ref_bonus: ddsRefBonus,
                shop_bonus: shopBonus,
                shop_reference: shopReference,
                product_partner_bonus: productPartnerBonus,
                product_partner_ref_bonus: productPartnerRefBonus,
                company_partner_bonus: companyPartnerBonus,
                franchise_bonus: franchiseBonus,
                franchise_ref_bonus: franchiseRefBonus,
                city_ref_bonus: cityRefBonus,
                leadership_bonus: leadershipBonus,
                vendor_ref_bonus: vendorRefBonus,
                vendor_bonus: vendorBonus,
                bilty_expense: biltyExpense,
                fuel_expense: fuelExpense,
                visit_expense: visitExpense,
                shipping_expense: shippingExpense,
                office_expense: officeExpense,
                event_expense: eventExpense,
                budget_promo: budgetPromo,
				royalty_bonus: royaltyBonus,
                current_stock: currentStock,
                recently_sold: recentlySold,
				weight: weight,
                weight_unit: weightUnit,
            });
        }
    });

    // AJAX Request to update products on the server
    fetch('{{ route("admin.products.bulk-update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ products: products }) // Send products data in JSON format
    })
    .then(response => response.json()) // Parse the JSON response
    .then(data => {
        if (data.success) {
            // Success message will be shown by ToastMagic on page reload.
            // Optionally reload the page
            setTimeout(() => {
                location.reload(); // Refresh the page to show updated values
            }, 2000); // Wait for 2 seconds before reloading
        } else {
            // Show error message using toastr.error if something goes wrong
            toastr.error('Error: ' + data.message);
            saveButton.removeAttribute('disabled'); // Re-enable the button if there's an error
            saveButton.innerHTML = 'Save Changes'; // Restore button text
        }
    })
    .catch(error => {
        console.error('AJAX error:', error); // Log AJAX error for debugging
        toastr.error('An error occurred. Please check the console for details.'); // Show general error message using toastr
        saveButton.removeAttribute('disabled'); // Re-enable the button if there's an error
        saveButton.innerHTML = 'Save Changes'; // Restore button text
    });
});
</script>

<script>
    // Event to open the Bulk Formula modal
    document.getElementById('apply-bulk-formula-btn').addEventListener('click', function () {
        new bootstrap.Modal(document.getElementById('bulkFormulaModal')).show();
    });

    // AJAX request for applying bulk formula
    document.getElementById('bulkFormulaForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);

    // Check which radio button is selected
    const applyScope = document.querySelector('input[name="apply_scope"]:checked').value;

    if (applyScope === 'current_page') {
        // Collect the current page's product IDs
        const productIds = Array.from(document.querySelectorAll('tr[data-product-id]')).map(row =>
            row.getAttribute('data-product-id')
        );
        formData.append('product_ids', JSON.stringify(productIds));
    }

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success message will be shown by ToastMagic on page reload.
            location.reload();
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        console.error(error);
        toastr.error('{{ translate("An error occurred. Please try again.") }}');
    });
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Review Products Button Click Event
    const reviewButton = document.getElementById('review-products-btn');
    if (reviewButton) {
        reviewButton.addEventListener('click', function() {
            this.innerHTML = '{{ translate("Checking...") }}';
            this.setAttribute('disabled', true);

            fetch('{{ route("admin.products.review-products-status") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                this.innerHTML = '{{ translate("Review Products") }}';
                this.removeAttribute('disabled');

                if (data.products && data.products.length > 0) {
                    const tableBody = document.getElementById('reviewProductsTableBody');
                    tableBody.innerHTML = ''; // Clear previous data

                    data.products.forEach(product => {
                        const row = `
                            <tr data-product-id="${product.id}">
                                <td>${product.name}</td>
                                <td><small>${product.missing_fields}</small></td>
                                <td class="text-center">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input deactivate-switch" checked>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });

                    new bootstrap.Modal(document.getElementById('reviewProductsModal')).show();
                } else {
                    toastr.success(data.message || '{{ translate("All active products have complete data!") }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('{{ translate("An error occurred while reviewing products.") }}');
                this.innerHTML = '{{ translate("Review Products") }}';
                this.removeAttribute('disabled');
            });
        });
    }

    // Deactivate Selected Products Button Click Event in Modal
    const deactivateButton = document.getElementById('deactivate-selected-products-btn');
    if(deactivateButton) {
        deactivateButton.addEventListener('click', function() {
            this.setAttribute('disabled', true);
            this.innerHTML = '{{ translate("Deactivating...") }}';
            
            const productsToDeactivate = [];
            document.querySelectorAll('#reviewProductsTableBody tr').forEach(row => {
                const checkbox = row.querySelector('.deactivate-switch');
                if (checkbox.checked) {
                    productsToDeactivate.push(row.getAttribute('data-product-id'));
                }
            });

            if (productsToDeactivate.length === 0) {
                toastr.info('{{ translate("No products selected for deactivation.") }}');
                this.removeAttribute('disabled');
                this.innerHTML = '{{ translate("Deactivate Selected") }}';
                return;
            }

            fetch('{{ route("admin.products.deactivate-incomplete-products") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_ids: productsToDeactivate })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const reviewModal = bootstrap.Modal.getInstance(document.getElementById('reviewProductsModal'));
                    if(reviewModal) {
                        reviewModal.hide();
                    }
                    location.reload();
                } else {
                    toastr.error(data.message || '{{ translate("Failed to deactivate products.") }}');
                    this.removeAttribute('disabled');
                    this.innerHTML = '{{ translate("Deactivate Selected") }}';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('{{ translate("An error occurred.") }}');
                this.removeAttribute('disabled');
                this.innerHTML = '{{ translate("Deactivate Selected") }}';
            });
        });
    }
});
</script>
@endpush