<?php

declare(strict_types=1);

if (! function_exists('to_float')) {
    /**
     * Convert a mixed DB/form value to float.
     *
     * Several price/spec columns are stored as strings (or nullable),
     * so values like null, '', or '1,234.50' are possible. Returns null
     * when the value holds no parseable number.
     */
    function to_float(mixed $value): ?float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (is_bool($value)) {
            return null;
        }

        if (! is_string($value)) {
            return null;
        }

        $cleaned = str_replace([',', ' '], '', trim($value));

        if ($cleaned === '' || ! is_numeric($cleaned)) {
            return null;
        }

        return (float) $cleaned;
    }
}

if (! function_exists('format_number')) {
    /**
     * number_format() that never throws on null/empty/garbage input.
     * Unparseable values render as $fallback (default '–').
     */
    function format_number(mixed $value, int $decimals = 2, string $decPoint = '.', string $thousandsSep = '', string $fallback = '–'): string
    {
        $float = to_float($value);

        if ($float === null) {
            return $fallback;
        }

        return number_format($float, $decimals, $decPoint, $thousandsSep);
    }
}
