<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static withAvailability() include booking product availability
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'product_id',
        'booked_on'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'booked_on' => 'datetime'
    ];

    /**
     * A booking belongs to a product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Product availability scope
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithAvailability(Builder $query): Builder
    {
        return $query->joinSub("
                SELECT
                    products.id as product_id,
                    CASE
                      WHEN COUNT(b) < products.capacity THEN true
                      ELSE false
                    END as is_available
                FROM products
                LEFT JOIN bookings b on products.id = b.product_id
                GROUP BY products.id
            ", 'product_availabilities', 'product_availabilities.product_id', '=', 'bookings.product_id');
    }
}
