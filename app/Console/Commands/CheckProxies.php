<?php

namespace App\Console\Commands;

use App\Models\Proxy;
use App\Services\ProxyChecker;
use Illuminate\Console\Command;

class CheckProxies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxies:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет статус всех прокси';

    /**
     * Execute the console command.
     */
    public function handle(ProxyChecker $checker): int
    {
        $proxies = Proxy::all();

        if ($proxies->isEmpty()) {
            $this->info('Прокси не найдены.');

            return self::SUCCESS;
        }

        foreach ($checker->checkMany($proxies) as $id => $result) {
            $proxies->find($id)->applyCheckResult($result);
        }

        $this->info("Проверено прокси: {$proxies->count()}.");

        return self::SUCCESS;
    }
}
