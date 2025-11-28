<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbsUser extends Model
{
    use HasFactory;

    protected $table = 'bbs_users';

    protected $fillable = [
        'user_id',
        'bbs_username',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}