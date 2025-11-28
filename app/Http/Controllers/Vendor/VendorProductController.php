<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $searchValue = $request->input('searchValue');
        $seller = auth('seller')->user();

        // An array of vendor types to fetch
        $vendorTypes = ['franchise', 'shop'];

        $products = Product::where([
                'added_by' => 'seller',
                'user_id' => $seller->id
            ])
            ->whereIn('vendor_type', $vendorTypes) // Use whereIn to get products of multiple vendor types
            ->withSum(['orderDetails' => function($query) use ($seller) {
                $query->whereHas('order', function($q) use ($seller) {
                    $q->where('seller_id', $seller->id)
                      ->where('delivery_status', 'delivered');
                });
            }], 'qty')
            ->when($type == 'new-request', function($q) {
                return $q->where('request_status', 0);
            })
            ->when($type == 'approved', function($q) {
                return $q->where('request_status', 1);
            })
            ->when($searchValue, function($query) use ($searchValue) {
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', "%$searchValue%")
                      ->orWhere('id', 'like', "%$searchValue%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        // The view name in the original file was 'vendor-views.order-history.list',
        // ensuring it points to the correct blade file.
        return view('vendor-views.order-history.list', compact('products', 'type'));
    }
}