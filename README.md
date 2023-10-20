# artic-api-shop

Artic api used simple shop

## Description

The poject is a simple webshop based on [artic api](https://api.artic.edu/docs/).


## Installation

### With docker compose

1) Make copy `.env.prod` or `.env.prod` to `.env` file.
Ensure given settings is valid. See in (examlpe)[#example-docker-compose-settings].

2) Open the `./docker-compose folder`:
```cd docker-compose```

3) Run docker compose up:
```docker compose up -d```

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