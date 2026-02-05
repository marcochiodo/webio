<?php

namespace MvcEventCallback;

use App;
use mrblue\mvc\JsonView;
use mrblue\mvc\Mvc;
use mrblue\mvc\MvcEvent;
use mrblue\mvc\RenderView;
use mrblue\mvc\Response;

class HandleNotFound extends AbstractMvcEventCallback {

    function run(MvcEvent $MvcEvent): void {

        http_response_code(404);
        $MvcEvent->Mvc->setResponse(
            self::createResponse($MvcEvent->Mvc)
        );
    }

    static function createResponse(Mvc $Mvc): Response {

        $Excepiton = new \Exception\ClientException(
            \Exception\ClientException::NOT_FOUND
        );
        $Response = new Response();

        $Response->setView($Excepiton->createJsonView(false));
        return $Response;
    }
}
