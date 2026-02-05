<?php

namespace Controller;

use App;
use Exception\ClientException;

final class DataContentController extends ProjectController {

    function get() {

        $params = $this->Mvc->getRouteMatch()->getParams();
        $data_content_name = $params['data_content_name'];
        $success = true;
        $errors = [];

        /** @var \Model\DataContent $DataContent */
        $DataContent = $this->Project->elements->search('name', $data_content_name);
        if (!$DataContent || $DataContent->type !== \Enum\ElementType::data_content) {
            throw new \Exception\ClientException(ClientException::NOT_FOUND, 'Data content not found');
        }

        $captcha_required = (bool) $this->Project->captcha;

        if ($captcha_required) {
            // TODO: validate captcha
            $captcha_passed_token = filter_input(INPUT_GET, 'captcha_passed_token');
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

        end:

        http_response_code($success ? 201 : 400);

        return [
            'success' => $success,
            'errors' => $errors
        ] + ($success ? [
            'data' => $DataContent->data,
        ] : []);
    }
}
