<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'first_name',
        'last_name',
        'company',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    // Address type constants
    const TYPE_BILLING = 'billing';
    const TYPE_SHIPPING = 'shipping';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeBilling($query)
    {
        return $query->where('type', self::TYPE_BILLING);
    }

    public function scopeShipping($query)
    {
        return $query->where('type', self::TYPE_SHIPPING);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getFormattedAddressAttribute()
    {
        $address = $this->address_line_1;
        if ($this->address_line_2) {
            $address .= "\n" . $this->address_line_2;
        }
        $address .= "\n" . $this->city . ', ' . $this->state . ' ' . $this->postal_code;
        $address .= "\n" . $this->country;
        
        return $address;
    }
}
