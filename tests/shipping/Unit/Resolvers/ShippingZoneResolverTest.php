<?php

uses(\Lunar\Tests\Shipping\TestCase::class)
    ->group('shipping', 'shipping-zone');

use Illuminate\Support\Facades\Config;
use Lunar\Models\Country;
use Lunar\Models\State;
use Lunar\Shipping\DataTransferObjects\PostcodeLookup;
use Lunar\Shipping\Models\ShippingZone;
use Lunar\Shipping\Resolvers\ShippingZoneResolver;
use Lunar\Tests\Shipping\Stubs\Resolvers\TestCustomPostcodeResolver;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can fetch shipping zones by country', function () {
    $countryA = Country::factory()->create();
    $countryB = Country::factory()->create();

    $shippingZoneA = ShippingZone::factory()->create([
        'type' => 'countries',
    ]);

    $shippingZoneB = ShippingZone::factory()->create([
        'type' => 'countries',
    ]);

    $shippingZoneA->countries()->attach($countryA);
    $shippingZoneB->countries()->attach($countryB);

    expect($shippingZoneA->refresh()->countries)->toHaveCount(1);

    $zones = (new ShippingZoneResolver)->country($countryA)->get();

    expect($zones)->toHaveCount(1);

    expect($zones->first()->id)->toEqual($shippingZoneA->id);
});

test('can fetch shipping zones by state', function () {
    $countryA = Country::factory()->create();
    $countryB = Country::factory()->create();

    $stateA = State::factory()->create([
        'country_id' => $countryA->id,
    ]);

    $stateB = State::factory()->create([
        'country_id' => $countryB->id,
    ]);

    $shippingZoneA = ShippingZone::factory()->create([
        'type' => 'states',
    ]);

    $shippingZoneB = ShippingZone::factory()->create([
        'type' => 'countries',
    ]);

    $shippingZoneA->states()->attach($stateA);
    $shippingZoneB->states()->attach($stateB);

    expect($shippingZoneA->refresh()->states)->toHaveCount(1);

    $zones = (new ShippingZoneResolver)->state($stateA)->get();

    expect($zones)->toHaveCount(1);

    expect($zones->first()->id)->toEqual($shippingZoneA->id);
});

test('doesnt fetch postcode shipping zones by country', function () {
    $countryA = Country::factory()->create();

    $shippingZoneA = ShippingZone::factory()->create([
        'type' => 'postcodes',
    ]);

    $shippingZoneA->countries()->attach($countryA);

    expect($shippingZoneA->refresh()->countries)->toHaveCount(1);

    $zones = (new ShippingZoneResolver)->country($countryA)->get();

    expect($zones)->toBeEmpty();
});

test('can fetch zone by postcode lookup', function () {
    $country = Country::factory()->create();

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'postcodes',
    ]);

    $shippingZone->countries()->attach($country);

    $shippingZone->postcodes()->create([
        'postcode' => 'ABC',
    ]);

    expect($shippingZone->refresh()->countries)->toHaveCount(1);
    expect($shippingZone->refresh()->postcodes)->toHaveCount(1);

    $postcode = new PostcodeLookup(
        $country,
        'ABC 123'
    );

    $zones = (new ShippingZoneResolver)->postcode($postcode)->get();

    expect($zones)->toHaveCount(1);

    expect($zones->first()->id)->toEqual($shippingZone->id);
});

test('can use custom postcode resolver', function () {
    $country = Country::factory()->create();

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'postcodes',
    ]);

    $shippingZone->countries()->attach($country);

    $shippingZone->postcodes()->createMany([[
        'postcode' => '390*',
    ], [
        'postcode' => '391*',
    ]]);

    $shippingZone2 = ShippingZone::factory()->create([
        'type' => 'postcodes',
    ]);

    $shippingZone2->countries()->attach($country);

    $shippingZone2->postcodes()->create([
        'postcode' => '393*',
    ]);

    expect($shippingZone->refresh()->countries)->toHaveCount(1);
    expect($shippingZone->refresh()->postcodes)->toHaveCount(2);

    $postcode = new PostcodeLookup(
        $country,
        '39100'
    );

    Config::set('lunar.shipping-tables.resolvers.postcode', TestCustomPostcodeResolver::class);

    $zones = (new ShippingZoneResolver)->postcode($postcode)->get();

    expect($zones)->toHaveCount(1);

    expect($zones->first()->id)->toEqual($shippingZone->id);
})->group('shipping-postcode');
