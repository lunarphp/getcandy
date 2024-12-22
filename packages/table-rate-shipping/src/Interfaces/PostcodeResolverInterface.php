<?php

namespace Lunar\Shipping\Interfaces;

use Illuminate\Support\Collection;

interface PostcodeResolverInterface
{
    public function getParts($postcode): Collection;
}
