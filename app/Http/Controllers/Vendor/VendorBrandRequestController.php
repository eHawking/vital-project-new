<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class VendorBrandRequestController extends Controller
{
    /**
     * Show the form to request a new brand.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('vendor-views.request-brand.index');
    }

    /**
     * Handle the submission of a new brand request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        // Ensure the vendor (seller) is authenticated using the 'seller' guard
        if (!auth('seller')->check()) {
            Toastr::error('You must be logged in to submit a brand request.'); // Error message using Toastr
            return redirect()->route('vendor.auth.login');
        }

        // Get the authenticated vendor's ID
        $vendorId = auth('seller')->id(); // Corrected to use 'seller' guard
        
        // Validate the incoming request
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'image_alt_text' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brand_requests', 'public');
        }

        // Create a new brand request
        BrandRequest::create([
            'vendor_id' => $vendorId, // Use the authenticated vendor's ID
            'brand_name' => $request->brand_name,
            'image_alt_text' => $request->image_alt_text,
            'image_path' => $imagePath,
        ]);

        // Success message using Toastr
        Toastr::success('Brand request submitted successfully.');

        // Redirect back with success message
        return redirect()->route('vendor.request.brand');
    }
}
