install:
    composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src tests

start:
	php -S localhost:8080 -t public public/index.php 2> phps.log &

browser:
	w3m http://localhost:8080/

clear:
	rm phps.log