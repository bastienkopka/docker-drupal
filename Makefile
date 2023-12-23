DOCKER_COMPOSE=docker compose
DOCKER_USERNAME=app

# Include .env file
ifneq ("$(wildcard ./.env)","")
	include ./.env
else
	include ./conf/env/init.env
endif

docker-init:
	@bash ./scripts/init.sh

docker-build:
	$(DOCKER_COMPOSE) build --no-cache --progress=plain

docker-up:
	$(DOCKER_COMPOSE) up -d --remove-orphans

docker-install: docker-init docker-up
	$(DOCKER_COMPOSE) exec -u $(DOCKER_USERNAME) php bash ./scripts/install.sh

docker-stop:
	$(DOCKER_COMPOSE) stop
	$(DOCKER_COMPOSE) down

docker-clean: docker-stop
	sudo rm -rf app/core/
	sudo rm -rf app/sites/default/files/
	sudo rm -rf vendor/
	sudo rm -rf .env
	sudo rm -rf docker-compose.yml
	sudo rm -rf data/db/

docker-restart: docker-stop docker-up

docker-shell:
	$(DOCKER_COMPOSE) exec -u $(DOCKER_USERNAME) php sh

### Quality quality.
quality-phpcs:
	$(DOCKER_COMPOSE) exec -u $(DOCKER_USERNAME) php ./vendor/bin/phpcs \
		--standard=./scripts/quality/phpcs/phpcs.xml.dist

quality-phpstan:
	$(DOCKER_COMPOSE) exec -u $(DOCKER_USERNAME) php ./vendor/bin/phpstan \
		--configuration=./scripts/quality/phpstan/phpstan.neon

quality-phpunit:
	$(DOCKER_COMPOSE) exec -u $(DOCKER_USERNAME) php bash ./scripts/run-phpunit.sh
	$(DOCKER_COMPOSE) exec php ./vendor/bin/phpunit \
		--configuration=./scripts/quality/phpunit/
