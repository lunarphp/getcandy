<?php

namespace GetCandy\Tests\Unit\Base\Traits;

use GetCandy\Base\ModelFactory;
use GetCandy\Models\Product;
use GetCandy\Models\ProductOption;
use GetCandy\Models\ProductOptionValue;
use GetCandy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;

class InteractsWithEloquentModelTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        ModelFactory::register([
            Product::class => \GetCandy\Tests\Stubs\Models\Product::class,
            ProductOption::class => \GetCandy\Tests\Stubs\Models\ProductOption::class,
        ]);

        Product::factory()->create();

        ProductOption::factory()
            ->has(ProductOptionValue::factory()->count(3), 'values')
            ->create([
                'name' => [
                    'en' => 'Size',
                ],
            ]);
    }

    /** @test */
    public function can_forward_static_method_calls_on_eloquent_model()
    {
        $newStaticMethod = ProductOption::getSizesStatic();

        $this->assertInstanceOf(Collection::class, $newStaticMethod);
        $this->assertCount(3, $newStaticMethod);
    }

    /** @test */
    public function can_forward_method_calls_to_extended_model()
    {
        $sizeOption = ProductOption::with('sizes')->find(1);

        $this->assertInstanceOf(Collection::class, $sizeOption->values);
        $this->assertCount(3, $sizeOption->values);
    }

    /** @test */
    public function can_forward_method_trait_calls_to_extended_model()
    {
        $sizeOption = ProductOption::find(1);
        $traitMethod = $sizeOption->extendedSizes();

        $this->assertInstanceOf(Collection::class, $traitMethod);
        $this->assertCount(2, $traitMethod);
    }

    /** @test */
    public function can_and_new_scout_call_with_extended_model()
    {
        $product = Product::find(1);
        $this->assertFalse($product->shouldBeSomethingElseSearchable());
    }

    /** @test */
    public function can_swap_scout_call_with_extended_model()
    {
        $product = Product::find(1);
        $this->assertFalse($product->swap()->shouldBeSearchable());
    }

    /** @test */
    public function can_swap_static_call_with_extended_model()
    {
        $this->assertFalse(Product::swapStatic()->shouldBeSearchable());
    }

    /** @test */
    public function can_swap_scout_call_double_underscore_with_extended_model()
    {
        $product = Product::find(1);
        $this->assertFalse($product->__shouldBeSearchable());
    }
}
