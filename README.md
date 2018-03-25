Symfony RealWorld example app
=============================

**Unofficial** Symfony codebase containing real world examples (CRUD, auth, advanced patterns, etc) 
that adheres to the [RealWorld](https://github.com/gothinkster/realworld-example-apps) API spec.

## Install project

Clone project

```bash
git clone https://github.com/slashfan/symfony-realworld-example-app
cd symfony-realworld-example-app
```

Create keys for JWT authentication

```bash
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
# edit .env file and adapt the JWT_PASSPHRASE variable
```

Configure environment variables (JWT_PASSPHRASE and DATABASE_URL)

```bash
cp .env.dist .env
```

Install dependencies

```bash
composer install -o
```

Prepare database and load fixtures

```bash
bin/console doctrine:database:create --env=dev
bin/console doctrine:schema:create --env=dev
bin/console doctrine:fixtures:load --no-interaction --env=dev
```

## Run project

Use the provided docker configuration (http://localhost)

```bash
cp docker-compose.override.yml.dist docker-compose.override.yml
docker-compose up --build
```

Or the simple symfony development webserver (http://localhost:8000)

```bash
bin/console server:run
```

## Run tests suite

```bash
bin/console cache:clear --env=test
bin/console doctrine:database:create --env=test
bin/console doctrine:schema:create --env=test
bin/console doctrine:fixtures:load --no-interaction --env=test
bin/phpunit
```
