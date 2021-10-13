#!/usr/bin/env bash

export APP_ENV=prod && symfony composer install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
