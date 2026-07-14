<?php

namespace Tests\Unit;

use App\Models\Proxy;
use App\Services\ProxyChecker;
use Tests\TestCase;

class ProxyCheckerTest extends TestCase
{
    public function test_builds_url_without_auth(): void
    {
        $proxy = new Proxy(['host' => '1.2.3.4', 'port' => 8080, 'protocol' => 'http']);

        $this->assertSame('http://1.2.3.4:8080', (new ProxyChecker)->buildProxyUrl($proxy));
    }

    public function test_builds_url_with_username_only(): void
    {
        $proxy = new Proxy(['host' => '1.2.3.4', 'port' => 8080, 'protocol' => 'http', 'username' => 'user']);

        $this->assertSame('http://user@1.2.3.4:8080', (new ProxyChecker)->buildProxyUrl($proxy));
    }

    public function test_escapes_credentials_in_url(): void
    {
        $proxy = new Proxy([
            'host' => '1.2.3.4',
            'port' => 1080,
            'protocol' => 'socks5',
            'username' => 'my user',
            'password' => 'p@ss:w',
        ]);

        $this->assertSame('socks5://my%20user:p%40ss%3Aw@1.2.3.4:1080', (new ProxyChecker)->buildProxyUrl($proxy));
    }
}
