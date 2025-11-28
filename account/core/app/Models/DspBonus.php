<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DspBonus Model
 *
 * Represents the dsp_bonuses table in the database.
 */
class DspBonus extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dsp_bonuses';

    /**
     * The attributes that are mass assignable.
     * These are the fields you can fill using methods like `create()` or `update()`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bonus_type',
        'bonus_amount',
    ];

    /**
     * The attributes that should be cast.
     * This ensures the bonus_amount is treated as a decimal number.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bonus_amount' => 'decimal:2',
    ];

    /**
     * The name of the "created at" column.
     * Overrides Laravel's default 'created_at'.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     * Overrides Laravel's default 'updated_at'.
     *
     * @var string
     */
    const UPDATED_AT = 'updated';
}
