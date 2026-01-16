<?php

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

use Exception\BaseException;
use mrblue\mvc\Utility\Env;
use mrblue\mvc\Event\EventListener;
use mrblue\mvc\Mvc;


define('APP_ENV', getenv('APP_ENV'));

if (!in_array(APP_ENV, [ENV_DEVELOPMENT, ENV_TESTING, ENV_STAGING, ENV_PRODUCTION])) {
    throw new \RuntimeException('ENV var "' . APP_ENV . '" not valid');
}

set_exception_handler(function ($e) {
    BaseException::log($e);
    http_response_code(500);
    throw $e;
});

set_error_handler(function ($severity, $message, $file, $line) {
    BaseException::log(new ErrorException($message, 0, $severity, $file, $line));
    return false;
});


$Request = \mrblue\framework\Utils\Request::getGlobalInstance();

define('REMOTE_ADDR', $Request->ip);
define('BASE_URL', $Request->proto . '://' . $Request->server_name);
define('REQUEST_PATH', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

include 'src/init_envar.php';

if (APP_ENV !== ENV_DEVELOPMENT) {
    $config = require 'config_build.php';
} else {
    $config = mrblue\mvc\Config::get(realpath('config'));
}

$Mvc = new \mrblue\mvc\Mvc($config);

App::$Mvc = $Mvc;
App::init($config);

foreach (
    [
        [Mvc::EVENT_ROUTE_EXCEPTION, new \MvcEventCallback\HandleNotFound, 110],
        [Mvc::EVENT_AFTER_ROUTE, new \MvcEventCallback\HandleProject, 110],
        [Mvc::EVENT_CONTROLLER_EXCEPTION, new \MvcEventCallback\HandleException, 110],
    ] as $mvc_event_set
) {

    /**@var \MvcEventCallback\AbstractMvcEventCallback */
    $MvcEventCallback = $mvc_event_set[1];

    $Mvc->EventManager->register(
        new EventListener(
            Mvc::EVENT_PREFIX . $mvc_event_set[0],
            $MvcEventCallback->getCallback(),
            $mvc_event_set[2]
        )
    );
}

$Mvc->run();
