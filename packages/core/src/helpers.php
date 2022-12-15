<?php

use Lunar\Base\Traits\LunarUser;

if (! function_exists('is_lunar_user')) {
    function is_lunar_user($user)
    {
        $traits = class_uses_recursive($user);

        return in_array(LunarUser::class, $traits);
    }
}
