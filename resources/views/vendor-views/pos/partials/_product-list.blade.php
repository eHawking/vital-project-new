<div class="product-overlay" id="product-overlay"></div>
<div class="product-loading d-none" id="product-loading">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="card-body pt-2">
    @if(count($products) > 0)
        <div class="pos-item-wrap">
            @foreach($products as $product)
                @include('vendor-views.pos.partials._single-product', ['product'=>$product])
            @endforeach
        </div>
    @else
        <div class="p-4 bg-light rounded text-center">
            <div class="py-5">
                <img src="{{dynamicAsset('public/assets/back-end/svg/illustrations/sorry.svg')}}" width="80" alt="No products">
                <div class="mx-auto my-3 max-w-353px">
                    <h5 class="text-muted">{{ translate('Currently_no_product_available_by_this_name') }}</h5>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="pos-pagination-wrapper">
    <div class="d-flex justify-content-center">
        {{ $products->withQueryString()->onEachSide(2)->links() }}
    </div>
</div>
