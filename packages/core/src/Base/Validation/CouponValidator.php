<?php

namespace Lunar\Base\Validation;

use Lunar\DiscountTypes\AmountOff;
use Lunar\Models\Discount;

class CouponValidator implements CouponValidatorInterface
{
    public function validate(string $coupon): bool
    {
        return Discount::whereType(AmountOff::class)
            ->active()
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhereRaw('uses < max_uses');
            })->where('coupon', '=', strtoupper($coupon))->exists();
    }
}
