1.	Клонируйте репозиторий и перейдите в папку проекта
2.	Установите зависимости командой composer install
3.	Запустите окружение Laravel Sail командой ./vendor/bin/sail up -d
4.	Создайте копию файла .env из .env.example
5.	Сгенерируйте ключ приложения командой ./vendor/bin/sail artisan key:generate
6.	Выполните миграции базы данных командой ./vendor/bin/sail artisan migrate
7.	Запустите тесты командой ./vendor/bin/sail artisan test
8.	Соберите документацию командой ./vendor/bin/sail artisan l5-swagger:generate
9.	Документация API доступна по адресу http://localhost/api/documentation.

Проект использует Laravel 12, Laravel Sail (Docker), Eloquent ORM, L5-Swagger для документации и PHPUnit для тестов.