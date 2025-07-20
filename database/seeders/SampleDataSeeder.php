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
                'name' => 'Keripik Pisang Original',
                'slug' => 'keripik-pisang-original',
                'description' => 'Keripik pisang dengan rasa original yang gurih dan renyah',
                'status' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Keripik Pisang Asin',
                'slug' => 'keripik-pisang-asin',
                'description' => 'Keripik pisang dengan rasa asin yang gurih',
                'status' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Keripik Pisang Coklat',
                'slug' => 'keripik-pisang-coklat',
                'description' => 'Keripik pisang dengan lapisan cokelat yang manis',
                'status' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Keripik Pisang Balado',
                'slug' => 'keripik-pisang-balado',
                'description' => 'Keripik pisang dengan bumbu balado khas Padang',
                'status' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Keripik Pisang Pedas',
                'slug' => 'keripik-pisang-pedas',
                'description' => 'Keripik pisang dengan bumbu pedas yang menggugah selera',
                'status' => true,
                'sort_order' => 5
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
            // KERIPIK PISANG ORIGINAL
            [
                'name' => 'Keripik Pisang Original 1kg',
                'slug' => 'keripik-pisang-original-1kg',
                'description' => 'Keripik pisang original dengan rasa gurih dan renyah. Dibuat dari pisang pilihan berkualitas tinggi dari Cinangka, Banten. Cocok untuk camilan sehari-hari.',
                'short_description' => 'Keripik pisang original gurih dan renyah 1kg',
                'sku' => 'KPO-1KG-001',
                'price' => 15000.00,
                'sale_price' => null,
                'category_id' => 1,
                'brand_id' => null,
                'stock_quantity' => 50,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'Keripik Pisang Original 2kg',
                'slug' => 'keripik-pisang-original-2kg',
                'description' => 'Keripik pisang original ukuran 2kg dengan rasa gurih dan renyah. Dibuat dari pisang pilihan berkualitas tinggi dari Cinangka, Banten. Cocok untuk keluarga besar.',
                'short_description' => 'Keripik pisang original gurih dan renyah 2kg',
                'sku' => 'KPO-2KG-001',
                'price' => 30000.00,
                'sale_price' => null,
                'category_id' => 1,
                'brand_id' => null,
                'stock_quantity' => 30,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'Keripik Pisang Original 5kg',
                'slug' => 'keripik-pisang-original-5kg',
                'description' => 'Keripik pisang original ukuran 5kg dengan rasa gurih dan renyah. Cocok untuk acara atau reseller.',
                'short_description' => 'Keripik pisang original gurih dan renyah 5kg',
                'sku' => 'KPO-5KG-001',
                'price' => 70000.00,
                'sale_price' => 65000.00,
                'category_id' => 1,
                'brand_id' => null,
                'stock_quantity' => 20,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => false
            ],
            [
                'name' => 'Keripik Pisang Original 10kg',
                'slug' => 'keripik-pisang-original-10kg',
                'description' => 'Keripik pisang original ukuran 10kg dengan rasa gurih dan renyah. Cocok untuk grosir atau acara besar.',
                'short_description' => 'Keripik pisang original gurih dan renyah 10kg',
                'sku' => 'KPO-10KG-001',
                'price' => 135000.00,
                'sale_price' => 125000.00,
                'category_id' => 1,
                'brand_id' => null,
                'stock_quantity' => 15,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => false
            ],

            // KERIPIK PISANG ASIN
            [
                'name' => 'Keripik Pisang Asin 1kg',
                'slug' => 'keripik-pisang-asin-1kg',
                'description' => 'Keripik pisang dengan rasa asin yang gurih. Dibuat dari pisang raja pilihan dengan bumbu asin yang pas. Camilan favorit semua kalangan.',
                'short_description' => 'Keripik pisang asin gurih 1kg',
                'sku' => 'KPA-1KG-001',
                'price' => 16000.00,
                'sale_price' => null,
                'category_id' => 2,
                'brand_id' => null,
                'stock_quantity' => 40,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'Keripik Pisang Asin 2kg',
                'slug' => 'keripik-pisang-asin-2kg',
                'description' => 'Keripik pisang asin ukuran 2kg dengan rasa yang gurih dan asin. Cocok untuk keluarga.',
                'short_description' => 'Keripik pisang asin gurih 2kg',
                'sku' => 'KPA-2KG-001',
                'price' => 32000.00,
                'sale_price' => null,
                'category_id' => 2,
                'brand_id' => null,
                'stock_quantity' => 25,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => false
            ],

            // KERIPIK PISANG COKLAT
            [
                'name' => 'Keripik Pisang Coklat 1kg',
                'slug' => 'keripik-pisang-coklat-1kg',
                'description' => 'Keripik pisang dengan lapisan cokelat yang manis dan lezat. Perpaduan sempurna antara keripik pisang renyah dengan cokelat premium.',
                'short_description' => 'Keripik pisang coklat manis 1kg',
                'sku' => 'KPC-1KG-001',
                'price' => 25000.00,
                'sale_price' => 22000.00,
                'category_id' => 3,
                'brand_id' => null,
                'stock_quantity' => 25,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'Keripik Pisang Coklat 2kg',
                'slug' => 'keripik-pisang-coklat-2kg',
                'description' => 'Keripik pisang coklat ukuran 2kg dengan lapisan cokelat premium yang manis.',
                'short_description' => 'Keripik pisang coklat manis 2kg',
                'sku' => 'KPC-2KG-001',
                'price' => 48000.00,
                'sale_price' => 45000.00,
                'category_id' => 3,
                'brand_id' => null,
                'stock_quantity' => 20,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => false
            ],

            // KERIPIK PISANG BALADO
            [
                'name' => 'Keripik Pisang Balado 1kg',
                'slug' => 'keripik-pisang-balado-1kg',
                'description' => 'Keripik pisang dengan bumbu balado khas Padang yang pedas dan gurih. Dibuat dengan cabai merah dan bumbu rempah pilihan.',
                'short_description' => 'Keripik pisang balado pedas gurih 1kg',
                'sku' => 'KPB-1KG-001',
                'price' => 18000.00,
                'sale_price' => null,
                'category_id' => 4,
                'brand_id' => null,
                'stock_quantity' => 35,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'Keripik Pisang Balado 2kg',
                'slug' => 'keripik-pisang-balado-2kg',
                'description' => 'Keripik pisang balado ukuran 2kg dengan bumbu balado khas Padang yang autentik.',
                'short_description' => 'Keripik pisang balado pedas gurih 2kg',
                'sku' => 'KPB-2KG-001',
                'price' => 36000.00,
                'sale_price' => null,
                'category_id' => 4,
                'brand_id' => null,
                'stock_quantity' => 20,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => false
            ],

            // KERIPIK PISANG PEDAS
            [
                'name' => 'Keripik Pisang Pedas 1kg',
                'slug' => 'keripik-pisang-pedas-1kg',
                'description' => 'Keripik pisang dengan bumbu pedas yang menggugah selera. Dibuat dengan cabai pilihan dan rempah-rempah khas Banten. Cocok untuk pecinta pedas.',
                'short_description' => 'Keripik pisang pedas menggugah selera 1kg',
                'sku' => 'KPP-1KG-001',
                'price' => 17000.00,
                'sale_price' => null,
                'category_id' => 5,
                'brand_id' => null,
                'stock_quantity' => 35,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => true
            ],
            [
                'name' => 'Keripik Pisang Pedas 2kg',
                'slug' => 'keripik-pisang-pedas-2kg',
                'description' => 'Keripik pisang pedas ukuran 2kg dengan bumbu pedas yang pas di lidah.',
                'short_description' => 'Keripik pisang pedas menggugah selera 2kg',
                'sku' => 'KPP-2KG-001',
                'price' => 34000.00,
                'sale_price' => null,
                'category_id' => 5,
                'brand_id' => null,
                'stock_quantity' => 25,
                'stock_status' => 'in_stock',
                'status' => true,
                'featured' => false
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create a sample product image with actual image files
            $imageName = '';
            switch ($product->slug) {
                // Original
                case 'keripik-pisang-original-1kg':
                    $imageName = 'keripik-original-1kg.jpg';
                    break;
                case 'keripik-pisang-original-2kg':
                    $imageName = 'keripik-original-2kg.jpg';
                    break;
                case 'keripik-pisang-original-5kg':
                    $imageName = 'keripik-original-5kg.jpg';
                    break;
                case 'keripik-pisang-original-10kg':
                    $imageName = 'keripik-original-10kg.jpg';
                    break;

                // Asin
                case 'keripik-pisang-asin-1kg':
                    $imageName = 'keripik-asin-1kg.jpg';
                    break;
                case 'keripik-pisang-asin-2kg':
                    $imageName = 'keripik-asin-2kg.jpg';
                    break;

                // Coklat
                case 'keripik-pisang-coklat-1kg':
                    $imageName = 'keripik-coklat-1kg.jpg';
                    break;
                case 'keripik-pisang-coklat-2kg':
                    $imageName = 'keripik-coklat-2kg.jpg';
                    break;

                // Balado
                case 'keripik-pisang-balado-1kg':
                    $imageName = 'keripik-balado-1kg.jpg';
                    break;
                case 'keripik-pisang-balado-2kg':
                    $imageName = 'keripik-balado-2kg.jpg';
                    break;

                // Pedas
                case 'keripik-pisang-pedas-1kg':
                    $imageName = 'keripik-pedas-1kg.jpg';
                    break;
                case 'keripik-pisang-pedas-2kg':
                    $imageName = 'keripik-pedas-2kg.jpg';
                    break;

                default:
                    $imageName = 'no-image.svg';
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $imageName,
                'alt_text' => $product->name,
                'sort_order' => 0
            ]);
        }
    }
}
