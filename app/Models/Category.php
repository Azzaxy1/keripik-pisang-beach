<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'status',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // If image starts with 'storage/', it's already the full path
            if (str_starts_with($this->image, 'storage/')) {
                return asset($this->image);
            }
            // Otherwise, prepend the storage path
            return asset('storage/categories/' . $this->image);
        }
        return 'https://via.placeholder.com/300x300?text=No+Image';
    }
}
