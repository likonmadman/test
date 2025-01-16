<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SwitchDatabase
{
    public function handle($request, Closure $next)
    {
        try {
            // Проверка подключения к мастер-базе
            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            // Если мастер недоступен, переключаемся на SLAVE
            Config::set('database.default', 'mysql_slave');
        }

        return $next($request);
    }
}
