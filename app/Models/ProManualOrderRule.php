<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProManualOrderRule extends Model
{
    use HasFactory;

    // Specify the table associated with the model (optional, but recommended if you want to ensure consistency)
    protected $table = 'pro_manual_order_rules';

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'country',            // Country for the rule (e.g., United Arab Emirates, Oman)
        'min_retail_price',   // Minimum retail price for the rule
        'max_retail_price',   // Maximum retail price for the rule (nullable for ranges that do not have a max)
        'quantity',           // Quantity of products the rule applies to
        'profit_amount',      // Profit amount to be applied for the given price range and quantity
        'max_profit',         // Maximum profit allowed (optional)
    ];

    // Optionally, add any relationships or methods related to this model if needed
}
