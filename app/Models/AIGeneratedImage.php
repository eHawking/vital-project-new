<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIGeneratedImage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ai_generated_images';

    protected $fillable = [
        'filename',
        'path',
        'url',
        'type',
        'angle',
        'width',
        'height',
        'size',
        'prompt',
        'product_id',
        'generated_by',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'size' => 'integer',
        'product_id' => 'integer',
        'generated_by' => 'integer',
    ];

    /**
     * Get the product that owns the image
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who generated the image
     */
    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
