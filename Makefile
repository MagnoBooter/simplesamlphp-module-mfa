
# Set up the default (i.e. - first) make entry.
start: web

bash:
	docker-compose run --rm mfaidp bash

#bashtests:
#	docker-compose run --rm tests bash

behat:
	TEST_IDP_PORT=":8085" TEST_SP_PORT=":8081" ./vendor/bin/behat --config=features/behat.yml --strict --stop-on-failure

behatappend:
	TEST_IDP_PORT=":8085" TEST_SP_PORT=":8081" ./vendor/bin/behat --config=features/behat.yml --strict --append-snippets

behatv:
	TEST_IDP_PORT=":8085" TEST_SP_PORT=":8081" ./vendor/bin/behat --config=features/behat.yml --strict --stop-on-failure -v

clean:
	docker-compose kill
	docker system prune -f

composer:
	docker-compose run --rm composer bash -c "composer install --no-scripts"

composerupdate:
	docker-compose run --rm composer bash -c "composer update --no-scripts"

enabledebug:
	docker-compose exec mfaidp bash -c "/data/enable-debug.sh"

ps:
	docker-compose ps

test: composer web
	sleep 10
	make behat

web:
	docker-compose up -d mfaidp mfasp
