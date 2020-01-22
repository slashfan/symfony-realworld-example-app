DOCKER_COMPOSE = docker-compose
EXEC = $(DOCKER_COMPOSE) exec
EXEC_PHP = $(DOCKER_COMPOSE) exec php
EXEC_NODE = $(DOCKER_COMPOSE) exec node
SYMFONY = $(EXEC_PHP) bin/console
COMPOSER = $(EXEC_PHP) composer

##
## Project
## -------
##

build:
	@$(DOCKER_COMPOSE) pull --parallel --quiet --ignore-pull-failures 2> /dev/null
	$(DOCKER_COMPOSE) build --pull

kill:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

install: ## Install and start the project
install: .env.local docker-compose.override.yml build start vendor rsa-keys db

reset: ## Stop and start a fresh install of the project
reset: kill install

start: ## Start the project
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop: ## Stop the project
	$(DOCKER_COMPOSE) stop

clean: ## Stop the project and remove generated files
clean: kill
	rm -rf .env.local docker-compose.override.yml config/jwt/*.pem vendor

no-docker:
	$(eval DOCKER_COMPOSE := \#)
	$(eval EXEC_PHP := )
	$(eval EXEC_JS := )

.PHONY: build kill install reset start stop clean no-docker

##
## Utils
## -----
##

db: ## Reset the database and load fixtures
db: .env.local vendor
	@$(EXEC_PHP) php -r 'echo "Wait database...\n"; set_time_limit(15); require __DIR__."/vendor/autoload.php"; (new \Symfony\Component\Dotenv\Dotenv())->load(__DIR__."/.env"); $$u = parse_url(getenv("DATABASE_URL")); for(;;) { if(@fsockopen($$u["host"].":".($$u["port"] ?? 3306))) { break; }}'
	-$(SYMFONY) doctrine:database:drop --if-exists --force
	-$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --allow-no-migration
	$(SYMFONY) doctrine:fixtures:load --no-interaction

migration: ## Generate a new doctrine migration
migration: vendor
	$(SYMFONY) doctrine:migrations:diff

db-validate-schema: ## Validate the doctrine ORM mapping
db-validate-schema: .env.local vendor
	$(SYMFONY) doctrine:schema:validate

.PHONY: db migration watch

# rules based on files
#composer.lock: composer.json
#	$(COMPOSER) update --lock --no-scripts --no-interaction

vendor: composer.lock
	$(COMPOSER) install

.env.local: .env
	@if [ -f .env.local ]; \
	then\
		echo '\033[1;41m/!\ The .env file has changed. Please check your .env.local file (this message will not be displayed again).\033[0m';\
		touch .env.local;\
		exit 1;\
	else\
		echo cp .env .env.local;\
		cp .env .env.local;\
	fi

docker-compose.override.yml: docker-compose.override.yml.dist
	@if [ -f docker-compose.override.yml ]; \
	then\
		echo '\033[1;41m/!\ The docker-compose.override.yml.dist file has changed. Please check your docker-compose.override.yml file (this message will not be displayed again).\033[0m';\
		touch docker-compose.override.yml;\
		exit 1;\
	else\
		echo cp docker-compose.override.yml.dist docker-compose.override.yml;\
		cp docker-compose.override.yml.dist docker-compose.override.yml;\
	fi

rsa-keys: ## Generate RSA keys need for JWT encoding / decoding
rsa-keys:
	@if [ -f config/jwt/private.pem ]; \
	then\
		rm config/jwt/private.pem;\
	fi
	@if [ -f config/jwt/public.pem ]; \
	then\
		rm config/jwt/public.pem;\
	fi
	$(EXEC_PHP) openssl genrsa -out config/jwt/private.pem -aes256 -passout pass:passphrase 4096
	$(EXEC_PHP) openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem -passin pass:passphrase

##
## Quality assurance
## -----------------
##

ci: ## Run all quality insurance checks (tests, code styles, linting, security, static analysis...)
ci: php-cs-fixer phpcs phpmd phpmnd phpstan rector.dry lint validate-composer validate-mapping security test test-coverage test-spec

ci.local: ## Run quality insurance checks from inside the php container
ci.local: no-docker ci

lint: ## Run lint check
lint:
	$(SYMFONY) lint:yaml config/ --parse-tags
	$(SYMFONY) lint:yaml fixtures/
	$(SYMFONY) lint:yaml translations/
	$(SYMFONY) lint:container

phpcs: ## Run phpcode_sniffer
phpcs:
	$(EXEC_PHP) vendor/bin/phpcs

php-cs-fixer: ## Run php-cs-fixer
php-cs-fixer:
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix --verbose

php-cs-fixer.dry-run: ## Run php-cs-fixer in dry-run mode
php-cs-fixer.dry-run:
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix --verbose --diff --dry-run

phpmd: ## Run PHPMD
phpmd:
	$(EXEC_PHP) vendor/bin/phpmd src/,tests/ text phpmd.xml.dist

phpmnd: ## Run PHPMND
phpmnd:
	$(EXEC_PHP) vendor/bin/phpmnd src --extensions=default_parameter

phpstan: ## Run phpstan
phpstan:
	$(EXEC_PHP) vendor/bin/phpstan analyse
	
rector.dry: ## Dry-run rector
rector.dry:
	$(EXEC_PHP) vendor/bin/rector process src --dry-run
	
rector: ## Run rector
rector:
	$(EXEC_PHP) vendor/bin/rector process src
	
psalm: ## Run rector
psalm:
	docker run --init -it --rm -v $$PWD:/project -v $$PWD/var/phpqa:/tmp -w /project jakzal/phpqa psalm

security: ## Run security-checker
security:
	$(EXEC_PHP) vendor/bin/security-checker security:check

test: ## Run phpunit tests
test:
	$(EXEC_PHP) vendor/bin/phpunit

test-coverage: ## Run phpunit tests with code coverage (phpdbg)
test-coverage: test-coverage-pcov

test-coverage-phpdbg: ## Run phpunit tests with code coverage (phpdbg)
test-coverage-phpdbg:
	$(EXEC_PHP) phpdbg -qrr ./vendor/bin/phpunit --coverage-html=var/coverage

test-coverage-pcov: ## Run phpunit tests with code coverage (pcov - uncomment extension in dockerfile)
test-coverage-pcov:
	$(EXEC_PHP) vendor/bin/phpunit --coverage-html=var/coverage

test-coverage-xdebug: ## Run phpunit tests with code coverage (xdebug - uncomment extension in dockerfile)
test-coverage-xdebug:
	$(EXEC_PHP) vendor/bin/phpunit --coverage-html=var/coverage

test-coverage-xdebug-filter: ## Run phpunit tests with code coverage (xdebug with filter - uncomment extension in dockerfile)
test-coverage-xdebug-filter:
	$(EXEC_PHP) vendor/bin/phpunit --dump-xdebug-filter var/xdebug-filter.php
	$(EXEC_PHP) vendor/bin/phpunit --prepend var/xdebug-filter.php --coverage-html=var/coverage

test-spec: ## Run postman collection tests
test-spec:
	$(EXEC_NODE) bash ./spec/api-spec-test-runner.sh

validate-composer: ## Validate composer.json and composer.lock
validate-composer:
	$(EXEC_PHP) composer validate
	$(EXEC_PHP) composer normalize --dry-run

validate-mapping: ## Validate doctrine mapping
validate-mapping:
	$(SYMFONY) doctrine:schema:validate --skip-sync -vvv --no-interaction

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
