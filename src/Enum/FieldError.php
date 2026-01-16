<?php

namespace Enum;

enum FieldError: int {

    case format = 1;
    case required = 2;
    case empty_string = 3;
    case text_min_length = 4;
    case text_max_length = 5;
    case email_address = 6;
    case not_in_haystack = 7;
    case number_format = 8;
    case number_min = 9;
    case number_max = 10;
    case number_step = 11;
    case number_integer = 12;
    case phone_number = 13;

    function getErrorMessage(string $lang, string $field_name, array $params = []): string {

        $message_format = match ($this) {
            self::format => [
                'en' => "Field \"$field_name\" format is invalid",
                'it' => "Formato del campo \"$field_name\" non valido",
            ],
            self::required => [
                'en' => "Field \"$field_name\" required",
                'it' => "Campo \"$field_name\" obbligatorio",
            ],
            self::empty_string => [
                'en' => "Field \"$field_name\" cannot be empty",
                'it' => "Campo \"$field_name\" non può essere vuoto",
            ],
            self::text_min_length => [
                'en' => "Field \"$field_name\" must be at least %s characters long",
                'it' => "Campo \"$field_name\" deve contenere almeno %s caratteri",
            ],
            self::text_max_length => [
                'en' => "Field \"$field_name\" must be at most %s characters long",
                'it' => "Campo \"$field_name\" deve contenere al massimo %s caratteri",
            ],
            self::email_address => [
                'en' => "Field \"$field_name\" must be a valid email address",
                'it' => "Campo \"$field_name\" deve essere un indirizzo email valido",
            ],
            self::not_in_haystack => [
                'en' => "Field \"$field_name\" value not contains one of the allowed values.",
                'it' => "Il valore del campo \"$field_name\" non contiene uno dei valori ammessi.",
            ],
            self::number_format => [
                'en' => "Field \"$field_name\" format is not valid",
                'it' => "Formato numerico del campo \"$field_name\" non valido",
            ],
            self::number_min => [
                'en' => "Field \"$field_name\" min value: %s",
                'it' => "Valore minimo del campo \"$field_name\": %s",
            ],
            self::number_max => [
                'en' => "Field \"$field_name\" max value %s",
                'it' => "Valore massimo del campo \"$field_name\": %s",
            ],
            self::number_step => [
                'en' => "Field \"$field_name\" value must be an increment of %s",
                'it' => "Il valore del campo \"$field_name\" deve contenere un incremento di %s",
            ],
            self::number_integer => [
                'en' => "Field \"$field_name\" value must be an integer",
                'it' => "Il valore del campo \"$field_name\" deve essere un intero",
            ],
            self::phone_number => [
                'en' => "Field \"$field_name\" value must be a valid phone number",
                'it' => "Il valore del campo \"$field_name\" deve essere un numero di telefono valido",
            ],
        };

        $message = $message_format[$lang] ?? $message_format['en'];
        return vsprintf($message, $params);
    }
}
