# LP DIOC - TP4

## Install

`$ composer install`

Please update the `.env` file as your needs. Expecially the `DATABASE_URL` env var.

## Create the database

`$ bin/console doctrine:database:create`

## Create the schema

`$ bin/console doctrine:schema:create`

## Load fixtures

`$ bin/console doctrine:fixtures:load`

## Start the web server

`$ bin/console server:start`

### Information

| login | password | role       |
|-------|----------|------------|
| `user@exemple.org`  | `user`     | `ROLE_USER`  |
| `admin@exemple.org` | `admin`    | `ROLE_ADMIN` |
