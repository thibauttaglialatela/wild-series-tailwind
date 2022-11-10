composer require symfony/requirements-checker
composer dump-env prod
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
php bin/console d:m:m
php bin/console d:f:l
exec apache2-foreground