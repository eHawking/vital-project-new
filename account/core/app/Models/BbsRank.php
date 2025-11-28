<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BbsRank
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $requirement
 * @property string $reward
 * @property string $reward_image
 * @property string|null $budget
 * @property string|null $next_rank
 *
 * @package App\Models
 */
class BbsRank extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bbs_ranks';

    /**
     * Indicates if the model should be timestamped.
     * The 'ranks' table does not have 'created_at' and 'updated_at' columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'requirement',
        'reward',
        'reward_image',
        'budget',
        'next_rank',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requirement' => 'integer',
    ];
}
