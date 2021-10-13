#!/usr/bin/env bash

composer install || true
symfony console doctrine:database:create --if-not-exists --no-interaction || true
symfony console doctrine:schema:update --force --no-interaction || true
