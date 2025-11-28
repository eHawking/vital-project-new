<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\FranchiseOrder;
use App\Models\ShopOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    /**
     * Display the vendor's order history.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sellerId = Auth::guard('seller')->id();

        $franchiseOrders = FranchiseOrder::where('ordered_by', $sellerId)->get();
        $shopOrders = ShopOrder::where('ordered_by', $sellerId)->get();

        $orderHistory = $franchiseOrders->concat($shopOrders)
            ->sortByDesc('created_at')
            ->paginate(20);

        return view('vendor-views.order-history.index', compact('orderHistory'));
    }
}
