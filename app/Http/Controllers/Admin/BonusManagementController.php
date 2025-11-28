<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusManagement;
use Illuminate\Http\Request;
use App\Models\WalletsHistory;

class BonusManagementController extends Controller
{
    /**
     * Display a listing of the bonuses.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $bonuses = BonusManagement::query()
            ->when($search, function ($query, $search) {
                $query->where('bonus_type', 'LIKE', "%$search%")
                      ->orWhere('description', 'LIKE', "%$search%");
            })
            ->paginate(10);

        return view('admin-views.bonus_management.index', compact('bonuses'));
    }

    /**
     * Show the form for creating a new bonus.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin-views.bonus_management.create');
    }

    /**
     * Store a newly created bonus in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'bonus_type' => 'required|string|max:255',
            'description' => 'required|string',
            'statement' => 'required|string',
        ]);

        BonusManagement::create($request->all());

        return redirect()->route('admin.bonus-management.index')
            ->with('success', 'Bonus created successfully.');
    }

    /**
     * Show the form for editing the specified bonus.
     *
     * @param  \App\Models\BonusManagement  $bonusManagement
     * @return \Illuminate\View\View
     */
    public function edit(BonusManagement $bonusManagement)
{
    $availableTags = [
        '{username}', '{customer_name}', '{vendor_id}', '{vendor_username}',
        '{vendor_name}', '{shop_name}', '{city}', '{products}',
        '{bonus_type}', '{amount}', '{qty}', '{month_name}',
        '{bv}', '{pv}', '{dds_ref_bonus}', '{shop_bonus}',
        '{shop_reference}', '{franchise_bonus}', '{franchise_ref_bonus}',
        '{city_ref_bonus}', '{leadership_bonus}', '{promo}', '{user_promo}',
        '{seller_promo}', '{shipping_expense}', '{bilty_expense}',
        '{office_expense}', '{event_expense}', '{fuel_expense}',
        '{visit_expense}', '{company_partner_bonus}', '{product_partner_bonus}',
        '{budget_promo}', '{product_partner_ref_bonus}', '{vendor_ref_bonus}',
        '{royalty_bonus}'
    ];

    return view('admin-views.bonus_management.edit', compact('bonusManagement', 'availableTags'));
}

    /**
     * Update the specified bonus in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BonusManagement  $bonusManagement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, BonusManagement $bonusManagement)
    {
        $request->validate([
            'bonus_type' => 'required|string|max:255',
            'description' => 'required|string',
            'statement' => 'required|string',
        ]);

        $bonusManagement->update($request->all());

        return redirect()->route('admin.bonus-management.index')
            ->with('success', 'Bonus updated successfully.');
    }

    /**
     * Remove the specified bonus from the database.
     *
     * @param  \App\Models\BonusManagement  $bonusManagement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BonusManagement $bonusManagement)
    {
        $bonusManagement->delete();

        return redirect()->route('admin.bonus-management.index')
            ->with('success', 'Bonus deleted successfully.');
    }
	
	public function walletsHistory()
{
    $walletsHistory = \App\Models\WalletsHistory::orderBy('id', 'desc')->paginate(10);

    // Calculate the line budget using the budget value now stored in the 'products' JSON data.
    $walletsHistory->getCollection()->transform(function ($record) {
        if (!empty($record->products) && is_array($record->products)) {
            $processedProducts = [];
            foreach ($record->products as $product) {
                // Get the stored budget and quantity for the product.
                $stored_budget = $product['budget'] ?? 0;
                $quantity = $product['quantity'] ?? 1;

                // Calculate the total for this line: quantity * stored budget.
                $product['line_budget'] = $quantity * $stored_budget;
                $processedProducts[] = $product;
            }
            $record->products = $processedProducts;
        }
        return $record;
    });

    return view('admin-views.bonus_management.wallets_history', compact('walletsHistory'));
}
	
}
