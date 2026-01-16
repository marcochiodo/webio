<?php

use mrblue\framework\Utils\DbValue\S3ValueManager;
use mrblue\framework\Utils\Request;

class App {

    static $config;

    static public $includes = [];
    static public $files_content = [];
    static public $json_files = [];

    static public \mrblue\mvc\Mvc $Mvc;

    static public \DateTimeZone $TimeZone;
    static public \DateTimeImmutable $Now;
    static public int $time;

    static public \Helper\Translation $Translation;

    static function init(array $config) {

        self::$config = $config;

        self::$TimeZone = new \DateTimeZone(TIMEZONE);
        self::$Now = new \DateTimeImmutable();
        self::$time = self::$Now->getTimestamp();
        $Request = Request::getGlobalInstance();

        self::$Translation = \Helper\Translation::createFromHttpHeader($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null);
    }

    static function _include(string $path, array $vars = []) {

        if (!isset(self::$includes[$path])) {

            foreach ($vars as $var_name => $var_value) {
                $$var_name = $var_value;
            }

            self::$includes[$path] = include $path;
        }

        return self::$includes[$path];
    }

    static function _file_content(string $path): string {
        if (!isset(self::$files_content[$path])) {
            self::$files_content[$path] = file_get_contents($path);
        }

        return self::$files_content[$path];
    }

    static function _json_file(string $path) {
        if (!isset(self::$json_files[$path])) {
            self::$json_files[$path] = json_decode(self::_file_content($path), true, flags: JSON_THROW_ON_ERROR);
        }

        return self::$json_files[$path];
    }

    static public function getSmtp(string $name, string &$user): \Symfony\Component\Mailer\Mailer {

        $dsn = getenv("SMTP_DSN_" . strtoupper($name));

        if (!$dsn) {
            throw new \Exception("SMTP DSN not found for $name");
        }

        $Dsn = \Symfony\Component\Mailer\Transport\Dsn::fromString($dsn);
        $user = $Dsn->getUser();

        $TransportFactory = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory();
        return new \Symfony\Component\Mailer\Mailer(
            $TransportFactory->create($Dsn)
        );
    }

    static public function getTelegramBotHttpClient(string $name): \GuzzleHttp\Client {

        $token = getenv("TELEGRAM_BOT_TOKEN_" . strtoupper($name));

        if (! $token) {
            throw new \Exception("Telegram Bot Token not found for $name");
        }

        return new \GuzzleHttp\Client([
            'base_uri' => 'https://api.telegram.org/bot' . $token . '/',
            'timeout'  => 15
        ]);
    }
}
