<div class="table-responsive datatable-custom">
    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
        <thead class="thead-light">
            <tr>
                <th class="table-column-pr-0">#</th>
                <th class="col-product-name">{{ translate('Product Name') }}</th>
                <th class="text-center">{{ translate('Price') }}</th>
                <th class="text-center col-quantity">{{ translate('Quantity') }}</th>
                <th class="text-center">{{ translate('Action') }}</th>
            </tr>
        </thead>
            <tbody>
                @forelse ($products as $key => $product)
                    <tr>
                        <td>{{ $key + $products->firstItem() }}</td>
                        <td class="col-product-name">
                            <div class="media align-items-center gap-2">
                                {{-- This image will trigger the lightbox --}}
                                <img src="{{ getStorageImages(path:$product->thumbnail_full_url,type:'backend-product')}}"
                                     alt="{{$product->name}}" class="avatar border onerror-image product-image-lightbox" style="cursor: pointer;">
                                
                                {{-- This link goes to the product detail page --}}
                                <a href="{{ route('product', $product->slug) }}" class="media-body title-color hover-c1" target="_blank" rel="noopener noreferrer">
                                    {{ Str::limit($product->name, 35) }}
                                </a>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $unitPrice = $product->unit_price;
                                $discount = $product->discount ?? 0;
                                $discountType = $product->discount_type ?? 'amount';
                                
                                // Calculate discounted price
                                if ($discount > 0) {
                                    if ($discountType == 'percent') {
                                        $discountedPrice = $unitPrice - ($unitPrice * $discount / 100);
                                    } else {
                                        $discountedPrice = $unitPrice - $discount;
                                    }
                                    $discountedPrice = max(0, $discountedPrice); // Ensure non-negative
                                } else {
                                    $discountedPrice = null;
                                }
                            @endphp
                            
                            @if($discountedPrice !== null)
                                <div class="d-flex flex-column align-items-center">
                                    <span class="text-muted" style="text-decoration: line-through; font-size: 0.85rem;">
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $unitPrice), currencyCode: getCurrencyCode()) }}
                                    </span>
                                    <span class="text-success font-weight-bold">
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discountedPrice), currencyCode: getCurrencyCode()) }}
                                    </span>
                                    <span class="badge badge-soft-danger badge-pill">
                                        @if($discountType == 'percent')
                                            -{{ $discount }}%
                                        @else
                                            -{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discount), currencyCode: getCurrencyCode()) }}
                                        @endif
                                    </span>
                                </div>
                            @else
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $unitPrice), currencyCode: getCurrencyCode()) }}
                            @endif
                        </td>
                        <td class="col-quantity">
                            <div class="input-group input-group-sm quantity-input justify-content-center" style="width: 130px; margin: 0 auto;">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-white quantity-btn minus-btn">
                                        <i class="tio-remove"></i>
                                    </button>
                                </div>
                                <input type="number" class="form-control text-center quantity"
                                       data-id="{{ $product->id }}" value="1" min="1" max="100">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-white quantity-btn plus-btn">
                                        <i class="tio-add"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                // Use discounted price if available, otherwise use unit price
                                $finalPrice = $discountedPrice ?? $unitPrice;
                                $variations = $product->variation ? json_decode($product->variation, true) : [];
                                $hasVariations = !empty($variations);
                            @endphp
                            <button type="button" class="btn btn-sm btn-primary {{ $hasVariations ? 'open-variation-modal' : 'add-to-cart' }}"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ usdToDefaultCurrency(amount: $finalPrice) }}"
                                    data-discount="{{ $discount }}"
                                    data-discount-type="{{ $discountType }}"
                                    @if($hasVariations)
                                    data-variations='{{ json_encode($variations) }}'
                                    @endif>
                                <i class="tio-shopping-cart mr-1"></i> {{ translate('Add') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img class="mb-3" src="{{dynamicAsset('public/assets/back-end/svg/illustrations/sorry.svg')}}" alt="No products" style="width: 5rem;">
                            <p class="mb-0 text-muted">{{ translate('No products found!') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
    </table>
</div>

<div class="card-footer">
    <div class="d-flex justify-content-center justify-content-sm-end flex-wrap">
        <nav aria-label="Product pagination" class="w-100 d-flex justify-content-center justify-content-sm-end">
            {{ $products->onEachSide(2)->links() }}
        </nav>
    </div>
</div>