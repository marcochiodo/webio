#!/bin/bash

if [ -s .env ]; then
    export $(grep -v '^#' .env | xargs -0)
fi

docker run -it --rm \
    -v ${PWD}:/var/www/html \
    -v ${COMPOSER_PHAR}:/var/www/html/composer.phar \
    -v "./etc/default-server-nginx.conf":/etc/nginx/default-server.conf.d/extra.conf:ro \
    -v "./etc/nginx.conf":/etc/nginx/conf.d/extra.conf:ro \
    -v "./etc/php.ini":/etc/php/conf.d/www.ini:ro \
    --env-file env \
    --env-file ${CREDENTIALS_DIR}/env_files/scaleway-webio.env \
    -p 80:8080 \
    sigblue/nginx-php:84
