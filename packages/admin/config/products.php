<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Disable product variants
    |--------------------------------------------------------------------------
    |
    | If your storefront doesn't support variants and you don't want staff members
    | to be able to generate variants, you can disable the editing components here.
    |
    */
    'disable_variants' => false,

    /*
    |--------------------------------------------------------------------------
    | Product identifiers
    |--------------------------------------------------------------------------
    |
    | Here you can specify certain validation rules and how they affect the way
    | product variants are stored in the database. By defauly everything is false
    | but you can set these values to true if you would like to enforce uniqueness
    | and make sure a value is specified in the hub.
    |
    */
    'sku' => [
        'required' => true,
        'unique'   => true,
    ],
    'gtin' => [
        'required' => false,
        'unique'   => false,
    ],
    'mpn' => [
        'required' => false,
        'unique'   => false,
    ],
    'ean' => [
        'required' => false,
        'unique'   => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Extend validation rules
    |--------------------------------------------------------------------------
    |
    | Here you can specify certain validation rules to override or add more
    | validation to product.
    |
    */
    'validation' => [],
];
