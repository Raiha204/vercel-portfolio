<?php

namespace App\Support;

final class Phone
{
    /**
     * Turns a local number such as 09705476036 into the international digits
     * used by chat links (639705476036 for country code 63).
     */
    public static function international(?string $number, string $countryCode = '63'): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if ($digits === '' || $digits === null) {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            return substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            return $countryCode.substr($digits, 1);
        }

        return $digits;
    }
}
