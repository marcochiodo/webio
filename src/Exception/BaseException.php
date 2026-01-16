<?php

namespace Exception;

use Utils\Utils;

class BaseException extends \mrblue\framework\Exception\ClientException {

    static function log(\Throwable $th) {
        Utils::log("PHP Exception: $th", [], 'ERROR', 'stderr');
    }
}
