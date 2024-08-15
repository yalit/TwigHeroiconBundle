.PHONY: testsDOCKER_EXEC

DOCKER = docker
DOCKER_EXEC = ${DOCKER} exec -it



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


#### ------- Tests
test-8.1: install-8.1
	${DOCKER_EXEC} php_8.1 ./vendor/bin/phpunit --testdox tests

test-8.2: install-8.2
	${DOCKER_EXEC} php_8.2 ./vendor/bin/phpunit --testdox tests


tests: test-8.1 test-8.2
