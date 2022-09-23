<?php

namespace Lunar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Lunar\Base\Addressable;
use Lunar\Base\BaseModel;
use Lunar\Base\Traits\HasMacros;
use Lunar\Database\Factories\AddressFactory;

class Address extends BaseModel implements Addressable
{
    use HasFactory, HasMacros;

    /**
     * Return a new factory instance for the model.
     *
     * @return \Lunar\Database\Factories\AddressFactory
     */
    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'shipping_default' => 'boolean',
        'billing_default' => 'boolean',
    ];

    /**
     * Mutator for the meta attribute.
     *
     * @param  array|null  $value
     * @return void
     */
    public function setMetaAttribute(array $value = null)
    {
        if ($value) {
            $this->attributes['meta'] = json_encode($value);
        }
    }

    /**
     * Accessor for the meta attribute.
     *
     * @param  string  $value
     * @return array
     */
    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Return the country relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Return the customer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
