#!/usr/bin/bash

rm -Rf vendor/
composer install --ignore-platform-reqs
yarn install
composer dump-env prod
php bin/console cache:clear
php bin/console cache:warmup
yarn build
