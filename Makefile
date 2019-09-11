up: docker-up
init: docker-down-clear docker-pull docker-build docker-up manager-prestissimo manager-recipe manager-init perm
ps: docker-ps
test: manager-test
manager-recipe:
	docker-compose run --rm manager-php-cli composer config extra.symfony.allow-contrib true
docker-up:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans
docker-down-clear:
	docker-compose down -v --remove-orphans
docker-pull:
	docker-compose pull
docker-ps:
	docker-compose ps
docker-build:
	docker-compose build
manager-init: manager-composer-install
perm:
	sudo chown -R rupak:rupak manager
manager-test:
	docker-compose run --rm manager-php-cli php bin/phpunit tests
manager-prestissimo:
	docker-compose run --rm manager-php-cli composer global require hirak/prestissimo
manager-composer-install:
	docker-compose run --rm manager-php-cli composer install
manager-init: manager-composer-install manager-wait-db manager-migrations manager-fixtures
manager-migrations:
	docker-compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate --no-interaction
manager-fixtures:
	docker-compose run --rm manager-php-cli php bin/console doctrine:fixtures:load --no-interaction
manager-wait-db:
	until docker-compose exec -T manager-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done
cli:
	docker-compose run --rm manager-php-cli php bin/app.php
build-production:
	docker build --pull --file=manager/docker/production/nginx.docker --tag ${REGISTRY_ADDRESS}/manager-nginx:${IMAGE_TAG} manager
	docker build --pull --file=manager/docker/production/php-fpm.docker --tag ${REGISTRY_ADDRESS}/manager-php-fpm:${IMAGE_TAG} manager
	docker build --pull --file=manager/docker/production/php-cli.docker --tag ${REGISTRY_ADDRESS}/manager-php-cli:${IMAGE_TAG} manager
push-production:
	docker push ${REGISTRY_ADDRESS}/manager-nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-php-cli:${IMAGE_TAG}
deploy-production:
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'rm -rf docker-compose.yml .env'
	scp -P {PRODUCTION_PORT} docker-compose-production.yml ${PRODUCTION_HOST}:docker-compose.yml
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_APP_SECRET=${MANAGER_APP_SECRET}" >> .env'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_DB_PASSWORD=${MANAGER_DB_PASSWORD}" >> .env'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose pull'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose --build -d'