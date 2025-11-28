<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_by_weight',
        'free_shipping_over_weight',
        'shipping_cost',
        'shipping_cost_over_weight',
    ];

    protected $casts = [
        'shipping_by_weight' => 'boolean',
        'free_shipping_over_weight' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipping_cost_over_weight' => 'decimal:2',
    ];
}
