<?php

namespace MvcEventCallback;

use App;
use Exception\BaseException;
use Exception\ClientException;
use Exception\ClientLoggableException;
use mrblue\mvc\JsonView;
use mrblue\mvc\MvcEvent;
use mrblue\mvc\RenderView;

class HandleException extends AbstractMvcEventCallback {

    function run(MvcEvent $MvcEvent): void {

        $Response = $MvcEvent->Mvc->getResponse();

        $Exception = $MvcEvent->Mvc->getException();

        if ($Exception instanceof \JsonException) {
            BaseException::log($Exception);
            $Exception = new ClientException(ClientException::BAD_REQUEST, 'json not valid');
        } elseif ($Exception instanceof ClientLoggableException) {
            BaseException::log($Exception);
        }

        if (!$Exception instanceof ClientException) {
            BaseException::log($Exception);
            $Exception = new ClientException(ClientException::INTERNAL_SERVER_ERROR);
        }

        $MvcEvent->Mvc->setResponse($Exception->createJsonResponse(true));
    }
}
