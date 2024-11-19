<?php

namespace Lunar\Tests\Shipping\Stubs\Resolvers;

use Illuminate\Support\Collection;

class TestCustomPostcodeResolver
{
    public function getParts($postcode): Collection
    {
        $postcode = str_replace(' ', '', strtoupper($postcode));

        return collect([
            $postcode,
            substr($postcode, 0, 1).'*',
            substr($postcode, 0, 2).'*',
            substr($postcode, 0, 3).'*',
            substr($postcode, 0, 4).'*',
        ])->filter()->unique()->values();
    }
}
