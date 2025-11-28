<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DspBonus; // Import the DspBonus model

class DspManagementController extends Controller
{
    /**
     * Display the DSP Management dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch wallet balances from the database
        $balances = DB::table('bonus_wallets')->first();

        // Convert the object to an array for easier use in the Blade template
        $balances = $balances ? (array) $balances : [];

        // Calculate total DSP count
        $totalDSPCount = DB::table('users')->where('plan_id', 1)->count();

        // Calculate total collected amount (assuming each DSP costs 6800)
        $totalCollectedAmount = $totalDSPCount * 6800;

        // Calculate total current balance of all users
        $totalCurrentBalance = DB::table('users')->sum('balance');

        // Define the page title
        $pageTitle = __('DSP Management');

        return view('admin.dsp_management', compact('balances', 'pageTitle', 'totalDSPCount', 'totalCollectedAmount', 'totalCurrentBalance'));
    }

    /**
     * Update a specific wallet balance.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $walletName
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $walletName)
    {
        // Validate the input
        $request->validate([
            'balance' => 'required|numeric|min:0',
        ]);

        // Check if the wallet exists in the database
        if (!DB::getSchemaBuilder()->hasColumn('bonus_wallets', $walletName)) {
            return back()->withErrors(['error' => __('Invalid wallet specified.')]);
        }

        // Update the specified wallet's balance
        DB::table('bonus_wallets')->update([
            $walletName => $request->input('balance'),
        ]);

        return back()->with('success', __('Wallet balance updated successfully.'));
    }

    /**
     * Display the DSP Bonuses management page.
     *
     * @return \Illuminate\View\View
     */
    public function dspBonuses()
    {
        $pageTitle = 'DSP Bonuses';
        $bonuses = DspBonus::all();
        return view('admin.dsp_bonuses', compact('pageTitle', 'bonuses'));
    }

    /**
     * Update the DSP bonus amounts.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDspBonuses(Request $request)
    {
        $request->validate([
            'bonuses' => 'required|array',
            'bonuses.*.amount' => 'required|numeric|min:0',
        ]);

        foreach ($request->bonuses as $id => $data) {
            $bonus = DspBonus::findOrFail($id);
            $bonus->update(['bonus_amount' => $data['amount']]);
        }

        $notify[] = ['success', 'DSP Bonuses updated successfully'];
        return back()->withNotify($notify);
    }
}
