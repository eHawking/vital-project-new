<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulaHistory extends Model
{
    use HasFactory;

    // Define the table name (optional if it matches Laravel's convention)
    protected $table = 'formula_histories';

    // Disable automatic timestamps for this model
    public $timestamps = false;

    // Fillable fields for mass assignment
    protected $fillable = [
        'formula_id', 'edit_count', 'changed_at', 'details'
    ];

    // Cast the details column to JSON
    protected $casts = [
        'details' => 'array',
        'changed_at' => 'datetime',
    ];

    // Relationship to the formula model
    public function formula()
    {
        return $this->belongsTo(Formula::class);
    }
}
