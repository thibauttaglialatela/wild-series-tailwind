#!/usr/bin/bash

composer dump-env prod
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
composer install --no-dev --optimize-autoloader
yarn install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

