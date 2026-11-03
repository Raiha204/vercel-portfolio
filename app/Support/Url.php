<?php

namespace App\Support;

final class Url
{
    /**
     * Returns the URL only when it is a real http(s) address, otherwise null.
     * Used everywhere a stored link is shown, so a blank or odd value never becomes a live link.
     */
    public static function safe(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        $parts = parse_url($url);

        if (! is_array($parts) || empty($parts['host'])) {
            return null;
        }

        if (! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            return null;
        }

        return $url;
    }
}
