setup:
	composer install
	cp .env.example .env
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate

install:
	composer install

lint:
	composer phpcs

start:
	php -S localhost:8080 -t public public/index.php 2> phps.log &

browser:
	w3m http://localhost:8080/

clear:
	rm phps.log

test:
	php artisan test

reset-sqlite-db:
	rm database/database.sqlite || true
	touch database/database.sqlite
	php artisan migrate

reset-psql-db:
	dropdb seo_analyzer || true
	createdb seo_analyzer
	php artisan migrate
