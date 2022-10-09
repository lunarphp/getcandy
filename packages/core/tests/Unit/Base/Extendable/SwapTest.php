<?php

namespace Lunar\Tests\Unit\Base\Extendable;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Product;
use Lunar\Tests\Stubs\Models\ProductSwapModel;

class SwapTest extends ExtendableTestCase
{
    use RefreshDatabase;

    protected Product $product;

    /** @test */
    public function core_model_made_aware_of_swapping_instances()
    {
        $this->product = Product::find(1);
        $this->product->swap(ProductSwapModel::class);

        $this->product = Product::find(3);
        $this->assertFalse($this->product->shouldBeSearchable());
    }
}
