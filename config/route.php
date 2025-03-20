<?php

use Controller\MainController;
use mrblue\mvc\Route;

$routes = [
    'GET' => [
        'type' => Route::TYPE_METHOD,
        'equal_to' => 'GET',
        'may_terminate' => false,
        'childs' => [
            'index' => [
                'type' => Route::TYPE_LITERAL,
                'equal_to' => '/',
                'defaults' => [
                    'action' => 'index'
                ],
                'controller' => MainController::class,
                'childs' => []
            ],
        ]
    ],
    'POST' => [
        'type' => Route::TYPE_METHOD,
        'equal_to' => 'POST',
        'may_terminate' => false,
        'childs' => []
    ],
    'PUT' => [
        'type' => Route::TYPE_METHOD,
        'equal_to' => 'PUT',
        'may_terminate' => false,
        'childs' => []
    ],
    'PATCH' => [
        'type' => Route::TYPE_METHOD,
        'equal_to' => 'PATCH',
        'may_terminate' => false,
        'childs' => []
    ],
    'DELETE' => [
        'type' => Route::TYPE_METHOD,
        'equal_to' => 'DELETE',
        'may_terminate' => false,
        'childs' => []
    ],
    'OPTIONS' => [
        'type' => Route::TYPE_METHOD,
        'equal_to' => 'OPTIONS',
        'may_terminate' => false,
        'childs' => [
            'root' => [
                'type' => Route::TYPE_SEGMENT,
                'equal_to' => '/[:path]',
                'constraints' => [
                    'path' => '.*'
                ],
                'defaults' => [
                    'action' => 'index'
                ],
                'controller' => MainController::class,
            ]
        ]
    ]
];

/*
foreach (scandir('config/routes') as $dir) {
    if (is_file("config/routes/$dir")) {
        $routes = array_merge_recursive($routes, include "config/routes/$dir");
    }
}
*/
return [
    'router' => [
        'routes' => $routes
    ]
];
