<?php

namespace Controller\Captcha;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\ChallengeOptions;
use App;
use Controller\ProjectController;
use Enum\CaptchaProvider;
use Exception\ClientException;

final class AltchaController extends ProjectController {

    function get() {

        $Captcha = $this->Project->captcha;

        if ($Captcha?->provider !== CaptchaProvider::altcha) {
            throw new ClientException(ClientException::BAD_REQUEST, 'Altcha not enabled in this project');
        }

        $Altcha = new Altcha(ENCRYPTION_KEY);

        $options = new ChallengeOptions(
            maxNumber: 50000,
            expires: (new \DateTimeImmutable())->add(new \DateInterval('PT1M')),
        );

        $Challenge = $Altcha->createChallenge($options);

        return [
            'algorithm' => $Challenge->algorithm,
            'challenge' => $Challenge->challenge,
            'salt' => $Challenge->salt,
            'signature' => $Challenge->signature,
        ];
    }

    function post() {

        $Captcha = $this->Project->captcha;

        if ($Captcha?->provider !== CaptchaProvider::altcha) {
            throw new ClientException(ClientException::BAD_REQUEST, 'Altcha not enabled in this project');
        }

        $solution = filter_input(INPUT_POST, 'solution');

        if (!$solution) {
            throw  new ClientException(ClientException::BAD_REQUEST, 'Altcha solution missing');
        }

        $altcha = new Altcha(ENCRYPTION_KEY);

        if (! $altcha->verifySolution($solution, true)) {
            if (! \Utils\DevEnv::canTrust()) {
                throw new ClientException(ClientException::BAD_REQUEST, 'Altcha verification failed');
            }
        }

        return [
            'captcha_passed_token' => \Utils\Encryptor::encrypt(json_encode([
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'timestamp' => App::$time
            ])),
            'expires' => App::$time + CAPTCHA_PASSED_TOKEN_TTL,
        ];
    }
}
