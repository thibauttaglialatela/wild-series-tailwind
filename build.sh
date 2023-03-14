#!/usr/bin/bash

rm -Rf vendor/
composer install --ignore-platform-reqs
yarn install
composer dump-env prod
php bin/console doctrine:schema:create
php bin/console doctrine:migrations:migrate
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
yarn build
