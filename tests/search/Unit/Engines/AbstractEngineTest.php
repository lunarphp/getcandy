<?php

use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Models\Transaction;
use Lunar\Stripe\Facades\Stripe;
use Lunar\Stripe\StripePaymentType;
use Lunar\Tests\Stripe\Utils\CartBuilder;

use function Pest\Laravel\assertDatabaseHas;

uses(\Lunar\Tests\Search\TestCase::class)->group('search');

it('can capture an order', function () {

});
