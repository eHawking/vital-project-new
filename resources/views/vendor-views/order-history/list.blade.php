@extends('layouts.vendor.app')

@section('title', translate($type=='new-request'?'pending_products':($type=='approved'?'approved_products':'product_list')))

@section('content')

{{-- MODIFIED: Added a style block to make the pagination component itself wrap on small screens --}}
<style>
    .pagination {
        flex-wrap: wrap;
    }
</style>

<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2 flex-wrap">
            <img src="{{ dynamicAsset('public/assets/back-end/img/inhouse-product-list.png') }}" alt="" width="24">
            {{ translate('products_list') }}
            <span class="badge badge-soft-dark rounded-pill fz-14 ml-1">
                {{ $products->total() }}
            </span>
        </h2>
    </div>

    <div class="card mt-20">
        <div class="card-header">
            <h5 class="card-title">{{ translate('Product List') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="search" name="searchValue" class="form-control"
                           placeholder="{{ translate('search_by_Product_Name') }}"
                           value="{{ request('searchValue') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">{{ translate('search') }}</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>{{ translate('SL') }}</th>
                            <th style="min-width: 250px;">{{ translate('Product_Name') }}</th>
                            <th>{{ translate('Stock') }}</th>
                            <th class="d-none d-md-table-cell">{{ translate('PV') }}</th>
                            <th class="d-none d-md-table-cell">{{ translate('BV') }}</th>
                            <th>{{ translate('Sold') }}</th>
                            <th>{{ translate('Verify_Status') }}</th>
                            <th>{{ translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                        <tr>
                            <td class="text-center">{{ $products->firstItem() + $key }}</td>
                            <td>
                                <a href="{{ route('vendor.products.view', $product->id) }}" class="d-flex align-items-center">
                                    <img src="{{ getStorageImages(path:$product->thumbnail_full_url, type:'backend-product') }}"
                                         class="img-thumbnail rounded mr-2" width="60" alt="">
                                    <span>{{ Str::limit($product->name, 30) }}</span>
                                </a>
                            </td>
                            <td class="text-center">
                                @if($product->product_type == 'physical')
                                    <span class="badge badge-info">{{ $product->current_stock }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ translate('N/A') }}</span>
                                @endif
                            </td>
                            <td class="text-center d-none d-md-table-cell">{{ $product->pv }}</td>
                            <td class="text-center d-none d-md-table-cell">{{ $product->bv }}</td>
                            <td class="text-center">{{ $product->order_details_sum_qty ?? 0 }}</td>
                            <td class="text-center">
                                @if($product->request_status == 1)
                                    <span class="badge badge-soft-success">{{ translate('approved') }}</span>
                                @else
                                    <span class="badge badge-soft-warning">{{ translate('pending') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a class="btn btn-outline-info btn-sm"
                                   href="{{ route('vendor.products.view', $product->id) }}"
                                   title="{{ translate('view') }}">
                                    <i class="tio-invisible"></i> {{ translate('View') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="p-4">
                                    <img src="{{ dynamicAsset('public/assets/back-end/img/empty-box.png') }}"
                                         class="w-120 mb-3" alt="">
                                    <p class="text-muted">{{ translate('No_products_found') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->total() > 0)
            <div class="mt-3 d-flex flex-wrap justify-content-center justify-content-md-end">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection