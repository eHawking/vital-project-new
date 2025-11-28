<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimarySeller extends Model
{
    protected $connection = 'mysql_store';  
    protected $table = 'sellers';           
    public $timestamps = false;             

    protected $fillable = [
        'username',
        'vendor_id',
        'vendor_type',
        'city',
        
    ];

    
    public function primaryUser()
    {
        return $this->belongsTo(PrimaryUser::class, 'username', 'username');
    }

    
    public function primaryShop()
    {
        return $this->hasOne(PrimaryShop::class, 'seller_id', 'id');
    }
}