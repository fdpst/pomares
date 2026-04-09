<?php

namespace App\Helpers;

class PriceHelper
{
    /**
     * Número de decimales a mostrar (2 o 3).
     * Solo 3 si el valor tiene tercer decimal significativo.
     */
    public static function decimalPlaces(float $value): int
    {
        return (int) round($value * 1000) === (int) round($value * 100) * 10 ? 2 : 3;
    }

    /**
     * Formatea un importe: coma decimal y 2 o 3 decimales según el valor.
     */
    public static function format(float $value): string
    {
        $decimals = self::decimalPlaces($value);
        return number_format($value, $decimals, ',', '');
    }

    /**
     * Formatea un importe con un número fijo de decimales (para coherencia en facturas).
     */
    public static function formatWithDecimals(float $value, int $decimals): string
    {
        return number_format($value, $decimals, ',', '');
    }

    /**
     * Formatea con sufijo €.
     */
    public static function formatWithSymbol(float $value): string
    {
        return self::format($value) . '€';
    }
}
