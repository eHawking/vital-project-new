<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoyaltyBonus extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'royalty_bonus';
    public $timestamps = false;
    protected $fillable = ['current_royalty_bonus'];
}