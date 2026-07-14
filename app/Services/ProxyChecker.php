<?php

namespace App\Services;

use App\Enums\ProxyStatus;
use App\Models\Proxy;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ProxyChecker
{
    /**
     * Проверить прокси тестовым запросом: статус и задержка.
     */
    public function check(Proxy $proxy): ProxyCheckResult
    {
        return $this->checkMany(collect([$proxy]))[$proxy->id];
    }

    /**
     * Проверить пачку прокси параллельно.
     *
     * @return array<int, ProxyCheckResult>
     */
    public function checkMany(Collection $proxies): array
    {
        $latencies = [];

        $responses = Http::pool(fn (Pool $pool) => $proxies->map(
            fn (Proxy $proxy) => $pool->as((string) $proxy->id)
                ->withOptions($this->options($proxy, $latencies))
                ->get(config('proxy.check_url'))
        )->all());

        $results = [];

        foreach ($proxies as $proxy) {
            $response = $responses[(string) $proxy->id] ?? null;

            // не-2xx считаем нерабочим: 407 и 5xx отдаёт сам прокси, до цели запрос не дошёл
            $results[$proxy->id] = $response instanceof Response && $response->successful()
                ? new ProxyCheckResult(ProxyStatus::Online, $latencies[$proxy->id] ?? null)
                : new ProxyCheckResult(ProxyStatus::Offline);
        }

        return $results;
    }

    /**
     * Строка прокси для curl
     */
    public function buildProxyUrl(Proxy $proxy): string
    {
        $auth = '';

        if (filled($proxy->username)) {
            $auth = rawurlencode($proxy->username);

            if (filled($proxy->password)) {
                $auth .= ':'.rawurlencode($proxy->password);
            }

            $auth .= '@';
        }

        return sprintf('%s://%s%s:%d', $proxy->protocol->value, $auth, $proxy->host, $proxy->port);
    }

    private function options(Proxy $proxy, array &$latencies): array
    {
        return [
            'proxy' => $this->buildProxyUrl($proxy),
            'connect_timeout' => (float) config('proxy.connect_timeout'),
            'timeout' => (float) config('proxy.timeout'),
            // нам важна доступность, а не TLS - у прокси часто самоподписанные сертификаты
            'verify' => false,
            'on_stats' => function ($stats) use (&$latencies, $proxy) {
                $latencies[$proxy->id] = (int) round($stats->getTransferTime() * 1000);
            },
        ];
    }
}
