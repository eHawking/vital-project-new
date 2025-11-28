<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletHistory;
use Illuminate\Http\Request;

class BonusHistoryController extends Controller
{
    /**
     * Display a listing of the bonus history records.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pageTitle = 'Bonus Distribution History';
        $emptyMessage = 'No bonus history found.';
        
        // Eager load relationships to prevent the N+1 query problem
        $histories = WalletHistory::with('user', 'voucher')->latest()->paginate(getPaginate());
        
        return view('admin.bonus_history', compact('pageTitle', 'histories', 'emptyMessage'));
    }
}
