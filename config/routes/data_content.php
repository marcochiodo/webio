<?php

use mrblue\mvc\Route;

return [
    'router' => [
        'routes' => [
            'GET' => [
                'childs' => [
                    'data_content' => [
                        'type' => Route::TYPE_SEGMENT,
                        'equal_to' => '/data_content/[:data_content_name]',
                        'constraints' => [
                            'data_content_name' => '[a-zA-Z0-9\-_]+'
                        ],
                        'controller' => \Controller\DataContentController::class,
                        'defaults' => [
                            'action' => 'get'
                        ]
                    ]
                ]
            ]
        ],
    ]
];
