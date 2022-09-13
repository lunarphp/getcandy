<?php

namespace Lunar\Exceptions;

use Exception;

class NonPurchasableItemException extends Exception
{
    public function __construct(string $classname)
    {
        $this->message = __('lunar::exceptions.non_purchasable_item', [
            'class' => $classname,
        ]);
    }
}
