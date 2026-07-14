<?php

return [

    // Адрес, который дёргаем через прокси для проверки
    'check_url' => env('PROXY_CHECK_URL', 'http://cp.cloudflare.com/'),

    // Таймауты проверки, секунды
    'connect_timeout' => env('PROXY_CONNECT_TIMEOUT', 5),
    'timeout' => env('PROXY_TIMEOUT', 10),

];
