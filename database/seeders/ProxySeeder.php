<?php

namespace Database\Seeders;

use App\Models\Proxy;
use Illuminate\Database\Seeder;

class ProxySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proxies = [
            ['host' => '127.0.0.1', 'port' => 8888, 'protocol' => 'http'],
            ['host' => '192.168.1.10', 'port' => 1080, 'protocol' => 'socks5', 'username' => 'admin', 'password' => 'secret'],
            ['host' => '10.0.0.5', 'port' => 3128, 'protocol' => 'http'],
            ['host' => '203.0.113.42', 'port' => 8080, 'protocol' => 'https'],
        ];

        foreach ($proxies as $proxy) {
            Proxy::firstOrCreate(['host' => $proxy['host'], 'port' => $proxy['port'], 'protocol' => $proxy['protocol']], $proxy);
        }
    }
}
