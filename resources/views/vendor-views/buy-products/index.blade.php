@extends('layouts.vendor.app')

@section('title', translate('Buy Products'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .lightbox-content {
            max-width: 85%;
            max-height: 85%;
            border-radius: 5px;
        }

        /* Responsive Table Styles */
        .product-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 991px) {
            .table-responsive {
                border: 0;
            }
            
            .col-product-name {
                min-width: 180px;
            }
            
            .product-image-lightbox {
                width: 50px !important;
                height: 50px !important;
            }
        }

        @media (max-width: 767px) {
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            .col-product-name {
                min-width: 160px;
            }

            .quantity-input {
                flex-wrap: nowrap;
            }

            .quantity-input .quantity {
                max-width: 50px !important;
                font-size: 0.875rem;
            }
            
            .quantity-btn {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.875rem;
            }
            
            .btn--primary {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 575px) {
            .h1 {
                font-size: 1.5rem;
            }
            
            .card-title {
                font-size: 1.1rem;
            }
            
            /* Fix pagination overflow */
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.125rem;
            }
            
            .page-item {
                margin: 0.125rem 0;
            }
            
            .page-link {
                padding: 0.375rem 0.625rem;
                font-size: 0.875rem;
            }
            
            /* Ellipsis styling */
            .page-item.disabled .page-link {
                padding: 0.375rem 0.5rem;
            }
        }
        
        /* Pagination responsive fixes */
        @media (max-width: 767px) {
            .pagination {
                font-size: 0.875rem;
                gap: 0.25rem;
            }
            
            .page-link {
                padding: 0.5rem 0.75rem;
            }
            
            /* Hide text on prev/next for mobile, keep icons if any */
            .page-item .page-link {
                min-width: 38px;
            }
        }
        
        /* General pagination improvements */
        .pagination {
            margin-bottom: 0;
        }
        
        .page-item.disabled .page-link {
            cursor: default;
            background-color: transparent;
            border-color: transparent;
        }
        
        .page-item.active .page-link {
            z-index: 1;
        }
        
        /* Modal backdrop and scrolling fixes for mobile */
        @media (max-width: 575px) {
            .modal.show .modal-dialog {
                transform: none;
            }
            
            body.modal-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-end mb-3">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">
                    <span class="page-header-icon">
                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/product-list.png') }}" class="width-26px" alt="">
                    </span>
                    <span>{{ translate('Buy Products') }}</span>
                </h1>
            </div>
            <div class="col-sm-auto">
                <a class="btn btn-primary" href="{{ route('vendor.order.history') }}">
                    <i class="tio-history mr-1"></i> {{ translate('Order History') }}
                </a>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-header-title">
                <i class="tio-filter-list mr-1"></i> {{ translate('Product List') }}
            </h5>
        </div>
        <div class="card-body">
            <form class="mb-3">
                <div class="input-group input-group-merge input-group-flush">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="tio-search"></i>
                        </div>
                    </div>
                    <input type="text" id="search-input" class="form-control" placeholder="{{ translate('Search products...') }}">
                    <button class="btn btn-primary" type="button" id="search-button">
                        {{ translate('Search') }}
                    </button>
                </div>
            </form>

            <div id="product-list" data-currency-symbol="{{ getCurrencySymbol() }}">
                @include('vendor-views.buy-products.partials.product-list')
            </div>

            <form class="product-form text-start" action="{{ route('vendor.buy.products.create-order') }}" method="POST" id="summary_form">
                @csrf
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="tio-shopping-cart"></i> {{ translate('Order Summary') }}
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table mb-0" id="order-summary">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('Product') }}</th>
                                        <th>{{ translate('Variation') }}</th>
                                        <th class="text-right">{{ translate('Price') }}</th>
                                        <th class="text-center">{{ translate('Quantity') }}</th>
                                        <th class="text-right">{{ translate('Total') }}</th>
                                        <th class="text-center">{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="empty-summary">
                                        <td colspan="6" class="text-center py-5">
                                            <img src="{{dynamicAsset('public/assets/back-end/svg/illustrations/sorry.svg')}}" alt="Empty" class="mb-3" style="width: 5rem;">
                                            <p class="text-muted">{{ translate('Your cart is empty!') }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="hidden-fields-container"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-sm-6 mb-2 mb-sm-0">
                                <h4 class="mb-0">{{ translate('Subtotal:') }} <span id="subtotal" class="text-primary font-weight-bold">{{ getCurrencySymbol() }}0.00</span></h4>
                            </div>
                            <div class="col-sm-6 text-sm-right">
                                <button type="submit" class="btn btn--primary px-4">
                                    <i class="tio-checkmark-circle"></i> {{ translate('Place Order') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- This is the HTML for the lightbox container --}}
<div class="lightbox" id="image-lightbox">
    <img src="" alt="Product Image" class="lightbox-content">
</div>

{{-- Variation Selection Modal --}}
<div class="modal fade" id="variationModal" tabindex="-1" role="dialog" aria-labelledby="variationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="variationModalLabel">
                    <i class="tio-shopping-cart mr-1"></i> {{ translate('Select Variation') }}
                </h5>
                <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal" aria-label="Close">
                    <i class="tio-clear tio-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-4 pb-3 border-bottom">
                    <small class="text-muted d-block mb-1">{{ translate('Product') }}</small>
                    <h6 id="modal-product-name" class="font-weight-bold mb-0"></h6>
                </div>
                
                <div class="form-group">
                    <label class="input-label">{{ translate('Choose Variation') }}</label>
                    <div id="variation-cards" class="row">
                        <!-- Variation cards will be inserted here -->
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-quantity" class="input-label">{{ translate('Quantity') }}</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-white" id="modal-quantity-minus">
                                        <i class="tio-remove"></i>
                                    </button>
                                </div>
                                <input type="number" class="form-control text-center font-weight-bold" id="modal-quantity" value="1" min="1">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-white" id="modal-quantity-plus">
                                        <i class="tio-add"></i>
                                    </button>
                                </div>
                            </div>
                            <small id="stock-warning" class="form-text" style="display: none;"></small>
                        </div>
                    </div>
                </div>
                
                <div class="card card-bordered mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <span class="text-muted small mb-1">{{ translate('Unit Price') }}</span>
                                    <h4 class="mb-0 text-body" id="modal-unit-price">{{ getCurrencySymbol() }}0.00</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column align-items-end">
                                    <span class="text-muted small mb-1">{{ translate('Total Amount') }}</span>
                                    <h3 class="mb-0 text-primary font-weight-bold" id="modal-total-price">{{ getCurrencySymbol() }}0.00</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-white" data-dismiss="modal">
                    {{ translate('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="modal-add-to-cart">
                    <i class="tio-shopping-cart mr-1"></i> {{ translate('Add to Cart') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Variation Cards - Admin Theme Style */
.variation-card {
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    border: 2px solid #e7eaf3;
    border-radius: 0.5rem;
    padding: 1.25rem 1rem;
    text-align: center;
    background: #fff;
    position: relative;
}

.variation-card:hover {
    border-color: #377dff;
    box-shadow: 0 0.375rem 1.5rem 0 rgba(140, 152, 164, 0.125);
    transform: translateY(-2px);
}

.variation-card.selected {
    border-color: #377dff;
    background-color: rgba(55, 125, 255, 0.05);
    box-shadow: 0 0 0 0.2rem rgba(55, 125, 255, 0.25);
}

.variation-card .variation-type {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e2022;
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.variation-card .variation-price {
    font-size: 1rem;
    color: #377dff;
    font-weight: 600;
}

.variation-card.selected .variation-type {
    color: #377dff;
}

.variation-card .check-icon {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    color: #00c9a7;
    font-size: 1.5rem;
    display: none;
}

.variation-card.selected .check-icon {
    display: block;
}

/* Mobile Responsive for Variation Cards */
@media (max-width: 767px) {
    .variation-card {
        padding: 1rem 0.75rem;
    }
    
    .variation-card .variation-type {
        font-size: 1rem;
    }
    
    .variation-card .variation-price {
        font-size: 0.875rem;
    }
    
    .variation-card .check-icon {
        font-size: 1.25rem;
        top: 0.375rem;
        right: 0.375rem;
    }
    
    /* Modal responsive on tablet */
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-header, .modal-footer {
        padding: 0.75rem 1rem;
    }
    
    .modal-title {
        font-size: 1rem;
    }
}

@media (max-width: 575px) {
    #variation-cards .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    /* Modal fit to phone screen */
    .modal-dialog {
        margin: 0;
        max-width: 100%;
        height: 100vh;
    }
    
    .modal-content {
        height: 100%;
        border-radius: 0;
        border: 0;
    }
    
    .modal-body {
        padding: 1rem 0.875rem;
        overflow-y: auto;
        max-height: calc(100vh - 120px);
    }
    
    .modal-header {
        padding: 0.75rem 1rem;
        flex-shrink: 0;
    }
    
    .modal-footer {
        padding: 0.75rem 1rem;
        flex-shrink: 0;
    }
    
    .modal-title {
        font-size: 1rem;
    }
    
    #variation-cards {
        margin: 0 -0.25rem;
    }
    
    #variation-cards .col-md-4, 
    #variation-cards .col-sm-6 {
        padding: 0 0.25rem;
        margin-bottom: 0.5rem;
    }
    
    .card-bordered {
        margin-left: 0;
        margin-right: 0;
    }
    
    .input-group-merge {
        width: 100% !important;
        max-width: 100% !important;
    }
}
</style>

@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const productContainer = document.getElementById('product-list');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const summaryTableBody = document.getElementById('order-summary').querySelector('tbody');
    const subtotalElement = document.getElementById('subtotal');
    const hiddenFieldsContainer = document.getElementById('hidden-fields-container');
    const currencySymbol = productContainer.dataset.currencySymbol;
    const lightbox = document.getElementById('image-lightbox');
    
    // Modal elements
    let currentModalProduct = null;

    function formatPrice(price) {
        return currencySymbol + parseFloat(price).toFixed(2);
    }
    
    function applyDiscount(price, discount, discountType) {
        if (discount > 0) {
            if (discountType === 'percent') {
                return price - (price * discount / 100);
            } else {
                return price - discount;
            }
        }
        return price;
    }

    function updateSummary() {
        const rows = summaryTableBody.querySelectorAll('tr:not(#empty-summary)');
        document.getElementById('empty-summary').style.display = rows.length ? 'none' : 'table-row';

        let currentSubtotal = Array.from(rows).reduce((sum, row) => {
            const totalText = row.querySelector('.product-total').textContent;
            const total = parseFloat(totalText.replace(currencySymbol, ''));
            return sum + (isNaN(total) ? 0 : total);
        }, 0);

        subtotalElement.textContent = formatPrice(currentSubtotal);
    }

    function updateHiddenInputs(productId, quantity, variation = null, remove = false) {
        const uniqueKey = variation ? `${productId}_${variation}` : productId;
        let productInput = hiddenFieldsContainer.querySelector(`input[name="product_id[]"][data-key="${uniqueKey}"]`);
        let quantityInput = hiddenFieldsContainer.querySelector(`input[name="quantity[]"][data-key="${uniqueKey}"]`);
        let variationInput = hiddenFieldsContainer.querySelector(`input[name="variation[]"][data-key="${uniqueKey}"]`);

        if (remove) {
            productInput?.remove();
            quantityInput?.remove();
            variationInput?.remove();
            return;
        }

        if (!productInput) {
            productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'product_id[]';
            productInput.value = productId;
            productInput.dataset.key = uniqueKey;
            hiddenFieldsContainer.appendChild(productInput);
        }

        if (!quantityInput) {
            quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity[]';
            quantityInput.dataset.key = uniqueKey;
            hiddenFieldsContainer.appendChild(quantityInput);
        }
        quantityInput.value = quantity;

        if (!variationInput) {
            variationInput = document.createElement('input');
            variationInput.type = 'hidden';
            variationInput.name = 'variation[]';
            variationInput.dataset.key = uniqueKey;
            hiddenFieldsContainer.appendChild(variationInput);
        }
        variationInput.value = variation || '';
    }

    function fetchProducts(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (data) {
                productContainer.innerHTML = data;
                attachProductActionEvents();
            },
            error: function (error) {
                console.error('Error fetching products:', error);
                toastr.error('Could not fetch products.');
            }
        });
    }

    function attachProductActionEvents() {
        // Handle modal opening for products with variations
        document.querySelectorAll('.open-variation-modal').forEach(button => {
            button.onclick = function() {
                const productId = this.dataset.id;
                const productName = this.dataset.name;
                const variations = JSON.parse(this.dataset.variations);
                const discount = parseFloat(this.dataset.discount) || 0;
                const discountType = this.dataset.discountType;
                
                currentModalProduct = {
                    id: productId,
                    name: productName,
                    variations: variations,
                    discount: discount,
                    discountType: discountType,
                    selectedVariation: null
                };
                
                // Set product name
                document.getElementById('modal-product-name').textContent = productName;
                
                // Create variation cards
                const variationCardsContainer = document.getElementById('variation-cards');
                variationCardsContainer.innerHTML = '';
                
                variations.forEach((variation, index) => {
                    const originalPrice = parseFloat(variation.price);
                    const discountedPrice = applyDiscount(originalPrice, discount, discountType);
                    
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-md-4 col-sm-6 mb-3';
                    
                    const cardDiv = document.createElement('div');
                    cardDiv.className = 'variation-card position-relative';
                    cardDiv.dataset.variationType = variation.type;
                    cardDiv.dataset.price = discountedPrice;
                    cardDiv.dataset.stock = variation.qty || 0;
                    cardDiv.dataset.sku = variation.sku || '';
                    
                    cardDiv.innerHTML = `
                        <div class="check-icon">
                            <i class="tio-checkmark-circle"></i>
                        </div>
                        <div class="variation-type">${variation.type}</div>
                        <div class="variation-price">${formatPrice(discountedPrice)}</div>
                    `;
                    
                    cardDiv.onclick = function() {
                        // Remove selection from all cards
                        document.querySelectorAll('.variation-card').forEach(card => {
                            card.classList.remove('selected');
                        });
                        
                        // Select this card
                        this.classList.add('selected');
                        
                        // Update current modal product
                        currentModalProduct.selectedVariation = {
                            type: this.dataset.variationType,
                            price: parseFloat(this.dataset.price),
                            stock: parseInt(this.dataset.stock),
                            sku: this.dataset.sku
                        };
                        
                        // Update prices
                        updateModalPrices();
                    };
                    
                    colDiv.appendChild(cardDiv);
                    variationCardsContainer.appendChild(colDiv);
                });
                
                // Reset quantity and prices
                document.getElementById('modal-quantity').value = 1;
                document.getElementById('modal-unit-price').textContent = formatPrice(0);
                document.getElementById('modal-total-price').textContent = formatPrice(0);
                document.getElementById('stock-warning').style.display = 'none';
                
                // Show modal
                $('#variationModal').modal('show');
            };
        });
        
        // Handle add to cart for products without variations
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.onclick = function() {
                const productId = this.dataset.id;
                const productName = this.dataset.name;
                const productPrice = parseFloat(this.dataset.price);
                const quantityInput = document.querySelector(`input.quantity[data-id="${productId}"]`);
                const quantity = parseInt(quantityInput.value);

                if (isNaN(quantity) || quantity < 1) {
                    toastr.warning('Please enter a valid quantity.');
                    return;
                }

                addToCartSummary(productId, productName, null, productPrice, quantity);
                quantityInput.value = 1;
            };
        });

        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.onclick = function () {
                // Find the input within the same input-group
                const inputGroup = this.closest('.input-group');
                const input = inputGroup.querySelector('.quantity');
                if (input) {
                    let value = parseInt(input.value) || 1;
                    if (this.classList.contains('plus-btn')) {
                        value++;
                    } else if (this.classList.contains('minus-btn')) {
                        value--;
                    }
                    if (value < 1) value = 1;
                    if (value > 100) value = 100; // Max limit
                    input.value = value;
                }
            };
        });
        
        document.querySelectorAll('.product-image-lightbox').forEach(image => {
            image.onclick = function() {
                if(lightbox) {
                    const lightboxImg = lightbox.querySelector('.lightbox-content');
                    lightboxImg.src = this.src;
                    lightbox.style.display = 'flex';
                }
            };
        });
    }
    
    // Helper function to add items to cart summary
    function addToCartSummary(productId, productName, variation, price, quantity) {
        const variationLabel = variation || '<span class="badge badge-soft-secondary">N/A</span>';
        const uniqueKey = variation ? `${productId}_${variation}` : productId;
        let existingRow = summaryTableBody.querySelector(`#summary-${uniqueKey}`);
        
        if (existingRow) {
            const quantityCell = existingRow.querySelector('.summary-quantity');
            const newQuantity = parseInt(quantityCell.dataset.quantity) + quantity;
            quantityCell.textContent = newQuantity;
            quantityCell.dataset.quantity = newQuantity;
            existingRow.querySelector('.product-total').textContent = formatPrice(price * newQuantity);
            updateHiddenInputs(productId, newQuantity, variation);
        } else {
            const row = summaryTableBody.insertRow(0);
            row.id = `summary-${uniqueKey}`;
            row.innerHTML = `
                <td>
                    <div class="media align-items-center">
                        <div class="media-body">
                            <h5 class="text-hover-primary mb-0">${productName}</h5>
                        </div>
                    </div>
                </td>
                <td>
                    ${variation ? '<span class="badge badge-soft-info">' + variation + '</span>' : '<span class="badge badge-soft-secondary">N/A</span>'}
                </td>
                <td class="text-right">${formatPrice(price)}</td>
                <td class="text-center">
                    <span class="summary-quantity badge badge-soft-dark px-3 py-2" data-quantity="${quantity}">${quantity}</span>
                </td>
                <td class="text-right">
                    <span class="product-total font-weight-bold">${formatPrice(price * quantity)}</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-from-cart" data-id="${productId}" data-variation="${variation || ''}" title="${{!! json_encode(translate('Remove')) !!}}">
                        <i class="tio-delete-outlined"></i>
                    </button>
                </td>
            `;
            updateHiddenInputs(productId, quantity, variation);

            row.querySelector('.remove-from-cart').addEventListener('click', function () {
                const removeVariation = this.dataset.variation;
                row.remove();
                updateHiddenInputs(productId, 0, removeVariation, true);
                updateSummary();
            });
        }
        updateSummary();
    }
    
    // Function to update modal prices
    function updateModalPrices() {
        if (!currentModalProduct || !currentModalProduct.selectedVariation) {
            return;
        }
        
        const unitPrice = currentModalProduct.selectedVariation.price;
        const quantity = parseInt(document.getElementById('modal-quantity').value) || 1;
        const stock = currentModalProduct.selectedVariation.stock;
        const totalPrice = unitPrice * quantity;
        
        // Update displayed prices
        document.getElementById('modal-unit-price').textContent = formatPrice(unitPrice);
        document.getElementById('modal-total-price').textContent = formatPrice(totalPrice);
        
        // Show stock warning if quantity exceeds stock
        const stockWarning = document.getElementById('stock-warning');
        if (quantity > stock) {
            stockWarning.textContent = `⚠️ Only ${stock} items available in stock`;
            stockWarning.style.display = 'block';
            stockWarning.classList.add('text-danger');
        } else {
            stockWarning.style.display = 'none';
        }
    }
    
    // Modal event listeners
    document.getElementById('modal-quantity-minus').addEventListener('click', function() {
        const input = document.getElementById('modal-quantity');
        let value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
            updateModalPrices();
        }
    });
    
    document.getElementById('modal-quantity-plus').addEventListener('click', function() {
        const input = document.getElementById('modal-quantity');
        let value = parseInt(input.value);
        input.value = value + 1;
        updateModalPrices();
    });
    
    // Update prices when quantity input changes
    document.getElementById('modal-quantity').addEventListener('input', function() {
        updateModalPrices();
    });
    
    document.getElementById('modal-add-to-cart').addEventListener('click', function() {
        if (!currentModalProduct) return;
        
        if (!currentModalProduct.selectedVariation) {
            toastr.warning('Please select a variation.');
            return;
        }
        
        const quantity = parseInt(document.getElementById('modal-quantity').value);
        const stock = currentModalProduct.selectedVariation.stock;
        const price = currentModalProduct.selectedVariation.price;
        const variationType = currentModalProduct.selectedVariation.type;
        
        if (quantity > stock) {
            toastr.error(`Only ${stock} items available in stock.`);
            return;
        }
        
        if (isNaN(quantity) || quantity < 1) {
            toastr.warning('Please enter a valid quantity.');
            return;
        }
        
        addToCartSummary(
            currentModalProduct.id,
            currentModalProduct.name,
            variationType,
            price,
            quantity
        );
        
        $('#variationModal').modal('hide');
        toastr.success('Product added to cart!');
    });

    if(lightbox) {
        lightbox.onclick = function() {
            this.style.display = 'none';
        };
    }

    function attachPaginationEvents() {
        productContainer.addEventListener('click', function(e) {
            if (e.target.matches('.pagination a')) {
                e.preventDefault();
                fetchProducts(e.target.href);
            }
        });
    }

    function handleSearch() {
        const query = searchInput.value.trim();
        const url = query ? `{{ route('vendor.buy.products') }}?search=${encodeURIComponent(query)}` : `{{ route('vendor.buy.products') }}`;
        fetchProducts(url);
    }
    
    searchButton.addEventListener('click', handleSearch);
    searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    });

    attachProductActionEvents();
    attachPaginationEvents();
});
</script>
@endpush