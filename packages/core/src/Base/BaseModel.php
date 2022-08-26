<?php

namespace GetCandy\Base;

use GetCandy\Base\Traits\HasModelExtending;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasModelExtending;

    /**
     * Create a new instance of the Model.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('getcandy.database.table_prefix').$this->getTable());

        if ($connection = config('getcandy.database.connection', false)) {
            $this->setConnection($connection);
        }
    }
}
