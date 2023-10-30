[![codecov](https://codecov.io/gh/vassdavid/artic-api-shop/graph/badge.svg?token=ONWV5KLS96)](https://codecov.io/gh/vassdavid/artic-api-shop)
# artic-api-shop

Artic api used simple shop

## Description

The poject is a simple webshop based on [artic api](https://api.artic.edu/docs/).


## Installation

### With docker compose

1) Clone repository: ``` git clone https://github.com/vassdavid/artic-api-shop.git ```

2) Open folder: ``` cd  artic-api-shop ```

3) Get docker submodule: ``` git submodule init; git submodule update --remote ```

4) Run ``` docker compose -f ./.docker/docker-compose.yml --env-file .env up -d ```.

5) Command to enter symfony container: ``` docker exec -it artic-api-shop-symfony  /bin/sh  ```

6) Composer install: ``` composer install ```

7) Set symfony:

``` php bin/console doctrine:database:create ```

``` php bin/console doctrine:schema:create ```

``` php bin/console doctrine:fixtures:load ```

``` php bin/console lexik:jwt:generate-keypair ```


### Switch off containers

``` docker compose -f ./.docker/docker-compose.yml down ```


#### Example docker-compose settings:

```
PROJECT_NAME=artic-api-shop
SOURCE_DIR=../src
MYSQL_ROOT_PASSWORD=myrootpassword
MYSQL_DATABASE=symfony_db_dev
APP_ENV=dev
APP_DEBUG=1
NGINX_BACKEND_DOMAIN=artworkshop.dev
MYSQL_USER=app_user
MYSQL_PASSWORD=userpw
```
