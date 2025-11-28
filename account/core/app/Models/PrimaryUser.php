<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaryUser extends Model
{
    protected $connection = 'mysql_store';
    protected $table = 'users'; 
    public $timestamps = false; 
    protected $fillable = [
        'name', 'f_name', 'l_name', 'phone', 'email', 'password', 'street_address', 'country', 'city' 
    ];

	protected $hidden = [
        'password',
    ];
}