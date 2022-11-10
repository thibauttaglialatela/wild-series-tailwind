FROM php:8.1-apache

#installation des extensions de php nécessaire au projet
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_pgsql intl

#installation de composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN curl https://deb.nodesource.com/setup_16.x | bash
RUN curl https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

RUN apt-get update && apt-get install -yqq nodejs yarn

#on copie tout notre projet symfony dans un nouveau répertoire
COPY . /var/www/

#on remplace le fichier de config présent de base par le fichier de config apache.conf
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

#on se place dans le dossier contenant notre application puis on lance composer install, yarn install et yarn build pour construire notre application
RUN cd /var/www && \
    composer install && \
    yarn install && \
    yarn build

WORKDIR /var/www/

ENTRYPOINT ["bash", "./docker/docker.sh"]

#Pour utiliser ce conteneur depuis l'extérieur il faut l'exposer depuis le port 80
EXPOSE 80