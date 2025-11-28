<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    use HasFactory;

    // Define the table name (optional if it matches Laravel's convention)
    protected $table = 'formulas';

    // Enable timestamps if `created_at` and `updated_at` columns exist
    public $timestamps = true;

    // Fillable fields to allow mass assignment
    protected $fillable = [
        'bv', 'pv', 'dds_ref_bonus', 'shop_bonus', 'shop_reference',
        'franchise_bonus', 'franchise_ref_bonus', 'city_ref_bonus', 'leadership_bonus',
        'promo', 'user_promo', 'seller_promo', 'shipping_expense', 'bilty_expense',
        'office_expense', 'event_expense', 'fuel_expense', 'visit_expense',
        'company_partner_bonus', 'product_partner_bonus', 'budget_promo',
        'product_partner_ref_bonus', 'vendor_ref_bonus', 'royalty_bonus'
    ];

    // Relationship to formula histories
    public function histories()
    {
        return $this->hasMany(FormulaHistory::class);
    }
}
