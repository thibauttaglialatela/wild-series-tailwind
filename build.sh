#!/usr/bin/bash

rm -rf /vendor
composer dump-env prod
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
composer install --no-dev --optimize-autoloader
yarn install
php bin/console doctrine:migrations:migrate

