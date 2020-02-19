SHELL := /bin/bash

PACKAGE_NAME=mailer-lite-task
DATE=$(shell date '+%F_%H-%M-%S')
DATE_DIR=$(shell date '+%B %d, %Y')
CURRENTDIR=$(shell pwd)

OPTIONS=--force-recreate --renew-anon-volumes

DOCKER_FILES=-f docker-compose.yml

.PHONY: all
all: .env docker composer-install install-app

.env:
	cp .env.example .env

install-app:
	docker-compose exec php php artisan migrate:fresh --seed
	docker-compose exec php php artisan key:generate
	docker-compose exec php php ./vendor/bin/phpunit
	docker-compose exec node yarn test

composer-install:
	docker-compose exec php /bin/bash -lc "/usr/local/bin/composer install -ovn --prefer-source"

composer-update:
	docker-compose exec php /bin/bash -lc "/usr/local/bin/composer update -ovn --prefer-source"

docker: down

	docker-compose $(DOCKER_FILES) up -d --remove-orphans $(OPTIONS)

	docker-compose exec php mkdir -p storage/framework/views storage/framework/session storage/app storage/cache

	docker-compose restart php

down:
	docker-compose down --remove-orphans
