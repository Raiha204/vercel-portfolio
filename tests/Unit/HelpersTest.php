<?php

namespace Tests\Unit;

use App\Support\Phone;
use App\Support\Url;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_local_numbers_become_international_digits(): void
    {
        $this->assertSame('639705476036', Phone::international('09705476036', '63'));
        $this->assertSame('639705476036', Phone::international('0970 547 6036', '63'));
        $this->assertSame('639705476036', Phone::international('+63 970 547 6036', '63'));
        $this->assertSame('639705476036', Phone::international('00639705476036', '63'));
        $this->assertNull(Phone::international('', '63'));
        $this->assertNull(Phone::international(null, '63'));
    }

    public function test_only_http_and_https_links_are_safe(): void
    {
        $this->assertSame('https://github.com/me/repo', Url::safe(' https://github.com/me/repo '));
        $this->assertSame('http://localhost:8000', Url::safe('http://localhost:8000'));

        foreach ([null, '', '   ', 'javascript:alert(1)', 'data:text/html,hi', 'ftp://host/file', 'github.com/me', 'https://', 'mailto:a@b.c'] as $bad) {
            $this->assertNull(Url::safe($bad), (string) $bad);
        }
    }
}
