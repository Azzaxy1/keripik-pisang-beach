<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'alt_text',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        $imagePath = $this->image;
        
        if (!$imagePath) {
            return asset('images/no-image.png');
        }
        
        // If it already starts with storage/, just add asset()
        if (str_starts_with($imagePath, 'storage/')) {
            return asset($imagePath);
        }
        
        // Otherwise, assume it's in products folder
        return asset('storage/products/' . $imagePath);
    }
}
