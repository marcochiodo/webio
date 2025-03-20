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

    static public \Aws\S3\S3Client $s3Client;

    static function init(array $config) {

        self::$config = $config;

        self::$TimeZone = new \DateTimeZone(TIMEZONE);
        self::$Now = new \DateTimeImmutable();
        self::$time = self::$Now->getTimestamp();
        $Request = Request::getGlobalInstance();

        self::$s3Client = new \Aws\S3\S3Client([
            'region' => 'nl-ams',
            'version' => '2006-03-01',
            'endpoint' => 'http://s3.nl-ams.scw.cloud',
            'credentials' => [
                'key' => SCALEWAY_ACCESS_KEY_ID,
                'secret' => SCALEWAY_SECRET_KEY
            ]
        ]);
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
}
