.PHONY: testsDOCKER_EXEC

DOCKER = docker
DOCKER_EXEC = ${DOCKER} exec -it
DOCKER_COMPOSE = ${DOCKER} compose

build: down
	${DOCKER} compose up -d --build --remove-orphans

down:
	${DOCKER} compose down

up:
	${DOCKER} compose up -d --remove-orphans

reset-back:
	rm -rf composer.lock

install-8.1: reset-back
	${DOCKER_EXEC} php_8.1 composer update

install-8.2: reset-back
	${DOCKER_EXEC} php_8.2 composer update

reset-front:
	rm -rf package-lock.json

install-front-18: reset-front
	${DOCKER_COMPOSE} run server-18 npm install

install-front-20: reset-front
	${DOCKER_COMPOSE} run server-20 npm install

install-front-22: reset-front
	${DOCKER_COMPOSE} run server-22 npm install

#### ------- Tests
test-8.1: install-8.1
	${DOCKER_EXEC} php_8.1 ./vendor/bin/phpunit --testdox tests

test-8.2: install-8.2
	${DOCKER_EXEC} php_8.2 ./vendor/bin/phpunit --testdox tests

tests-back: test-8.1 test-8.2

test-front-18: install-front-18
	${DOCKER_COMPOSE} run server-18 npm test

test-front-20: install-front-20
	${DOCKER_COMPOSE} run server-20 npm test

test-front-22: install-front-22
	${DOCKER_COMPOSE} run server-22 npm test

tests-front: test-front-18 test-front-20 test-front-22

tests: tests-back tests-front
