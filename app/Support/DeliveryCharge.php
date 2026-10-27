<?php

namespace App\Support;

class DeliveryCharge
{
    public const DEFAULT_INSIDE_DHAKA = 70;

    public const DEFAULT_OUTSIDE_DHAKA = 150;

    public static function amounts(array $settings): array
    {
        return [
            'inside' => self::configuredAmount($settings['delivery_inside_dhaka'] ?? null, self::DEFAULT_INSIDE_DHAKA),
            'outside' => self::configuredAmount($settings['delivery_outside_dhaka'] ?? null, self::DEFAULT_OUTSIDE_DHAKA),
        ];
    }

    public static function forDistrict(string $district, array $settings): float
    {
        $amounts = self::amounts($settings);

        return $amounts[strtolower(trim($district)) === 'dhaka' ? 'inside' : 'outside'];
    }

    private static function configuredAmount(mixed $value, int $default): float
    {
        return is_numeric($value) && (float) $value >= 0
            ? (float) $value
            : (float) $default;
    }
}
