<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CheckLowStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low {--threshold=5 : Low stock threshold}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for products with low or out of stock and update their status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = $this->option('threshold');

        $this->info("Checking products with stock threshold: {$threshold}");

        // Update all product stock statuses
        $products = Product::where('manage_stock', true)->get();
        $lowStockCount = 0;
        $outOfStockCount = 0;

        foreach ($products as $product) {
            $oldStatus = $product->stock_status;
            $product->updateStockStatus();

            if ($product->stock_status === 'low_stock') {
                $lowStockCount++;
            } elseif ($product->stock_status === 'out_of_stock') {
                $outOfStockCount++;
            }

            if ($oldStatus !== $product->stock_status) {
                $this->line("Updated {$product->name}: {$oldStatus} → {$product->stock_status} (Stock: {$product->stock_quantity})");

                // Log untuk tracking
                Log::info("Stock status updated", [
                    'product' => $product->name,
                    'old_status' => $oldStatus,
                    'new_status' => $product->stock_status,
                    'stock_quantity' => $product->stock_quantity
                ]);
            }
        }

        $this->info("\nStock check completed:");
        $this->line("- Total products checked: {$products->count()}");
        $this->line("- Low stock products: {$lowStockCount}");
        $this->line("- Out of stock products: {$outOfStockCount}");

        if ($lowStockCount > 0 || $outOfStockCount > 0) {
            $this->warn("⚠️  Action required: {$lowStockCount} low stock and {$outOfStockCount} out of stock products found!");
        } else {
            $this->info("✅ All products have sufficient stock.");
        }

        return Command::SUCCESS;
    }
}
