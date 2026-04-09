<?php

namespace App\Helpers;

class MessageHelper
{
    public static function success($key, $replace = [])
    {
        return __('messages.success.' . $key, $replace);
    }

    public static function error($key, $replace = [])
    {
        return __('messages.error.' . $key, $replace);
    }
}
