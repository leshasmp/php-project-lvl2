install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src bin

test:
	composer run-script phpunit tests

validate:
	composer validate

autoload:
	composer dump-autoload

gendiff:
	./bin/gendiff
