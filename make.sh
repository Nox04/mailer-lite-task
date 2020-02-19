#! /bin/bash
cp .env.example .env
docker-compose down --remove-orphans
docker-compose -f docker-compose.yml up -d --remove-orphans --force-recreate --renew-anon-volumes
docker-compose exec php mkdir -p storage/framework/views storage/framework/session storage/app storage/cache
docker-compose restart php
docker-compose exec php /bin/bash -lc "/usr/local/bin/composer install -ovn --prefer-source"
docker-compose exec php php artisan migrate:fresh --seed
docker-compose exec php php artisan key:generate
docker-compose exec php php ./vendor/bin/phpunit
docker-compose exec node yarn test
