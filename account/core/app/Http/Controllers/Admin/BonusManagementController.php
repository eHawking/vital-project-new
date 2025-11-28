<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusManagement;
use Illuminate\Http\Request;

class BonusManagementController extends Controller
{
    /**
     * Display the bonus management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pageTitle = 'Bonus Management';
        // highlight-start
        // Fetch all bonus records to be displayed dynamically.
        $bonuses = BonusManagement::all();
        return view('admin.bonus_management', compact('pageTitle', 'bonuses'));
        // highlight-end
    }

    /**
     * Update the bonus management settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validation rules for the incoming request
        $request->validate([
            'bonus_type' => 'required|array',
            'bonus_type.*' => 'required|string', // Removed specific 'in' validation to allow any bonus type
            'description' => 'required|array',
            'description.*' => 'nullable|string',
            'statement' => 'required|array',
            'statement.*' => 'nullable|string',
        ]);

        $bonusTypes = $request->input('bonus_type');

        // Loop through the submitted bonus types and update or create the records
        foreach ($bonusTypes as $key => $type) {
            BonusManagement::updateOrCreate(
                ['bonus_type' => $type],
                [
                    'description' => $request->description[$key],
                    'statement'   => $request->statement[$key],
                ]
            );
        }

        $notify[] = ['success', 'Bonus settings updated successfully.'];
        return back()->withNotify($notify);
    }
}