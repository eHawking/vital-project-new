<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoyaltyBonus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'royalty_bonus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'set_day',
        'transaction_detail',
        'current_royalty_bonus',
        'distributed_bonus',
        'last_distributed_date',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the formatted date for the last distributed bonus.
     *
     * @return string|null
     */
    public function getLastDistributedDateFormattedAttribute()
    {
        return $this->last_distributed_date ? 
            date('F d, Y', strtotime($this->last_distributed_date)) : null;
    }

    /**
     * Update the current royalty bonus amount.
     *
     * @param float $amount
     * @return void
     */
    public function updateRoyaltyBonusAmount(float $amount)
    {
        $this->current_royalty_bonus = $amount;
        $this->save();
    }

    /**
     * Reset the current royalty bonus.
     *
     * @return void
     */
    public function resetCurrentRoyaltyBonus()
    {
        $this->current_royalty_bonus = 0;
        $this->save();
    }

    /**
     * Increment the distributed bonus amount.
     *
     * @param float $amount
     * @return void
     */
    public function incrementDistributedBonus(float $amount)
    {
        $this->distributed_bonus += $amount;
        $this->save();
    }
}
