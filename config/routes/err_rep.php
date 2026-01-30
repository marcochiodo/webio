<?php

use mrblue\mvc\Route;

return [
    'router' => [
        'routes' => [
            'POST' => [
                'childs' => [
                    'err_rep' => [
                        'type' => Route::TYPE_LITERAL,
                        'equal_to' => '/err_rep',
                        'defaults' => [
                            'action' => 'post'
                        ],
                        'controller' => \Controller\ErrRepController::class,
                    ]
                ]
            ],
        ],
    ]
];
