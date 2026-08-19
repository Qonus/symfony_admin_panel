#!/bin/bash

docker compose -f compose.yaml build app
docker compose -f compose.yaml run --rm app php bin/console doctrine:migrations:migrate --no-interaction
docker compose -f compose.yaml up -d --no-deps app