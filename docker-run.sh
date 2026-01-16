#!/bin/sh

if [ -s .env ]; then
    export $(grep -v '^#' .env | xargs -0)
fi

BW_DATA=$(bw list items --folderid "5c5355fa-5e55-4027-ad9d-b3a001038038")

DSN_VALUE=$(echo $BW_DATA | jq -r '.[] | select(.name=="SMTP notifications@marcochiodo.it") | .fields[] | select(.name=="dsn") | .value')
TELEGRAM_BOT_TOKEN=$(echo $BW_DATA | jq -r '.[] | select(.name=="telegram-bot-sigblue-token") | .login.password')

docker run -it --rm \
    -v ${PWD}:/var/www/html \
    -v "./etc/default-server-nginx.conf":/etc/nginx/default-server.conf.d/extra.conf:ro \
    -v "./etc/nginx.conf":/etc/nginx/conf.d/extra.conf:ro \
    -v "./etc/php.ini":/etc/php/conf.d/www.ini:ro \
    -v "./examples/config_projects/sample_project.json":/var/www/config_projects/sample_project.json:ro \
    -e APP_ENV=development \
    -e DISPLAY_ERRORS=1 \
    -e OPCACHE_ENABLE=0 \
    -e SMTP_DSN_MARCOCHIODO="$DSN_VALUE" \
    -e TELEGRAM_BOT_TOKEN_SIGBLUE="$TELEGRAM_BOT_TOKEN" \
    -e ENCRYPTION_KEY="test key in devmode" \
    -e PORT=80 \
    -p 80:8080 \
    --name webio \
    sigblue/nginx-php:85-alpine-3.23
