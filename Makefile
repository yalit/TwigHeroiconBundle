.PHONY: testsDOCKER_EXEC

DOCKER = docker
DOCKER_EXEC = ${DOCKER} exec -it
DOCKER_COMPOSE = ${DOCKER} compose

build: down
	${DOCKER} compose up -d --build

down:
	${DOCKER} compose down

up:
	${DOCKER} compose up -d

install-8.1:
	rm -f composer.lock
	${DOCKER_EXEC} php_8.1 composer update

install-8.2:
	rm -f composer.lock
	${DOCKER_EXEC} php_8.2 composer update

install-front:
	${DOCKER_COMPOSE} run server npm install

#### ------- Tests
test-8.1: install-8.1
	${DOCKER_EXEC} php_8.1 ./vendor/bin/phpunit --testdox tests

test-8.2: install-8.2
	${DOCKER_EXEC} php_8.2 ./vendor/bin/phpunit --testdox tests

tests-back: test-8.1 test-8.2

tests-front:
	${DOCKER_COMPOSE} run server npm test

tests: tests-back tests-front
