DOCKER_COMPOSE?=docker compose
DOCKER?=docker
COMPOSER=$(DOCKER_COMPOSE) exec -T php composer
CONTAINERS=$(DOCKER) ps -a -q
RUN=$(DOCKER_COMPOSE) run --rm php

.DEFAULT_GOAL := help

help:
	@grep -E '(^[0-9a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##
## Manage docker containers
##---------------------------------------------------------------------------


start: clear-containers up ## Remove & start containers

up: network ## Run containers
	$(DOCKER_COMPOSE) up -d --remove-orphans

network: ## Create Docker network
	$(DOCKER) network create uuid | true

clear-containers: ## Remove containers
	$(DOCKER) stop `$(CONTAINERS)`
	$(DOCKER) rm `$(CONTAINERS)` --force
	$(DOCKER_COMPOSE) rm `$(CONTAINERS)` --force

up-docker-chmod:
	sudo chmod a+rwx /var/run/docker.sock
	sudo chmod a+rwx /var/run/docker.pid


##
## Project setup
##---------------------------------------------------------------------------

composer-install: up ## Install php dependencies
	$(COMPOSER) install

##
## Project tools
##---------------------------------------------------------------------------

php-console: up ## Start php console
	$(DOCKER_COMPOSE) run --rm php sh

check-all: analyse-code tests-code cs-fix ## Run analyse,tests and php cs fixer

analyse-code: ## Run phpstan analyse and unit test
	$(RUN) composer phpstan

tests-code: ## Run phpstan analyse and unit test
	$(RUN) composer phpunit

cs-fix: ## Run phpstan analyse and unit test
	$(RUN) composer php-cs-fixer

cs-fix-check: ## Run phpstan analyse and unit test
	$(RUN) composer php-cs-fixer-check