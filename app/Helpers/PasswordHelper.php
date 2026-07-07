<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class PasswordHelper
{
    public static function encrypt(string $plainPassword): string
    {
        return Crypt::encryptString($plainPassword);
    }

    public static function decrypt(string $value): string
    {
        if (! self::isEncrypted($value)) {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception) {
            return $value;
        }
    }

    public static function isEncrypted(string $value): bool
    {
        if (empty($value)) {
            return false;
        }
        // Laravel Crypt::encryptString produces base64-encoded JSON starting with eyJ
        return str_starts_with($value, 'eyJ');
    }
}
