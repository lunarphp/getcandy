<?php

namespace Lunar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Lunar\Base\BaseModel;
use Lunar\Base\Traits\HasDefaultRecord;
use Lunar\Base\Traits\HasMacros;
use Lunar\Database\Factories\CustomerGroupFactory;

class CustomerGroup extends BaseModel
{
    use HasFactory;
    use HasDefaultRecord;
    use HasMacros;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     *
     * @return \Lunar\Database\Factories\CustomerGroupFactory
     */
    protected static function newFactory(): CustomerGroupFactory
    {
        return CustomerGroupFactory::new();
    }

    /**
     * Return the customer's relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function customers()
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(
            Customer::class,
            "{$prefix}customer_customer_group"
        )->withTimestamps();
    }
}
