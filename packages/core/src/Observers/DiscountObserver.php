<?php

namespace Lunar\Observers;

use Lunar\Models\Discount;

class DiscountObserver
{
    /**
     * Handle the ProductVariant "deleted" event.
     *
     * @return void
     */
    public function deleting(Discount $discount)
    {
        $discount->collections()->detach();
        $discount->customerGroups()->detach();
        $discount->brands()->detach();
    }
}
