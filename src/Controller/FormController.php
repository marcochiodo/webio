<?php

namespace Controller;

use App;
use Exception\ClientException;

final class FormController extends ProjectController
{

    function submit()
    {

        $params = $this->Mvc->getRouteMatch()->getParams();
        $form_name = $params['form_name'];
        $success = false;
        $errors = [];

        /** @var \Model\Form $Form */
        $Form = $this->Project->elements->search('name', $form_name);
        if (!$Form || $Form->type !== \Enum\ElementType::form) {
            throw new \Exception\ClientException(ClientException::NOT_FOUND, 'Form not found');
        }

        $captcha_required = (bool) $this->Project->captcha;

        if ($captcha_required) {
            // TODO: validate captcha
            $captcha_passed_token = filter_input(INPUT_POST, 'captcha_passed_token');
            if (!$captcha_passed_token) {
                $success = false;
                $errors[] = App::$Translation->t('error_messages/captcha_token_missing');
                goto end;
            }
            if (! $this->isCaptchaPassed($captcha_passed_token)) {
                $success = false;
                $errors[] = App::$Translation->t('error_messages/captcha_token_not_valid');
                goto end;
            }
        }

        $FormValidator = new \Utils\FormValidator($Form);
        $success = $FormValidator->validateFields();
        $errors = $FormValidator->errors;

        if (!$success) {
            goto end;
        }

        $sent = false;

        if ($this->Project->email) {
            $EmailSender = new \Utils\EmailSender($this->Project->email, $FormValidator->validated_values);
            if ($EmailSender->send()) {
                $sent = true;
            }
        }

        if ($this->Project->telegram) {
            $TelegramSender = new \Utils\TelegramSender($this->Project->telegram, $FormValidator->validated_values);
            if ($TelegramSender->send()) {
                $sent = true;
            }
        }

        if (!$sent) {
            $success = false;
            $errors[] = App::$Translation->t('error_messages/send_error');
        }

        end:

        http_response_code($success ? 201 : 400);

        return [
            'success' => $success,
            'errors' => $errors,
        ];
    }
}
