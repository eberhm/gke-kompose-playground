FROM petrepatrasc/docker-php-fpm:5.6

COPY api/composer.json /source/composer.json
COPY api/composer.lock /source/composer.lock

WORKDIR /source

RUN composer install

ADD api /source