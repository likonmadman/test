<?php

use Illuminate\Support\Facades\Schedule;

// Автообновление статусов прокси каждые 5 минут
Schedule::command('proxies:check')->everyFiveMinutes()->withoutOverlapping();
