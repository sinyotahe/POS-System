<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public const string TYPE_IN = 'in';

    public const string TYPE_OUT = 'out';

    public const string TYPE_SALE = 'sale';

    public const string TYPE_VOID = 'void';

    public $timestamps = false;

    protected $fillable = [
        'product_id', 'type', 'quantity', 'before_stock', 'after_stock',
        'reference_type', 'reference_id', 'user_id', 'branch_id', 'created_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    public function scopeIncrease($query)
    {
        return $query->whereIn('type', [self::TYPE_IN, self::TYPE_VOID]);
    }

    public function scopeDecrease($query)
    {
        return $query->whereIn('type', [self::TYPE_OUT, self::TYPE_SALE]);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
