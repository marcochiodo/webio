<?php

namespace MvcEventCallback;

use Exception\ClientException;
use List\ProjectList;
use Model\Project;
use mrblue\mvc\MvcEvent;

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

        $apcu_key = 'projects';
        $cache_filepath = sys_get_temp_dir() . "/projects-cache.txt";

        if (apcu_exists($apcu_key)) {
            $projects_data = apcu_fetch($apcu_key);
        } elseif (is_file($cache_filepath)) {
            $projects_data = unserialize(file_get_contents($cache_filepath));
            apcu_store($apcu_key, $projects_data);
        }

        if (empty($projects_data)) {
            $projects_data = [];
            foreach (scandir(CONFIG_PROJECTS_PATCH) as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if ($file[0] === '.' || $extension !== 'json') {
                    continue;
                }
                try {
                    $file_data = json_decode(file_get_contents(CONFIG_PROJECTS_PATCH . '/' . $file), true, flags: JSON_THROW_ON_ERROR);
                    $Project = new Project($file_data);
                } catch (\Exception $e) {
                    continue;
                }
                $projects_data[$Project->name] = $file_data;
            }
            file_put_contents($cache_filepath, serialize($projects_data), LOCK_EX);
        }

        $Projects = new ProjectList();
        foreach ($projects_data as $project_name => $project_data) {
            $Projects->add($project_data);
        }

        return $Projects;
    }

    function validateOrigin(array $origin_list_allowed): bool {
        $request_origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        return in_array($request_origin, $origin_list_allowed);
    }
}
