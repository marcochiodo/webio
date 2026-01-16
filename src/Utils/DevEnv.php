<?php

namespace Utils;

class DevEnv {

    static function canTrust(): bool {

        return (
            APP_ENV === ENV_DEVELOPMENT &&
            ! empty($_GET['trust_dev_env'])
        );
    }
}
