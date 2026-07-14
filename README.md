# Proxy Manager

## Запуск

```bash
cp .env.example .env

docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer composer install

./vendor/bin/sail up -d --build
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
```

Приложение - http://localhost:8083, Swagger - http://localhost:8083/api/documentation
Крон для проверок прокси крутится отдельным контейнером, отдельно его запускать не нужно

## Тесты

```bash
./vendor/bin/sail artisan test
```
