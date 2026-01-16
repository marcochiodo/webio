<?php

namespace Utils;

class Utils {

    static function log(string $message, array $data = [], string $severity = 'INFO', string $stream = 'stdout') {

        $log_data = [
            'severity' => $severity,
            'message' => $message,
        ] + $data;

        file_put_contents("php://{$stream}", json_encode($log_data) . "\n");
    }
}
