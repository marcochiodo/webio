<?php

namespace Exception;

class ClientException extends BaseException {

    const KIND = 'error';

    static protected $errors;

    function __construct(int $code, string|array $data = []) {

        if (is_string($data)) {
            $data = [
                'dev_message' => $data
            ];
        }

        parent::__construct($code, $data);
    }

    function createJsonView(bool $set_http): \mrblue\mvc\JsonView {

        if ($set_http) {
            header('Content-Type: application/json');
            http_response_code($this->http_status_code);
        }

        return new \mrblue\mvc\JsonView([
            'kind' => ClientException::KIND,
            ClientException::KIND => $this
        ]);
    }

    function createJsonResponse(bool $set_http): \mrblue\mvc\Response {

        $Response = new \mrblue\mvc\Response;
        $Response->setView($this->createJsonView($set_http));
        return $Response;
    }
}
