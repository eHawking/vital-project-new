<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formula;
use App\Models\FormulaHistory;
use Illuminate\Http\Request;

class FormulaController extends Controller
{
    // List all formulas with search functionality
    public function index(Request $request)
    {
        $search = $request->input('search');
        $fields = [
            'bv', 'pv', 'dds_ref_bonus', 'shop_bonus', 'shop_reference', 'franchise_bonus',
            'franchise_ref_bonus', 'city_ref_bonus', 'leadership_bonus', 'promo', 'user_promo',
            'seller_promo', 'shipping_expense', 'bilty_expense', 'office_expense', 'event_expense',
            'fuel_expense', 'visit_expense', 'company_partner_bonus', 'product_partner_bonus',
            'budget_promo', 'product_partner_ref_bonus', 'vendor_ref_bonus', 'royalty_bonus'
        ];

        $query = Formula::query();
        if ($search) {
            $query->where('id', $search);
        }

        $formulas = $query->paginate(10);
        return view('admin-views.formulas.index', compact('formulas', 'fields'));
    }

    // Show form for creating a new formula
    public function create()
    {
        return view('admin-views.formulas.create');
    }

    // Store a new formula without creating an initial history entry
    public function store(Request $request)
    {
        $data = $request->validate([
            'bv' => 'nullable|numeric', 'pv' => 'nullable|numeric',
            'dds_ref_bonus' => 'nullable|numeric', 'shop_bonus' => 'nullable|numeric',
            'shop_reference' => 'nullable|numeric', 'franchise_bonus' => 'nullable|numeric',
            'franchise_ref_bonus' => 'nullable|numeric', 'city_ref_bonus' => 'nullable|numeric',
            'leadership_bonus' => 'nullable|numeric', 'promo' => 'nullable|numeric',
            'user_promo' => 'nullable|numeric', 'seller_promo' => 'nullable|numeric',
            'shipping_expense' => 'nullable|numeric', 'bilty_expense' => 'nullable|numeric',
            'office_expense' => 'nullable|numeric', 'event_expense' => 'nullable|numeric',
            'fuel_expense' => 'nullable|numeric', 'visit_expense' => 'nullable|numeric',
            'company_partner_bonus' => 'nullable|numeric', 'product_partner_bonus' => 'nullable|numeric',
            'budget_promo' => 'nullable|numeric', 'product_partner_ref_bonus' => 'nullable|numeric',
            'vendor_ref_bonus' => 'nullable|numeric', 'royalty_bonus' => 'nullable|numeric'
        ]);

        Formula::create($data);

        return redirect()->route('admin.formulas.index')->with('success', 'Formula created successfully.');
    }

    // Show specific formula details along with its history
    public function show(Formula $formula)
    {
        $formula->load('histories');
        return view('admin-views.formulas.show', compact('formula'));
    }

    // Show edit form for specific formula
    public function edit(Formula $formula)
    {
        return view('admin-views.formulas.edit', compact('formula'));
    }

    // Update specific formula and record only changes in history
    public function update(Request $request, Formula $formula)
    {
        $data = $request->validate([
            'bv' => 'nullable|numeric', 'pv' => 'nullable|numeric',
            'dds_ref_bonus' => 'nullable|numeric', 'shop_bonus' => 'nullable|numeric',
            'shop_reference' => 'nullable|numeric', 'franchise_bonus' => 'nullable|numeric',
            'franchise_ref_bonus' => 'nullable|numeric', 'city_ref_bonus' => 'nullable|numeric',
            'leadership_bonus' => 'nullable|numeric', 'promo' => 'nullable|numeric',
            'user_promo' => 'nullable|numeric', 'seller_promo' => 'nullable|numeric',
            'shipping_expense' => 'nullable|numeric', 'bilty_expense' => 'nullable|numeric',
            'office_expense' => 'nullable|numeric', 'event_expense' => 'nullable|numeric',
            'fuel_expense' => 'nullable|numeric', 'visit_expense' => 'nullable|numeric',
            'company_partner_bonus' => 'nullable|numeric', 'product_partner_bonus' => 'nullable|numeric',
            'budget_promo' => 'nullable|numeric', 'product_partner_ref_bonus' => 'nullable|numeric',
            'vendor_ref_bonus' => 'nullable|numeric', 'royalty_bonus' => 'nullable|numeric'
        ]);

        // Track only the fields that have changed
        $changes = [];
        foreach ($data as $field => $newValue) {
            if ($formula->$field != $newValue) {
                $changes[$field] = [
                    'old' => $formula->$field,
                    'new' => $newValue
                ];
            }
        }

        // Update the formula with new data
        $formula->update($data);

        // Only create a history record if there are changes
        if (!empty($changes)) {
            $historyData = [
                'edit_count' => $formula->histories()->count() + 1,
                'changed_at' => now(),
                'details' => $changes,
                'formula_id' => $formula->id,
            ];
            FormulaHistory::create($historyData);
        }

        return redirect()->route('admin.formulas.show', $formula->id)
                         ->with('success', 'Formula updated successfully!');
    }

    // Delete specific formula and its history
    public function destroy(Formula $formula)
    {
        $formula->delete();
        return redirect()->route('admin.formulas.index')->with('success', 'Formula deleted successfully.');
    }
}
