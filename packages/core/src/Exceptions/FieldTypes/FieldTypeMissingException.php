<?php

namespace Lunar\Exceptions\FieldTypes;

use Exception;

class FieldTypeMissingException extends Exception
{
    public function __construct($classname)
    {
        $this->message = __('lunar::exceptions.fieldtype_missing', [
            'class' => $classname,
        ]);
    }
}
