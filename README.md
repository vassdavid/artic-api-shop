[![codecov](https://codecov.io/gh/vassdavid/artic-api-shop/graph/badge.svg?token=ONWV5KLS96)](https://codecov.io/gh/vassdavid/artic-api-shop)
# artic-api-shop

Artic api used simple shop

## Description

The poject is a simple webshop based on [artic api](https://api.artic.edu/docs/).


## Installation

### With docker compose

1) Clone repository.

2) Run ```docker compose -f ./.docker/docker-compose.yml up```.

#### Example docker-compose settings:

```
PROJECT_NAME=artic-api-shop
SOURCE_DIR=../src
MYSQL_ROOT_PASSWORD=myrootpassword
MYSQL_DATABASE=symfony_db_dev
APP_ENV=dev
APP_DEBUG=1
NGINX_BACKEND_DOMAIN=artworkshop.dev
```