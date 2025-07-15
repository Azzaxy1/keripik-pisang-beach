<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'logo',
        'website',
        'status',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        $imagePath = $this->logo ?: $this->image;
        
        if (!$imagePath) {
            return asset('images/no-image.png');
        }
        
        // If it already starts with storage/, just add asset()
        if (str_starts_with($imagePath, 'storage/')) {
            return asset($imagePath);
        }
        
        // Otherwise, assume it's in brands folder
        return asset('storage/brands/' . $imagePath);
    }

    public function getLogoUrlAttribute()
    {
        return $this->getImageUrlAttribute();
    }
}
