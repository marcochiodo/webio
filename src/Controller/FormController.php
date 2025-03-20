<?php

namespace Controller;

use App;
use Exception\ClientException;
use GuzzleHttp\Client;
use Model\Form;

final class FormController extends BaseController {

    function get() {

        $Form = $this->getForm();

        return [
            'success' => true
        ];
    }

    function post() {

        $Form = $this->getForm();

        return [
            'success' => true
        ];
    }

    private function getForm(): Form {
        $params = $this->Mvc->getRouteMatch()->getParams();
        $id = $params['id'];

        try {
            $s3_data = \App::$s3Client->getObject([
                'Bucket' => S3_BUCKET,
                'Key' => 'form/' . $id . '.json'
            ])->toArray();
        } catch (\Aws\S3\Exception\S3Exception $th) {
            if ($th->getStatusCode() == 404) {
                throw new ClientException(ClientException::NOT_FOUND);
            } else {
                throw $th;
            }
        }

        try {
            $json_content = json_decode($s3_data['Body'], true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new ClientException(ClientException::INTERNAL_SERVER_ERROR, 'Configuration formatting problem');
        }

        try {
            $Form = new Form($json_content['config'] ?? []);
        } catch (\Exception $e) {
            throw new ClientException(ClientException::INTERNAL_SERVER_ERROR, 'Configuration fields problem');
        }

        if (! $Form->email_config && ! $Form->telegram_config) {
            throw new ClientException(ClientException::INTERNAL_SERVER_ERROR, 'No communication channel configured');
        }

        return $Form;
    }
}
