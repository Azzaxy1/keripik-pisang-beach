<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
        'total_items'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_items' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Methods
    public function updateTotals()
    {
        $this->total_items = $this->items()->sum('quantity');
        $this->total_amount = $this->items()->sum(DB::raw('quantity * price'));
        $this->save();
    }
}
