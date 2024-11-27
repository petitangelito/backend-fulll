# Variables
DOCKER = docker
DOCKER_COMPOSE = docker compose
EXEC = $(DOCKER) exec -it php-fpm
PHP = $(EXEC) php
COMPOSER = $(EXEC) composer
NPM = $(EXEC) npm
SYMFONY_CONSOLE = $(PHP) bin/console
PROJECT_NAME = Fulll
USER_UID=1000
USER_GID=1000

# Colors
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

## —— 🔥 App —————————————————————————————————————————————————————————————————
init: ## Init the project
	$(MAKE) create-folders
	$(MAKE) build
	$(MAKE) start
	$(COMPOSER) create-project symfony/skeleton:"7.1.*" .
	$(COMPOSER) require symfony/webpack-encore-bundle
	$(COMPOSER) require --dev symfony/profiler-pack
	$(COMPOSER) require --dev behat/behat:v3.15.0
	$(MAKE) npm-install
	@$(call GREEN,"The application is available at: http://127.0.0.1:80/.")
	@$(call GREEN,"The Pgadmin is available at: http://127.0.0.1:5000/.")

build-initial: ## Init the project
	$(MAKE) build
	$(MAKE) start
	$(MAKE) composer-install
	$(MAKE) npm-install
	@$(call GREEN,"The application is available at: http://127.0.0.1:80/.")
	@$(call GREEN,"The Pgadmin is available at: http://127.0.0.1:5000/.")

cache-clear: ## Clear cache
	$(SYMFONY_CONSOLE) cache:clear

## —— ✅ Linters —————————————————————————————————————————————————————————————————
.PHONY: linters
fix-cs: ## <Linters> Fix code style
	$(DOCKER_COMPOSE) run --rm php ./vendor/bin/php-cs-fixer fix

php-stan: ## <Linters> Fix code style
	$(DOCKER_COMPOSE) run --rm php ./vendor/bin/phpstan analyse src migrations config

## —— ✅ Test —————————————————————————————————————————————————————————————————
.PHONY: tests
tests: ## Run all tests
	$(MAKE) database-init-test
	$(PHP) bin/phpunit --testdox tests/Unit/
	$(PHP) bin/phpunit --testdox tests/Functional/

tests-behat: ## Run all behat tests
	$(EXEC) bash -c 'vendor/behat/behat/bin/behat'

database-init-test: ## Init database for test
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists --env=test
	$(SYMFONY_CONSOLE) d:d:c --env=test
	$(SYMFONY_CONSOLE) d:m:m --no-interaction --env=test
	$(SYMFONY_CONSOLE) d:f:l --no-interaction --env=test

unit-test: ## Run unit tests
	$(PHP) bin/phpunit --testdox tests/spec/Unit/

functional-test: ## Run functional tests
	$(PHP) bin/phpunit --testdox tests/spec/Functional/

## —— 🐳 Docker —————————————————————————————————————————————————————————————————
build: ## Build app with fresh images
	$(DOCKER_COMPOSE) build

start: ## Start app
	$(MAKE) docker-start

docker-start: 
	$(DOCKER_COMPOSE) up -d

stop: ## Stop app
	$(MAKE) docker-stop
	
docker-stop: 
	$(DOCKER_COMPOSE) stop
	@$(call RED,"The containers are now stopped.")

## —— 🎻 Composer ——
composer-install: ## Install dependencies
	$(COMPOSER) install

composer-update: ## Update dependencies
	$(COMPOSER) update

composer-clear-cache: ## clear-cache dependencies
	$(COMPOSER) clear-cache

## —— 🐈 NPM —————————————————————————————————————————————————————————————————
npm-install: ## Install all npm dependencies
	cd 
	$(NPM) install

npm-update: ## Update all npm dependencies
	$(NPM) update

npm-watch: ## Update all npm dependencies
	$(NPM) run watch

## —— 📊 Database —————————————————————————————————————————————————————————————————
database-init: ## Init database
	$(MAKE) database-drop
	$(MAKE) database-create
	$(MAKE) database-create-test
	$(MAKE) database-migrate
	$(MAKE) database-migrate-test
	$(MAKE) database-fixtures-load

database-drop: ## Create database
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists

database-create: ## Create database
	$(SYMFONY_CONSOLE) d:d:c --if-not-exists

database-create-test: ## Create test database
	$(SYMFONY_CONSOLE) --env=test d:d:c --if-not-exists

database-remove: ## Drop database
	$(SYMFONY_CONSOLE) d:d:d --force --if-exists

database-migration: ## Make migration
	$(SYMFONY_CONSOLE) make:migration

migration: ## Alias : database-migration
	$(MAKE) database-migration

database-migrate: ## Migrate migrations
	$(SYMFONY_CONSOLE) d:m:m --no-interaction

database-migrate-test: ## Migrate migrations
	$(SYMFONY_CONSOLE) d:m:m --env=test --no-interaction

migrate: ## Alias : database-migrate
	$(MAKE) database-migrate

database-fixtures-load: ## Load fixtures
	$(SYMFONY_CONSOLE) d:f:l --no-interaction

database-fixtures-load-test: ## Load fixtures
	$(SYMFONY_CONSOLE) d:f:l --no-interaction --env=test

fixtures: ## Alias : database-fixtures-load
	$(MAKE) database-fixtures-load

fixtures-test: ## Alias : database-fixtures-load
	$(MAKE) database-fixtures-load

create-folders: ## Create data folders
	mkdir -p ./app ./data/postgres ./logs/nginx

## —— 🛠️  Others —————————————————————————————————————————————————————————————————
help: ## List of commands
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

php-ssh: ## Run ssh on our php container
	$(DOCKER_COMPOSE) run php bash

chown-php: ## <Tests> Run specs
	$(DOCKER_COMPOSE) run --rm php chown $(USER_UID):$(USER_GID) -R .