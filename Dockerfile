FROM sigblue/nginx-php:85-alpine-3.23

ARG app_env=production
ENV APP_ENV=$app_env

COPY ./etc/default-server-nginx.conf /etc/nginx/default-server.conf.d/extra.conf
COPY ./etc/nginx.conf /etc/nginx/conf.d/extra.conf
COPY ./etc/php.ini /etc/php/conf.d/www.ini

COPY --chown=www  . /var/www/html

RUN sh /var/www/composer-installer.sh
RUN php -c . composer.phar install -o --no-dev
