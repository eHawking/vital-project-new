<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaryShop extends Model
{
    protected $connection = 'mysql_store'; 
    protected $table = 'shops';             
    public $timestamps = false;             

    protected $fillable = [
        'seller_id',
        'name',
        'address',
        
    ];

   
    public function primarySeller()
    {
        return $this->belongsTo(PrimarySeller::class, 'seller_id', 'id');
    }
}