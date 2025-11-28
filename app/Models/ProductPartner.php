<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPartner extends Model
{
    protected $table = 'product_partner';
    public $timestamps = false;
    protected $fillable = ['product_details', 'product_partner_bonus', 'balance'];
}