<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured products
        $featuredProducts = Product::active()
            ->featured()
            ->with(['images', 'category', 'brand'])
            ->take(8)
            ->get();
            
        // Latest products
        $latestProducts = Product::active()
            ->with(['images', 'category', 'brand'])
            ->latest()
            ->take(12)
            ->get();
            
        // Top categories
        $topCategories = Category::active()
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();
            
        // Best selling products
        $bestSellingProducts = Product::active()
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->with(['images', 'category', 'brand'])
            ->take(8)
            ->get();

        return view('frontend.home', compact(
            'featuredProducts',
            'latestProducts', 
            'topCategories',
            'bestSellingProducts'
        ));
    }

    public function shop(Request $request)
    {
        $query = Product::active()->with(['images', 'category', 'brand', 'reviews']);
        
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }
        
        // Price filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popularity':
                $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(12);
        
        // Get filter data
        $categories = Category::active()->whereNull('parent_id')->get();
        $brands = Brand::active()->get();
        $priceRange = Product::active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        
        return view('frontend.shop', compact('products', 'categories', 'brands', 'priceRange'));
    }

    public function productDetail($slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['images', 'category', 'brand', 'attributes', 'reviews.user'])
            ->firstOrFail();
            
        // Related products
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images', 'category', 'brand'])
            ->take(4)
            ->get();
            
        return view('frontend.product-detail', compact('product', 'relatedProducts'));
    }

    public function categoryProducts($slug)
    {
        $category = Category::active()
            ->where('slug', $slug)
            ->firstOrFail();
            
        $products = Product::active()
            ->where('category_id', $category->id)
            ->with(['images', 'category', 'brand'])
            ->paginate(12);
            
        return view('frontend.category-products', compact('category', 'products'));
    }

    public function brandProducts($slug)
    {
        $brand = Brand::active()
            ->where('slug', $slug)
            ->firstOrFail();
            
        $products = Product::active()
            ->where('brand_id', $brand->id)
            ->with(['images', 'category', 'brand'])
            ->paginate(12);
            
        return view('frontend.brand-products', compact('brand', 'products'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('shop');
        }
        
        $products = Product::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['images', 'category', 'brand'])
            ->paginate(12);
            
        return view('frontend.search-results', compact('products', 'query'));
    }
}
