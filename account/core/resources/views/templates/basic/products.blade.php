@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- Include Modern Auth Theme CSS for public pages -->
    @include($activeTemplate . 'css.modern-auth')

    <section class="product-section padding-top padding-bottom mb-5">
        <div class="container">
            <h2 class="text-center text-white mb-5 display-5 fw-bold">@lang('Our Products')</h2>
            
            <div class="d-flex justify-content-center mb-5 flex-wrap gap-2">
                <a class="btn btn-sm @if (!$categoryId) btn-primary @else btn-outline-light border-secondary @endif rounded-pill px-4" 
                   href="{{ route('products') }}">@lang('All Products')</a>
                @foreach ($categories as $category)
                    <a class="btn btn-sm @if ($categoryId == $category->id) btn-primary @else btn-outline-light border-secondary @endif rounded-pill px-4"
                        href="{{ route('products', $category->id) }}">{{ __($category->name) }}</a>
                @endforeach
            </div>

            <div class="row g-4 justify-content-center">
                @foreach ($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="premium-card h-100 overflow-hidden" style="background: var(--card-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: 20px; transition: transform 0.3s ease;">
                            <div class="product-thumb position-relative">
                                <img src="{{ getImage(getFilePath('products') . '/' . $product->thumbnail, getFileSize('products')) }}" alt="products" class="w-100 object-fit-cover" style="height: 250px;">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <a class="btn btn-sm btn-light rounded-circle shadow-sm image"
                                        href="{{ getImage(getFilePath('products') . '/' . $product->thumbnail, getFileSize('products')) }}"><i
                                            class="las la-expand"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="product-title mb-2">
                                    <a href="{{ route('product.details', ['id' => $product->id, 'slug' => slug($product->name)]) }}" class="text-white text-decoration-none">
                                        {{ __(shortDescription($product->name, 35)) }}
                                    </a>
                                </h5>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    @if ($product->quantity >= 0)
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill">@lang('In Stock')</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 rounded-pill">@lang('Out of Stock')</span>
                                    @endif
                                    <span class="fs-5 fw-bold text-info">{{ showAmount($product->price) }}</span>
                                </div>
                                
                                <a class="account--btn text-center mt-auto text-decoration-none"
                                    href="{{ route('product.details', ['id' => $product->id, 'slug' => slug($product->name)]) }}">@lang('View Details')</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($products->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ paginateLinks($products) }}
                </div>
            @endif
        </div>
    </section>
    
    @push('style')
    <style>
        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .product-thumb img {
            transition: transform 0.5s ease;
        }
        .premium-card:hover .product-thumb img {
            transform: scale(1.05);
        }
    </style>
    @endpush
@endsection
