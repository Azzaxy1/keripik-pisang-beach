<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductSoldCountSeeder extends Seeder
{
  /**
   * Run the database seeder untuk update sold count produk.
   */
  public function run(): void
  {
    // Update beberapa produk dengan sold count untuk testing
    $products = Product::limit(5)->get();

    foreach ($products as $product) {
      $product->update([
        'sold_count' => rand(5, 50)
      ]);
    }

    $this->command->info('Product sold count updated successfully!');
  }
}
