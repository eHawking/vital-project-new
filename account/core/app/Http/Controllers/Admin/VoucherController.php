<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DspVoucher;
use App\Models\User;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $vouchers = DspVoucher::query()
            ->with(['generatedBy', 'usedBy'])
            ->when($request->search, function($query) use ($request) {
                $search = $request->search;
                $query->whereHas('generatedBy', function($q) use ($search) {
                    $q->where('username', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('firstname', 'like', "%$search%")
                      ->orWhere('lastname', 'like', "%$search%");
                });
            })
            ->when($request->date, function($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalVouchers = DspVoucher::count();
        $usedVouchers = DspVoucher::where('is_used', true)->count();

		$pageTitle = "DSP Vouchers";

        return view('admin.vouchers.index', compact('vouchers', 'totalVouchers', 'pageTitle', 'usedVouchers'));
    }
}