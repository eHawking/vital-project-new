<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DspFormulas extends Model
{
    use HasFactory;
	
	protected $table = 'dsp_formulas';

    protected $fillable = [
        'dsp_price',
        'dsp_formula',
        'direct_reference_formula',
        'pair_formula',
        'reward_formula',
        'weekly_promo_formula',
        'budget_promo_formula',
        'event_formula',
        'visit_outside_formula',
        'shipping_formula',
        'office_formula',
        'it_formula',
        'pintapay_formula',
        'products_formula',
        'bbs_formula',
        'student_formula',
        'extra_formula',
        'company_formula',
    ];
}