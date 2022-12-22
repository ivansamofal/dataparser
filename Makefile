test: ## Показать возможные команды
	php vendor/bin/codecept run Unit
app:
	cd src && php app.php input.txt
composer-install:
	composer install --ignore-platform-reqs