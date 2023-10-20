# artic-api-shop

Artic api used simple shop

## Description

The poject is a simple webshop based on [artic api](https://api.artic.edu/docs/).


## Installation

### Docker compose
```cd docker-compose```
```docker compose up -d```

#### Example docker-compose settings:

```PROJECT_NAME=artic-api-shop
SOURCE_DIR=../src
MYSQL_ROOT_PASSWORD=myrootpassword
MYSQL_DATABASE=symfony_db_dev
APP_ENV=dev
APP_DEBUG=1
NGINX_BACKEND_DOMAIN=artworkshop.dev```