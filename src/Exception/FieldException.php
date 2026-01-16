<?php

namespace Exception;

use Enum\FieldError;

class FieldException extends ClientException {

    const KIND = 'error';

    const FIELD_ERROR = 400001;

    static protected $errors;

    function __construct(public readonly FieldError $FieldError, string $message) {

        parent::__construct(self::FIELD_ERROR, [
            'FieldException' => [
                'code' => $this->FieldError->value,
                'text_code' => $this->FieldError->name,
                'message' => $message
            ]
        ]);
    }
}
