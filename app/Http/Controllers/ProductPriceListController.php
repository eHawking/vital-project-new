<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductPriceListController extends Controller
{
    public function index()
    {
        $this->middleware('customer');
        $products = Product::where('added_by', 'admin')
                    ->where('status', 1)
                    ->paginate(15);
        return view('theme-views.users-profile.price-list', compact('products'));
    }

    public function generatePDF()
    {
        $this->middleware('customer');
        $products = Product::where('added_by', 'admin')
                    ->where('status', 1)
                    ->get();

        $pdf = Pdf::loadView('theme-views.users-profile.price-list-pdf', compact('products'))
                 ->setPaper('a4', 'landscape');

        return $pdf->download('price-list-' . date('Ymd') . '.pdf');
    }
}