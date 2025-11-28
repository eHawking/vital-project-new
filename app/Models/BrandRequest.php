<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'brand_name',
        'image_alt_text',
        'image_path',
    ];
}
