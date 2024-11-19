<?php

use Lunar\Shipping\Resolvers\PostcodeResolver;

return [
    'enabled' => env('LUNAR_SHIPPING_TABLES_ENABLED', true),

    'resolvers' => [
        'postcode' => PostcodeResolver::class,
    ],
];
