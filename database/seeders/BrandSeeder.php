<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Samsung is a South Korean multinational conglomerate known for its innovative technology products.',
                'website' => 'https://www.samsung.com',
                'status' => true,
            ],
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Apple Inc. is an American multinational technology company that designs and manufactures consumer electronics.',
                'website' => 'https://www.apple.com',
                'status' => true,
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Nike is an American multinational corporation engaged in the design, development, manufacturing and marketing of footwear, apparel, equipment, accessories, and services.',
                'website' => 'https://www.nike.com',
                'status' => true,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Adidas is a German multinational corporation that designs and manufactures shoes, clothing and accessories.',
                'website' => 'https://www.adidas.com',
                'status' => true,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Sony Corporation is a Japanese multinational conglomerate corporation known for electronics, gaming, entertainment and technology.',
                'website' => 'https://www.sony.com',
                'status' => true,
            ]
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
