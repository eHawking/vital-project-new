@extends('theme-views.layouts.app')

@section('title', translate('Products Price List').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')

<div class="container section-gap pt-0">
	   <style>
        .active > .page-link, .page-link.active {
            background-color: var(--bs-pagination-active-bg);
        }
    </style>
    <!-- Profile Sidebar Section -->
    @include('theme-views.partials._profile-aside')

	<!-- Add this button above the table in price-list.blade.php -->
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('price-list.pdf') }}" 
       class="btn btn-primary" 
       style="background-color: var(--bs-pagination-active-bg)">
        <i class="bi bi-download me-2"></i>Download Price List PDF
    </a>
</div>
    <!-- Check if there are no products -->
    @if($products->isEmpty())
        <p class="text-center">No products available.</p>
    @else
        <!-- Product Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">PV</th>
                        <th scope="col">BV</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>
                                <img src="{{ getStorageImages($product->thumbnail_full_url, 'product') }}" 
                                     class="avatar rounded-circle" alt="{{ $product->name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pv }}</td>
                            <td>{{ $product->bv }}</td>
                            <td>{{ getCurrencySymbol(getCurrencyCode()) }} {{ number_format($product->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection