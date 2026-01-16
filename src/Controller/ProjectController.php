<?php

namespace Controller;

use App;
use Model\Project;

abstract class ProjectController extends BaseController {

    public Project $Project;

    protected function isCaptchaPassed(string $token): bool {

        $decrypted_token = \Utils\Encryptor::decrypt($token);
        if (!$decrypted_token) {
            return false;
        }
        $data = json_decode($decrypted_token, true);
        if (!$data) {
            return false;
        }

        if (!isset($data['timestamp']) || !isset($data['user_agent'])) {
            return false;
        }

        if ($data['timestamp'] < (App::$time - CAPTCHA_PASSED_TOKEN_TTL)) {
            return false;
        }

        if ($data['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? null)) {
            return false;
        }

        return true;
    }
}
