<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'category_id',
        'brand_id',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'weight',
        'dimensions',
        'status',
        'featured',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'tags'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'status' => 'boolean',
        'featured' => 'boolean',
        'dimensions' => 'array',
        'tags' => 'array'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    // Stock management methods
    public function updateStockStatus()
    {
        $newStatus = 'in_stock';
        
        if (!$this->manage_stock) {
            $newStatus = 'in_stock';
        } elseif ($this->stock_quantity <= 0) {
            $newStatus = 'out_of_stock';
        } elseif ($this->stock_quantity <= 5) { // Low stock threshold
            $newStatus = 'low_stock';
        }
        
        // Hanya update jika status berubah
        if ($this->stock_status !== $newStatus) {
            $this->updateQuietly(['stock_status' => $newStatus]);
        }
    }

    public function decrementStock($quantity)
    {
        if ($this->manage_stock && $this->stock_quantity >= $quantity) {
            $this->decrement('stock_quantity', $quantity);
            $this->updateStockStatus();
            return true;
        }
        return false;
    }

    public function incrementStock($quantity)
    {
        if ($this->manage_stock) {
            $this->increment('stock_quantity', $quantity);
            $this->updateStockStatus();
            return true;
        }
        return false;
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price ? $this->sale_price : $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getFeaturedImageAttribute()
    {
        // Check if featured_image field is set
        if ($this->attributes['featured_image'] ?? null) {
            if (str_starts_with($this->attributes['featured_image'], 'storage/')) {
                return asset($this->attributes['featured_image']);
            }
            return asset('storage/products/' . $this->attributes['featured_image']);
        }
        
        // Fallback to first image from images relationship
        $firstImage = $this->images()->first();
        if ($firstImage) {
            if ($firstImage->image) {
                return str_starts_with($firstImage->image, 'storage/') 
                    ? asset($firstImage->image) 
                    : asset('storage/products/' . $firstImage->image);
            }
        }
        
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }
}
