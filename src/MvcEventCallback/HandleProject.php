<?php

namespace MvcEventCallback;

use Exception\ClientException;
use List\ProjectList;
use Model\Project;
use mrblue\mvc\MvcEvent;
use Utils\Utils;

class HandleProject extends AbstractMvcEventCallback {

    function run(MvcEvent $MvcEvent): void {

        $Controller = $MvcEvent->Mvc->getController();

        if (!$Controller instanceof \Controller\ProjectController) {
            return;
        }

        $project_name = filter_input(INPUT_GET, 'project_name', FILTER_VALIDATE_REGEXP, [
            'options' => [
                'regexp' => '/^[a-z0-9_-]+$/'
            ]
        ]);

        if (! $project_name) {

            http_response_code(500);
            echo (new ClientException(ClientException::BAD_REQUEST, 'Invalid project name'))->createJsonResponse(true)->render();
            exit;
        }

        $Projects = self::loadProjects();
        $Project = $Projects->search('name', $project_name);

        if (! $Project) {

            http_response_code(500);
            echo (new ClientException(ClientException::BAD_REQUEST, 'Invalid project name'))->createJsonResponse(true)->render();
            exit;
        }

        if (! $this->validateOrigin($Project->origins)) {
            http_response_code(500);
            echo (new ClientException(ClientException::FORBIDDEN, 'Origin not allowed'))->createJsonResponse(true)->render();
            exit;
        }


        $Controller->Project = $Project;
    }

    static function loadProjects(): ProjectList {

        $Projects = new ProjectList();
        foreach (getenv() as $env_name => $env_value) {
            if (str_starts_with($env_name, 'PROJECT_CONFIG_')) {
                $json_data = json_decode(base64_decode($env_value) ?: '', true, flags: JSON_THROW_ON_ERROR);
                $Projects->add($json_data);
            }
        }

        return $Projects;
    }

    function validateOrigin(array $origin_list_allowed): bool {
        $request_origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        return in_array($request_origin, $origin_list_allowed);
    }
}
