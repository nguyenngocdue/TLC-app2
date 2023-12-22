
docker:
	docker exec -it laravel302_php /bin/sh
.PHONY: docker

import-database:
	# How to use this script:
	# Extract the database file to laravel.sql in src folder, it is ignored from git
	# execute this script
	echo DROP laravel DATABASE
	echo DROP DATABASE IF EXISTS laravel | docker exec -i laravel302_mysql /usr/bin/mysql -u tlc_app_2 --password=DLTedTfrVzYbrYv9
	echo CREATE new larvel DATABASE
	echo CREATE DATABASE laravel | docker exec -i laravel302_mysql /usr/bin/mysql -u tlc_app_2 --password=DLTedTfrVzYbrYv9
	echo Importing data - approximately 80 seconds...
	cat laravel.sql | docker exec -i laravel302_mysql /usr/bin/mysql -u tlc_app_2 --password=DLTedTfrVzYbrYv9 laravel
.PHONY: import-database

git-pull:
	git pull
	# && sudo chmod 777 storage/framework/* -R
	# && sudo chown 82:82 * -R
	docker exec -it laravel302_php php artisan cache:clear
	docker exec -it laravel302_php php artisan view:clear
	docker exec -it laravel302_php php artisan config:clear
	docker exec -it laravel302_php php artisan event:clear

	docker exec -it laravel302_php php artisan route:clear
	docker exec -it laravel302_php php artisan route:cache

	echo "Don't forget to run php artisan migrate to have the latest version of DB"
.PHONY: git-pull

docker-compose:
	cd .docker_302/ && docker-compose -f docker-compose302.yml down && docker-compose -f docker-compose302.yml  up -d --build && cd ../
.PHONY: docker-compose

docker-compose-down:
	cd .docker_302/ && docker-compose -f docker-compose302.yml down && cd ../
.PHONY: docker-compose-down

docker-403:
	cd .docker_nginx-proxy-443/ && docker-compose down && docker-compose up -d --build && cd ../
.PHONY: docker-403
