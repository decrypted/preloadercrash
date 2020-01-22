all: run

.PHONY: $(LINT_TARGETS)
$(LINT_TARGETS):lint-%:%
	@php -l $< >/dev/null

.PHONY: lint
lint: $(LINT_TARGETS)
	@echo Lint finished

docker-test:
	@echo "checking docker ..."
	@docker ps > /dev/null && docker-compose -v > /dev/null && echo "docker seems ok"

bin/composer.phar:
	mkdir bin || true
	cd bin && curl -sS https://getcomposer.org/installer | php

composer-update: bin/composer.phar
	bin/composer.phar update

composer.lock: bin/composer.phar
	bin/composer.phar install

composed: composer.lock

docker-composer-update:
	COMPOSE_FILE=docker-compose.yml docker-compose run --rm  --entrypoint 'bash -c "make composer-update || exit 0"'  php

docker-composed:
	COMPOSE_FILE=docker-compose.yml docker-compose run --rm  --entrypoint 'bash -c "make composed || exit 0"'  php

.PHONY: list
list:
	@$(MAKE) -pRrq -f $(lastword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'

run: docker-composed
	docker-compose build
	COMPOSE_FILE=docker-compose.yml docker-compose run --rm php

shell:
	COMPOSE_FILE=docker-compose.yml docker-compose run --rm --entrypoint /bin/bash php

