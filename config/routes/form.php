<?php

use Controller\FormController;
use mrblue\mvc\Route;

const FORM_ID_REGEX = '[a-zA-Z]([a-zA-Z0-9\-\_]*[a-zA-Z0-9])?';

return [
    'router' => [
        'routes' => [
            'GET' => [
                'childs' => [
                    'form' => [
                        'type' => Route::TYPE_LITERAL,
                        'equal_to' => '/form',
                        'controller' => FormController::class,
                        'defaults' => [
                            'action' => 'get'
                        ],
                        'may_terminate' => false,
                        'childs' => [
                            'id' => [
                                'type' => Route::TYPE_SEGMENT,
                                'equal_to' => '/:id',
                                'constraints' => [
                                    'id' => FORM_ID_REGEX
                                ],
                                'defaults' => [
                                    'action' => 'get'
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'POST' => [
            'childs' => [
                'form' => [
                    'type' => Route::TYPE_LITERAL,
                    'equal_to' => '/form',
                    'controller' => FormController::class,
                    'defaults' => [
                        'action' => 'post'
                    ],
                    'may_terminate' => false,
                    'childs' => [
                        'id' => [
                            'type' => Route::TYPE_SEGMENT,
                            'equal_to' => '/:id',
                            'constraints' => [
                                'id' => FORM_ID_REGEX
                            ],
                            'defaults' => [
                                'action' => 'post'
                            ],
                        ]
                    ]
                ]
            ]
        ]
    ]
];
