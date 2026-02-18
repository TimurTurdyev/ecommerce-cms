<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUSES = [
        self::STATUS_PENDING => 'Ожидание',
        self::STATUS_PROCESSING => 'В обработке',
        self::STATUS_SHIPPED => 'Отправлен',
        self::STATUS_COMPLETED => 'Завершён',
        self::STATUS_CANCELLED => 'Отменён',
    ];
    protected $fillable = [
        'invoice_no',
        'invoice_prefix',
        'user_id',
        'firstname',
        'lastname',
        'email',
        'telephone',
        'payment_firstname',
        'payment_lastname',
        'payment_address',
        'payment_city',
        'payment_postcode',
        'payment_country',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_address',
        'shipping_city',
        'shipping_postcode',
        'shipping_country',
        'comment',
        'total',
        'status',
    ];
    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }
}
