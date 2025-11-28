<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusWallets extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bonus_wallets';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dsp_balance',
        'direct_reference_balance',
        'pair_balance',
        'reward_balance',
        'weekly_promo_balance',
        'budget_promo_balance',
        'event_balance',
        'visit_outside_balance',
        'shipping_balance',
        'office_balance',
        'it_balance',
        'pintapay_balance',
        'products_balance',
        'bbs_balance',
        'student_balance',
        'extra_balance',
        'company_balance',
    ];
}
