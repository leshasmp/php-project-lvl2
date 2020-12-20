install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin

test:
	composer run-script phpunit tests

autoload:
	composer dump-autoload

gendiff:
	./bin/gendiff
