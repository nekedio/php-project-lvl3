setup:
	composer install
	cp .env.example .env
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate

install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app/Http/Controllers tests routes

start:
	php -S localhost:8080 -t public public/index.php 2> phps.log &

browser:
	w3m http://localhost:8080/

clear:
	rm phps.log

test:
	php artisan test --testsuite=Feature

resetSqliteBD:
	rm database/database.sqlite || true
	touch database/database.sqlite
	php artisan migrate

resetPsqlBD:
	dropdb seo_analyzer || true
	createdb seo_analyzer
	php artisan migrate
