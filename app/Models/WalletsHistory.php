<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletsHistory extends Model
{
	protected $table = 'wallets_history';
    protected $fillable = [
        'username', 'type', 'customer_name', 'vendor_id', 'vendor_username', 
        'vendor_name', 'shop_name', 'city', 'products', 'bonus_types', 'created_at'
    ];

    protected $casts = [
        'products' => 'array',
        'bonus_types' => 'array',
    ];
	
	public $timestamps = false;
}