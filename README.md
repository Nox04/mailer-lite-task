# Mailer Lite Task

##Stack
 - PHP 7.3
 - MySQL 5.7
 - Node 10
 - Docker
 - Laravel 6
 - Vue.js 2
 - Vuetify 2

## Installation

1. Install [Docker](https://docs.docker.com/).
2. Run `make`.

> Depending upon your platform dependencies you may need install `Make` from your package provider.

## API Endpoint

`http://127.0.0.1:8080/api`

## Frontend

### URL 
`http://127.0.0.1:8080`

### Features

- Night Mode.
- 100% accessible for keyboard, mouse and voice assistant technologies.
- Focus traps on modals.
- Server side pagination for all datatables.

## Backend Tests

`docker-compose exec php php ./vendor/bin/phpunit`

## Frontend Tests

`docker-compose exec node yarn test`
