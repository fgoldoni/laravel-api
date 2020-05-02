.PHONY: install phpcs server test help optimize routes migrate watch chmod refresh move echo ansible test docker

.DEFAULT_GOAL = help
PHP=php
NPM=npm
COMPOSER=composer
PORT?=3000

vendor: composer.json
	composer install

composer.lock: composer.json
	composer update

install: vendor composer.lock ## install vendor dependancies  soe68427@eveav.com

help: ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

phpcs: ## PRS2 Validation
	./vendor/bin/phpcbf --extensions=php -s
	./vendor/bin/phpcs --extensions=php -s
	./vendor/bin/php-cs-fixer fix --diff

server: ## Load phpMyAdmin server
	$(PHP) -S localhost:$(PORT) -t ../../phpMyAdmin

test: ## phpunit test
	./vendor/bin/phpunit --debug

optimize: install ## optimize
		$(PHP) artisan cache:clear & $(PHP) artisan config:clear & $(PHP) artisan route:clear & $(PHP) artisan view:clear

migrate: optimize ## migrate
	$(PHP) artisan migrate:refresh

refresh: migrate ## refresh + php artisan module:seed Admin & php artisan module:seed Support
	php artisan module:migrate-refresh Roles
	php artisan module:migrate-refresh Users
	php artisan module:migrate-refresh Categories
	php artisan module:migrate-refresh Posts
	php artisan module:migrate-refresh Tags
	php artisan module:migrate-refresh Events
	php artisan module:migrate-refresh Tickets
	php artisan module:seed Roles
	php artisan module:seed Users
	php artisan module:seed Categories
	php artisan module:seed Posts
	php artisan module:seed Events
	php artisan module:seed Tickets
routes: optimize
	$(PHP) artisan laroute:generate

message: optimize ## messages.js add /* eslint-disable */
	$(PHP) artisan lang:js --no-lib resources/js/utils/messages.js & php artisan lang:js public/js/messages.js

queue: optimize ## start jos processing
	$(PHP) artisan queue:work --queue=high,transaction,low,default --tries=3

restart: optimize ## restart jos processing
	$(PHP) artisan queue:restart

watch:  ## run watch-poll
	$(NPM) run watch-poll

dump:  ## run watch-poll
	$(COMPOSER) dump-auto

chmod:  ## run watch-poll
	sudo chmod 777 -R storage/*

move:  ## move templates
	mv -f storage/templates/Role.php vendor/spatie/laravel-permission/src/Models/ & mv -f storage/templates/PermissionMiddleware.php vendor/spatie/laravel-permission/src/Middlewares/ & mv -f storage/templates/Category.php vendor/rinvex/categories/src/Models/

load:  ## move templates
	mv -f vendor/spatie/laravel-permission/src/Models/Role.php storage/templates/  & mv -f vendor/spatie/laravel-permission/src/Middlewares/PermissionMiddleware.php storage/templates/  & mv -f vendor/rinvex/categories/src/Models/Category.php  storage/templates/

echo:  ## laravel-echo-server start
	laravel-echo-server start

ansible:  ## deploy with ansible before run:  sudo apt-get install -y python
	ansible-playbook -i ../Ansible/hosts ../Ansible/playbook.yml

ansible-repo:  ## deploy with ansible
	ansible-playbook -i ../Ansible/hosts ../Ansible/install.yml
docker:  ## docker-compose up -d nginx mysql phpmyadmin laravel-horizon redis php-worker  workspace
	cd laradock
	docker-compose up -d nginx mysql phpmyadmin laravel-horizon redis php-worker  workspace



