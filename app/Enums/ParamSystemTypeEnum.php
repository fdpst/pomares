<?php

namespace App\Enums;

enum ParamSystemTypeEnum: string
{
    case TEXT = "TEXT";
    case NUMBER = "NUMBER";
    case BOOLEAN = "BOOLEAN";
    case SELECTABLE = "SELECTABLE";
    case TEXTAREA = "TEXTAREA";
    case IMAGE = "IMAGE";

    public static function values()
    {
        return [
            self::TEXT->value,
            self::NUMBER->value,
            self::BOOLEAN->value,
            self::SELECTABLE->value,
            self::TEXTAREA->value,
            self::IMAGE->value,
        ];
    }
}
