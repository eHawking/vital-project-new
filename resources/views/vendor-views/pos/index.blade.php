@extends('layouts.vendor.app')

@section('title', translate('POS'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
    <style>
        /* POS Product Section - Admin Theme */
        #product-list-container {
            min-height: 400px;
            position: relative;
        }
        
        .pos-item-wrap {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }
        
        .product-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        
        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9;
            display: none;
        }
        
        .product-overlay.active {
            display: block;
        }
        
        /* Pagination - Admin Theme Style */
        .pos-pagination-wrapper {
            padding: 1.5rem 1rem;
            margin-top: 1rem;
            border-top: 1px solid #e7eaf3;
            background: #f9fafc;
        }
        
        .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .page-item {
            margin: 0 0.125rem;
        }
        
        .page-link {
            border-radius: 0.3125rem;
            border: 1px solid #e7eaf3;
            color: #677788;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease-in-out;
        }
        
        .page-link:hover {
            background-color: #f8f9fa;
            border-color: #377dff;
            color: #377dff;
        }
        
        .page-item.active .page-link {
            background-color: #377dff;
            border-color: #377dff;
            color: #fff;
            z-index: 1;
        }
        
        .page-item.disabled .page-link {
            cursor: default;
            background-color: transparent;
            border-color: transparent;
            color: #8c98a4;
        }
        
        @media (max-width: 991px) {
            .pos-item-wrap {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 0.75rem;
            }
        }
        
        @media (max-width: 767px) {
            .pos-item-wrap {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 0.5rem;
                padding: 0.75rem;
            }
            
            .pagination {
                font-size: 0.875rem;
                gap: 0.25rem;
            }
            
            .page-link {
                padding: 0.375rem 0.625rem;
                min-width: 38px;
            }
        }
        
        @media (max-width: 575px) {
            .pos-item-wrap {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .pagination {
                gap: 0.125rem;
            }
            
            .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.8125rem;
            }
        }
    </style>
@endpush
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="card">
                    <h5 class="p-3 m-0 bg-light">
                        {{ translate('product_Section') }}
                    </h5>
                    <div class="px-3 py-4">
                        <div class="row gy-1">
                            <div class="col-sm-6">
                                <div class="input-group d-flex justify-content-end">
                                    <select name="category" id="category"
                                            class="form-control js-select2-custom w-100 action-category-filter"
                                            title="select category">
                                        <option value="">{{ translate('all_categories') }}</option>
                                        @foreach ($categories as $item)
                                            <option value="{{$item->id}}" {{$categoryId==$item->id?'selected':''}}>
                                                {{ $item->defaultName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <form class="">
                                    <div class="input-group-overlay input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="search" autocomplete="off" type="text"
                                                value="{{ $searchValue }}"
                                                name="searchValue" class="form-control search-bar-input"
                                                placeholder="{{ translate('search_by_name_or_sku') }}"
                                                aria-label="Search here">
                                        <diV class="card pos-search-card w-4 position-absolute z-index-1 w-100">
                                            <div id="pos-search-box" class="card-body search-result-box d--none">
                                            </div>
                                        </diV>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="product-list-container">
                        @include('vendor-views.pos.partials._product-list', ['products' => $products])
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-5">
                <div class="card billing-section-wrap">
                    <h5 class="p-3 m-0 bg-light">{{ translate('billing_Section') }}</h5>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button"
                                    class="btn btn-outline--primary d-flex align-items-center gap-2 action-view-all-hold-orders">
                                {{ translate('view_All_Hold_Orders') }}
                                <span class="total_hold_orders">
                                    {{$totalHoldOrder}}
                                </span>
                            </button>
                        </div>

                        <div class="form-group d-flex gap-2">
                            <div class="flex-grow-1 position-relative">
                                <input type="text" 
                                       id="customer-search" 
                                       class="form-control" 
                                       placeholder="{{ translate('Search by username or phone number') }}" 
                                       autocomplete="off">
                                <div id="customer-search-results" class="card position-absolute w-100 mt-1" style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto;">
                                    <div class="card-body p-2">
                                        <div id="customer-search-content"></div>
                                    </div>
                                </div>
                                <input type="hidden" id="selected-customer-id" value="0">
                            </div>
                            <button type="button" class="btn btn-primary" id="add_new_customer">
                                <i class="tio-add"></i>
                            </button>
                        </div>
                        
                        <div id="selected-customer-info" class="mb-3" style="display: none;">
                            <div class="alert alert-soft-primary d-flex justify-content-between align-items-center">
                                <div>
                                    <strong id="customer-display-name"></strong>
                                    <span id="customer-display-details" class="text-muted small d-block"></span>
                                </div>
                                <button type="button" class="btn btn-sm btn-soft-danger" id="clear-customer">
                                    <i class="tio-clear"></i>
                                </button>
                            </div>
                        </div>

                        <div id="cart-summary">
                            @include('vendor-views.pos.partials._cart-summary')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade pt-5" id="quick-view" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="quick-view-modal"></div>
        </div>
    </div>
    <button class="d-none" id="hold-orders-modal-btn" type="button" data-toggle="modal"
            data-target="#hold-orders-modal">
    </button>

    @if($order)
        @include('vendor-views.pos.partials.modals._print-invoice')
    @endif

    @include('vendor-views.pos.partials.modals._add-customer')
    @include('vendor-views.pos.partials.modals._hold-orders-modal')
    @include('vendor-views.pos.partials.modals._add-coupon-discount')
    @include('vendor-views.pos.partials.modals._add-discount')
    @include('vendor-views.pos.partials.modals._short-cut-keys')

    <span id="route-vendor-pos-new-cart-id" data-url="{{ route('vendor.pos.new-cart-id') }}"></span>
    <span id="route-vendor-pos-clear-cart-ids" data-url="{{ route('vendor.pos.clear-cart-ids') }}"></span>
    <span id="route-vendor-pos-view-hold-orders" data-url="{{ route('vendor.pos.view-hold-orders') }}"></span>
    <span id="route-vendor-products-search-product" data-url="{{ route('vendor.pos.search-product') }}"></span>
    <span id="route-vendor-pos-change-customer" data-url="{{ route('vendor.pos.change-customer') }}"></span>
    <span id="route-vendor-pos-update-discount" data-url="{{ route('vendor.pos.update-discount') }}"></span>
    <span id="route-vendor-pos-coupon-discount" data-url="{{ route('vendor.pos.coupon-discount') }}"></span>
    <span id="route-vendor-pos-cancel-order" data-url="{{ route('vendor.pos.cancel-order') }}"></span>
    <span id="route-vendor-pos-quick-view" data-url="{{ route('vendor.pos.quick-view') }}"></span>
    <span id="route-vendor-pos-add-to-cart" data-url="{{ route('vendor.pos.add-to-cart') }}"></span>
    <span id="route-vendor-pos-remove-cart" data-url="{{ route('vendor.pos.cart-remove') }}"></span>
    <span id="route-vendor-pos-empty-cart" data-url="{{ route('vendor.pos.cart-empty') }}"></span>
    <span id="route-vendor-pos-update-quantity" data-url="{{ route('vendor.pos.quantity-update') }}"></span>
    <span id="route-vendor-pos-get-variant-price" data-url="{{ route('vendor.pos.get-variant-price') }}"></span>
    <span id="route-vendor-pos-change-cart-editable" data-url="{{ route('vendor.pos.change-cart').'/?cart_id=:value' }}"></span>
    <span id="route-vendor-pos-search-customer" data-url="{{ route('vendor.pos.search-customer') }}"></span>

    <span id="message-cart-word" data-text="{{ translate('cart') }}"></span>
    <span id="message-user-not-found" data-text="{{ translate('User not found') }}"></span>
    <span id="message-stock-out" data-text="{{ translate('stock_out') }}"></span>
    <span id="message-stock-id" data-text="{{ translate('in_stock') }}"></span>
    <span id="message-add-to-cart" data-text="{{ translate('add_to_cart') }}"></span>
    <span id="message-cart-updated" data-text="{{ translate('cart_updated') }}"></span>
    <span id="message-update-to-cart" data-text="{{ translate('update_to_cart') }}"></span>
    <span id="message-cart-is-empty" data-text="{{ translate('cart_is_empty') }}"></span>
    <span id="message-coupon-is-invalid" data-text="{{ translate('coupon_is_invalid') }}"></span>
    <span id="message-product-quantity-updated" data-text="{{ translate('product_quantity_updated') }}"></span>
    <span id="message-coupon-added-successfully" data-text="{{ translate('coupon_added_successfully') }}"></span>
    <span id="message-sorry-stock-limit-exceeded" data-text="{{ translate('sorry_stock_limit_exceeded') }}"></span>
    <span id="message-please-choose-all-the-options" data-text="{{ translate('please_choose_all_the_options') }}"></span>
    <span id="message-item-has-been-removed-from-cart" data-text="{{ translate('item_has_been_removed_from_cart') }}"></span>
    <span id="message-you-want-to-remove-all-items-from-cart" data-text="{{ translate('you_want_to_remove_all_items_from_cart') }}"></span>
    <span id="message-you-want-to-create-new-order" data-text="{{ translate('Want_to_create_new_order_for_another_customer') }}"></span>
    <span id="message-product-quantity-is-not-enough" data-text="{{ translate('product_quantity_is_not_enough') }}"></span>
    <span id="message-sorry-product-is-out-of-stock" data-text="{{ translate('sorry_product_is_out_of_stock') }}"></span>
    <span id="message-item-has-been-added-in-your-cart" data-text="{{ translate('item_has_been_added_in_your_cart') }}"></span>
    <span id="message-extra-discount-added-successfully" data-text="{{ translate('extra_discount_added_successfully') }}"></span>
    <span id="message-amount-can-not-be-negative-or-zero" data-text="{{ translate('amount_can_not_be_negative_or_zero') }}"></span>
    <span id="message-sorry-the-minimum-value-was-reached" data-text="{{ translate('sorry_the_minimum_value_was_reached') }}"></span>
    <span id="message-this-discount-is-not-applied-for-this-amount" data-text="{{ translate('this_discount_is_not_applied_for_this_amount') }}"></span>
    <span id="message-product-quantity-cannot-be-zero-in-cart" data-text="{{ translate('product_quantity_can_not_be_zero_or_less_than_zero_in_cart') }}"></span>
    <span id="message-enter-valid-amount" data-text="{{ translate('please_enter_a_valid_amount') }}"></span>
    <span id="message-less-than-total-amount" data-text="{{ translate('paid_amount_is_less_than_total_amount') }}"></span>

@endsection

@push('script_2')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/libs/printThis/printThis.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/vendor/pos-script.js') }}"></script>

    <script>
        "use strict";
        $(document).on('ready', function () {
            @if($order)
            $('#print-invoice').modal('show');
            @endif
        });
    </script>
@endpush
