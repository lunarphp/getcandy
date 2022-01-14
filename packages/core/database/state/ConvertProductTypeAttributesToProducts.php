<?php

namespace GetCandy\Database\State;

use GetCandy\Models\Product;
use GetCandy\Models\ProductType;
use Illuminate\Support\Facades\DB;

class ConvertProductTypeAttributesToProducts
{
    public function run()
    {
        $prefix = config('getcandy.database.table_prefix');

        DB::table("{$prefix}attributes")
            ->whereAttributeType(ProductType::class)
            ->update([
                'attribute_type' => Product::class,
            ]);

        DB::table("{$prefix}attribute_groups")
            ->whereAttributeType(Product::class)
            ->update([
                'attributable_type' => Product::class,
            ]);
    }
}
