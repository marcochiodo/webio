<?php

namespace Utils;

use App;
use Model\Field;
use Model\Form;

class FormValidator {

    public array $errors = [];
    public array $validated_values = [];

    function __construct(
        private readonly Form $form
    ) {
    }

    function validateFields(): bool {

        $this->errors   = [];

        $all_ok = true;

        foreach ($this->form->fields as $Field) {
            if (!$this->validateField($Field)) {
                $all_ok = false;
            }
        }

        return $all_ok;
    }

    function validateField(Field $Field): bool {

        $value = filter_input(INPUT_POST, $Field->name);

        if (($value === null || $value === false) && $Field->required) {
            $this->errors[$Field->name] = (
                App::$Translation->t('default_fields_names/' . $Field->name) .
                ' ' .
                App::$Translation->t('error_messages/required')
            );
            return false;
        }

        if ($Field->min_length && strlen($value) < $Field->min_length) {
            $this->errors[$Field->name] = (
                App::$Translation->t('default_fields_names/' . $Field->name) .
                ' ' .
                App::$Translation->t('error_messages/min_length')
            );
            return false;
        }

        if ($Field->max_length && strlen($value) > $Field->max_length) {
            $this->errors[$Field->name] = (
                App::$Translation->t('default_fields_names/' . $Field->name) .
                ' ' .
                App::$Translation->t('error_messages/max_length')
            );
            return false;
        }

        if ($Field->regex && !preg_match($Field->regex, $value)) {
            $this->errors[$Field->name] = (
                App::$Translation->t('default_fields_names/' . $Field->name) .
                ' ' .
                App::$Translation->t('error_messages/generic')
            );
            return false;
        }

        if (in_array($Field->name, ['email', 'email_address'])) {
            if (!$this->validateEmail($value)) {
                $this->errors[$Field->name] = (
                    App::$Translation->t('default_fields_names/' . $Field->name) .
                    ' ' .
                    App::$Translation->t('error_messages/generic')
                );
                return false;
            }
        }

        if (in_array($Field->name, ['phone', 'phone_number'])) {
            if (!$this->validatePhone($value)) {
                $this->errors[$Field->name] = (
                    App::$Translation->t('default_fields_names/' . $Field->name) .
                    ' ' .
                    App::$Translation->t('error_messages/generic')
                );
                return false;
            }
        }

        $this->validated_values[$Field->name] = $value;
        return true;
    }

    protected function validateEmail($value) {

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    protected function validatePhone($value) {

        if (!preg_match('/^\+?[0-9]{1,4}?[-.\s]?(\(?\d{1,4}?\)?)[-.\s]?(\d{1,4})[-.\s]?(\d{1,9})$/', $value)) {
            return false;
        }

        return true;
    }
}
