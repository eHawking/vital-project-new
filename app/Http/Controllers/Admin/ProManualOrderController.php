<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProManualOrderRule;

class ProManualOrderController extends Controller
{
    /**
     * Display a listing of the manual order rules.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all manual order rules from the database
        $rules = ProManualOrderRule::all();

        // Return the view, passing the rules data
        return view('admin-views.pro-manual-order-rules.index', compact('rules'));
    }

    /**
     * Show the form for creating a new manual order rule.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return a view for creating a new rule
        return view('admin-views.pro-manual-order-rules.create');
    }

    /**
     * Store a newly created manual order rule in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'country' => 'required|string',
            'min_retail_price' => 'required|numeric',
            'max_retail_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'profit_amount' => 'required|numeric',
            'max_profit' => 'nullable|numeric',
        ]);

        // Create a new manual order rule
        ProManualOrderRule::create($data);

        // Redirect back to the index with a success message
        return redirect()->route('admin.pro-manual-order.index')->with('success', 'Manual order rule created successfully.');
    }

    /**
     * Show the form for editing a specific manual order rule.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Find the rule by its ID or fail
        $rule = ProManualOrderRule::findOrFail($id);

        // Return the view for editing the rule, passing the rule data
        return view('admin-views.pro-manual-order-rules.edit', compact('rule'));
    }

    /**
     * Update the specified manual order rule in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'country' => 'required|string',
            'min_retail_price' => 'required|numeric',
            'max_retail_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'profit_amount' => 'required|numeric',
            'max_profit' => 'nullable|numeric',
        ]);

        // Find the rule by ID and update it
        $rule = ProManualOrderRule::findOrFail($id);
        $rule->update($data);

        // Redirect back to the index with a success message
        return redirect()->route('admin.pro-manual-order.index')->with('success', 'Manual order rule updated successfully.');
    }

    /**
     * Remove the specified manual order rule from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the rule by its ID and delete it
        $rule = ProManualOrderRule::findOrFail($id);
        $rule->delete();

        // Redirect back to the index with a success message
        return redirect()->route('admin.pro-manual-order.index')->with('success', 'Manual order rule deleted successfully.');
    }

}
