<?php

define('CONFIG_APP_ENV', getenv('APP_ENV'));

if (!in_array(CONFIG_APP_ENV, [ENV_DEVELOPMENT, ENV_TESTING, ENV_STAGING, ENV_PRODUCTION])) {
    throw new \RuntimeException('ENV var "' . CONFIG_APP_ENV . '" not valid');
}

return [];
