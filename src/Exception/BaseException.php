<?php

namespace Exception;

class BaseException extends \mrblue\framework\Exception\ClientException {

    static function log(\Throwable $th) {
        if (APP_ENV === ENV_DEVELOPMENT) {
            $th_as_string = "----- EXCEPTION ----- " . date("H:i:s") . "\n";
            //$th_as_string .= GcpLogger::getGcpErrorType($th instanceof \ErrorException ? $th->getSeverity() : E_ERROR);
            $th_as_string .= $th;
            $th_as_string .= "----- END EXCEPTION -----\n";
            file_put_contents('php://stderr', $th_as_string);
        } else {
            //GcpLogger::exception($th);
        }
    }
}
