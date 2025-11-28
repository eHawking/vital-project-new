<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ShippingSetupController extends Controller
{
    public function index()
    {
        $shipping = Shipping::firstOrNew([]);
        return view('admin-views.shipping-setup.index', compact('shipping'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_by_weight' => 'nullable|boolean',
            'free_shipping_over_weight' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_cost_over_weight' => 'nullable|numeric|min:0',
        ]);

        Shipping::updateOrCreate(
            ['id' => 1], // Assuming you have only one row for settings
            [
                'shipping_by_weight' => $request->has('shipping_by_weight'),
                'free_shipping_over_weight' => $request->free_shipping_over_weight,
                'shipping_cost' => $request->shipping_cost,
                'shipping_cost_over_weight' => $request->shipping_cost_over_weight,
            ]
        );

        ToastMagic::success('Shipping settings updated successfully!');
        return back();
    }
}
