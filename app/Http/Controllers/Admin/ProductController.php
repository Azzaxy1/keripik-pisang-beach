<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images'])
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // Cek kategori yang statusnya true atau active
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'nullable|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'weight' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
            'featured' => 'nullable|boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->status === 'active' ? true : false;
        $data['featured'] = $request->has('featured');

        // Generate SKU automatically if not provided
        $data['sku'] = $request->sku ?: $this->generateSKU($request->name);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        $product = Product::create($data);

        // Handle additional images
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'attributes', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $product->load(['images', 'attributes']);

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'stock_quantity' => 'required|integer|min:0',
            'manage_stock' => 'boolean',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'weight' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
            'featured' => 'nullable|boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->status === 'active' ? true : false;
        $data['featured'] = $request->has('featured');

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old featured image if exists
            if ($product->featured_image && !str_contains($product->featured_image, 'placeholder')) {
                $oldImagePath = str_replace('storage/', '', $product->featured_image);
                $fullPath = storage_path('app/public/' . $oldImagePath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        if ($request->has('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $product->update($data);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        // Handle product attributes
        if ($request->has('attributes')) {
            // Delete existing attributes
            $product->attributes()->delete();
            $this->handleAttributes($product, $request->input('attributes', []));
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete product images
        foreach ($product->images as $image) {
            $imagePath = storage_path('app/public/products/' . $image->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function generateSKU($productName)
    {
        // Create base SKU from product name
        $baseSKU = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 6));

        // Add timestamp to make it unique
        $timestamp = time();
        $sku = $baseSKU . $timestamp;

        // Ensure uniqueness by checking database
        $counter = 1;
        $originalSKU = $sku;
        while (Product::where('sku', $sku)->exists()) {
            $sku = $originalSKU . $counter;
            $counter++;
        }

        return $sku;
    }

    private function uploadImage($image, $directory = 'products')
    {
        $path = storage_path('app/public/' . $directory);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($path, $filename);

        return 'storage/' . $directory . '/' . $filename;
    }

    private function handleImageUploads(Product $product, array $images)
    {
        foreach ($images as $index => $image) {
            $imageUrl = $this->uploadImage($image);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $imageUrl,
                'alt_text' => $product->name,
                'sort_order' => $index
            ]);
        }
    }

    private function handleAttributes(Product $product, array $attributes)
    {
        foreach ($attributes as $attribute) {
            if (!empty($attribute['name']) && !empty($attribute['value'])) {
                $product->attributes()->create([
                    'attribute_name' => $attribute['name'],
                    'attribute_value' => $attribute['value']
                ]);
            }
        }
    }

    public function deleteImage(Request $request)
    {
        $image = ProductImage::findOrFail($request->image_id);

        // Delete file
        $imagePath = storage_path('app/public/products/' . $image->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }
}
