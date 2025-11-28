<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPartner extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'company_partner';
    public $timestamps = false;
    protected $fillable = ['ownership_share', 'company_partner_bonus', 'balance'];
}