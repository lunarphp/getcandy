<?php

namespace Lunar\Observers;

use Lunar\Models\Product;

class ProductObserver
{
    /**
     * Handle the ProductVariant "deleted" event.
     *
     * @return void
     */
    public function deleting(Product $product): void
    {
        $product->variants()->delete();
    }

    public function restored(Product $product): void
    {
        $product->variants()->restore();
    }
}
