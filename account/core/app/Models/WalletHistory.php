<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    use HasFactory;

    protected $table = 'wallets_history';

    protected $fillable = [
        'user_id',
        'voucher_id',
        'distributed_bonuses',
        'remaining_bonuses',
    ];

    protected $casts = [
        'distributed_bonuses' => 'json',
        'remaining_bonuses' => 'json',
    ];

    /**
     * Get the user that owns the history record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the voucher associated with the history record.
     */
    public function voucher()
    {
        // Assuming you have a DspVoucher model
        return $this->belongsTo(DspVoucher::class, 'voucher_id');
    }
}
