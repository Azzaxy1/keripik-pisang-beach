<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Jika stock_quantity berubah dan bukan perubahan stock_status, update stock status
        if ($product->isDirty('stock_quantity') && !$product->isDirty('stock_status')) {
            $this->updateStockStatusSilently($product);
        }
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->updateStockStatusSilently($product);
    }

    /**
     * Update stock status tanpa trigger observer lagi
     */
    private function updateStockStatusSilently(Product $product): void
    {
        $newStatus = 'in_stock';
        
        if (!$product->manage_stock) {
            $newStatus = 'in_stock';
        } elseif ($product->stock_quantity <= 0) {
            $newStatus = 'out_of_stock';
        } elseif ($product->stock_quantity <= 5) {
            $newStatus = 'low_stock';
        }
        
        // Update tanpa trigger observer
        if ($product->stock_status !== $newStatus) {
            $product->timestamps = false;
            $product->updateQuietly(['stock_status' => $newStatus]);
            $product->timestamps = true;
        }
    }
}
