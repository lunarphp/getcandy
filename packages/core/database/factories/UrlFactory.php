<?php

namespace Lunar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lunar\Models\Language;
use Lunar\Models\Product;
use Lunar\Models\Url;

class UrlFactory extends Factory
{
    protected $model = Url::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'default' => true,
            'language_id' => Language::factory(),
            'element_type' => Product::class,
            'element_id' => 1,
        ];
    }
}
