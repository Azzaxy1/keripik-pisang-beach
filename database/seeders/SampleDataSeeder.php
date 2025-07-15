<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
                'status' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Fashion and apparel',
                'status' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Books and literature',
                'status' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home improvement and garden supplies',
                'status' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // // Create brands
        // $brands = [
        //     [
        //         'name' => 'Samsung',
        //         'slug' => 'samsung',
        //         'description' => 'Samsung Electronics',
        //         'status' => true
        //     ],
        //     [
        //         'name' => 'Apple',
        //         'slug' => 'apple',
        //         'description' => 'Apple Inc.',
        //         'status' => true
        //     ],
        //     [
        //         'name' => 'Nike',
        //         'slug' => 'nike',
        //         'description' => 'Nike Sportswear',
        //         'status' => true
        //     ],
        //     [
        //         'name' => 'Adidas',
        //         'slug' => 'adidas',
        //         'description' => 'Adidas Sportswear',
        //         'status' => true
        //     ]
        // ];

        // foreach ($brands as $brandData) {
        //     Brand::create($brandData);
        // }

        // Create sample products
        $products = [
            [
                'name' => 'Samsung Galaxy S24',
                'slug' => 'samsung-galaxy-s24',
                'description' => 'Latest Samsung flagship smartphone with advanced features.',
                'short_description' => 'Premium smartphone with excellent camera and performance.',
                'sku' => 'SAM-S24-001',
                'price' => 79999.00,
                'sale_price' => 74999.00,
                'category_id' => 1,
                'brand_id' => 1,
                'stock_quantity' => 50,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Apple iPhone 15 Pro with titanium design and A17 Pro chip.',
                'short_description' => 'Premium iPhone with pro camera system.',
                'sku' => 'APL-IP15P-001',
                'price' => 134900.00,
                'sale_price' => null,
                'category_id' => 1,
                'brand_id' => 2,
                'stock_quantity' => 30,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'Nike Air Max 270',
                'slug' => 'nike-air-max-270',
                'description' => 'Comfortable running shoes with Air Max technology.',
                'short_description' => 'Stylish and comfortable running shoes.',
                'sku' => 'NIK-AM270-001',
                'price' => 12995.00,
                'sale_price' => 9999.00,
                'category_id' => 2,
                'brand_id' => 3,
                'stock_quantity' => 100,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => false
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'slug' => 'adidas-ultraboost-22',
                'description' => 'Premium running shoes with Boost technology.',
                'short_description' => 'High-performance running shoes.',
                'sku' => 'ADI-UB22-001',
                'price' => 17999.00,
                'sale_price' => null,
                'category_id' => 2,
                'brand_id' => 4,
                'stock_quantity' => 75,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create a sample product image
            ProductImage::create([
                'product_id' => $product->id,
                'image' => 'sample-product.jpg',
                'alt_text' => $product->name,
                'sort_order' => 0
            ]);
        }
    }
}
