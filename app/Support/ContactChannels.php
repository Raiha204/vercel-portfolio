<?php

namespace App\Support;

final class ContactChannels
{
    /**
     * The contact methods shown on the site, built from config/portfolio.php.
     *
     * @return list<array{key:string,label:string,value:string,href:?string,cta:string}>
     */
    public static function all(): array
    {
        $contact = config('portfolio.contact');
        $code = (string) ($contact['country_code'] ?? '63');

        $channels = [];

        if (! empty($contact['whatsapp'])) {
            $digits = Phone::international($contact['whatsapp'], $code);
            $channels[] = [
                'key' => 'whatsapp',
                'label' => 'WhatsApp',
                'value' => $contact['whatsapp'],
                'href' => $digits ? "https://wa.me/{$digits}" : null,
                'cta' => 'Open chat',
            ];
        }

        if (! empty($contact['viber'])) {
            $digits = Phone::international($contact['viber'], $code);
            $channels[] = [
                'key' => 'viber',
                'label' => 'Viber',
                'value' => $contact['viber'],
                'href' => $digits ? 'viber://chat?number=%2B'.$digits : null,
                'cta' => 'Open chat',
            ];
        }

        if (! empty($contact['email'])) {
            $channels[] = [
                'key' => 'email',
                'label' => 'Email',
                'value' => $contact['email'],
                'href' => 'mailto:'.$contact['email'],
                'cta' => 'Write email',
            ];
        }

        // Profiles on other sites. They are shown only when a valid http(s) link is set in config/portfolio.php.
        $owner = config('portfolio.owner');
        foreach ([['github', 'GitHub', 'View profile'], ['linkedin', 'LinkedIn', 'View profile']] as [$key, $label, $cta]) {
            $url = Url::safe($owner[$key] ?? null);
            if ($url) {
                $channels[] = [
                    'key' => $key,
                    'label' => $label,
                    // Shown without the https:// and a trailing slash, copied as the full address.
                    'value' => rtrim((string) preg_replace($key === 'linkedin' ? '~^https?://(www\.)?linkedin\.com/~i' : '~^https?://(www\.)?~i', '', $url), '/'),
                    'copy' => $url,
                    'href' => $url,
                    'cta' => $cta,
                ];
            }
        }

        return $channels;
    }
}
