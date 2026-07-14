<?php

namespace App\Enums;

enum ProxyStatus: string
{
    case Unknown = 'unknown';
    case Online = 'online';
    case Offline = 'offline';

    public function label(): string
    {
        return match ($this) {
            self::Unknown => 'Не проверен',
            self::Online => 'Работает',
            self::Offline => 'Недоступен',
        };
    }
}
