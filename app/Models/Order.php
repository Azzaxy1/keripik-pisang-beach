<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'payment_status',
        'payment_method',
        'payment_proof',
        'bank_account_info',
        'payment_id',
        'shipping_address_id',
        'billing_address_id',
        'shipping_address',
        'billing_address',
        'notes',
        'currency',
        'courier_service',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'completed_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    // Order status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_COMPLETED = 'completed';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'billing_address_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    // Accessors
    public function getCalculatedSubtotalAttribute()
    {
        return $this->items()->sum('subtotal');
    }

    public function getFormattedOrderNumberAttribute()
    {
        return 'ORD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Methods
    public function generateOrderNumber()
    {
        $this->order_number = 'ORD-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
        $this->save();
    }

    /**
     * Kembalikan stok produk ketika order dibatalkan
     */
    public function restoreStock()
    {
        foreach ($this->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->incrementStock($item->quantity);
            }
        }
    }

    /**
     * Update sold count produk ketika order completed
     */
    public function updateProductSoldCount()
    {
        foreach ($this->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->increment('sold_count', $item->quantity);
            }
        }
    }

    /**
     * Mark order as completed by customer
     */
    public function markAsCompleted()
    {
        $oldStatus = $this->getAttribute('status');

        // Update status dan completed_at secara langsung tanpa trigger override update
        $this->setAttribute('status', self::STATUS_COMPLETED);
        $this->setAttribute('completed_at', now());
        $this->save();

        // Update sold count produk jika status berubah dari delivered ke completed
        if ($oldStatus === self::STATUS_DELIVERED) {
            $this->updateProductSoldCount();
        }
    }

    /**
     * Get available courier services
     */
    public static function getCourierServices()
    {
        return [
            'jne' => 'JNE',
            'pos' => 'Pos Indonesia',
            'tiki' => 'TIKI',
            'jnt' => 'J&T Express',
            'sicepat' => 'SiCepat',
            'anteraja' => 'AnterAja',
            'gosend' => 'GoSend',
            'grab' => 'GrabExpress'
        ];
    }

    /**
     * Check if order can be marked as completed by customer
     */
    public function canBeCompletedByCustomer()
    {
        return $this->getAttribute('status') === self::STATUS_DELIVERED;
    }
}
