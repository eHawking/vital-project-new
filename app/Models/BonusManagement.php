<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusManagement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bonus_type',
        'description',
        'statement',
    ];

    /**
     * Define any additional relationships or methods as needed.
     */

    /**
     * Get a human-readable description of the SQL statement.
     *
     * @return string
     */
    public function getFormattedStatementAttribute()
    {
        return str_replace(['{userId}', '{amount}'], ['<userId>', '<amount>'], $this->statement);
    }
}
