<?php

namespace Controller;

use Exception\ClientException;
use mrblue\framework\Utils\Request;
use Utils\Utils;

final class ErrRepController extends ProjectController {

    function post() {

        $payload = filter_input(INPUT_POST, 'payload');
        if (!$payload) {
            throw new ClientException(ClientException::BAD_REQUEST, 'Payload is required');
        }

        $dir = sys_get_temp_dir() . '/err_report';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $Request = Request::getGlobalInstance();
        $site_name = $Request->origin ?? $Request->referer ?? '?';

        $first_20_md5 = substr(md5($site_name . substr($payload, 0, 20)), 0, 12);
        $full_md5 = substr(md5($site_name . $payload), 0, 12);
        $file_name = $dir . '/' . $first_20_md5 . '_' . $full_md5 . '.json';
        $store_data = [
            'site_name' => $site_name,
            'payload' => $payload,
        ];

        Utils::log($payload, [
            'site_name' => $site_name,
        ], 'ERROR', 'stderr');


        http_response_code(201);
        return [
            'success' => true,
        ];
    }
}
