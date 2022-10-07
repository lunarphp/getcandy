<?php

namespace Lunar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Lunar\Models\Product;

class AttributeFactory extends Factory
{
    private static $position = 1;

    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'attribute_group_id' => AttributeGroup::factory(),
            'attribute_type' => Product::class,
            'position' => self::$position++,
            'name' => [
                'en' => $this->faker->name(),
            ],
            'handle' => Str::slug($this->faker->name()),
            'section' => $this->faker->name(),
            'type' => \Lunar\FieldTypes\Text::class,
            'required' => false,
            'default_value' => '',
            'configuration' => [
                'options' => [
                    $this->faker->name(),
                    $this->faker->name(),
                    $this->faker->name(),
                ],
            ],
            'system' => false,
        ];
    }
}
