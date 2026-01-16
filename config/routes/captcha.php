<?php

use mrblue\mvc\Route;

return [
    'router' => [
        'routes' => [
            'GET' => [
                'childs' => [
                    'captcha' => [
                        'type' => Route::TYPE_SEGMENT,
                        'equal_to' => '/captcha',
                        'may_terminate' => false,
                        'childs' => [
                            'altcha' => [
                                'type' => Route::TYPE_LITERAL,
                                'equal_to' => '/altcha',
                                'defaults' => [
                                    'action' => 'get'
                                ],
                                'controller' => \Controller\Captcha\AltchaController::class,
                            ]
                        ]
                    ]
                ]
            ],
            'POST' => [
                'childs' => [
                    'captcha' => [
                        'type' => Route::TYPE_SEGMENT,
                        'equal_to' => '/captcha',
                        'may_terminate' => false,
                        'childs' => [
                            'altcha' => [
                                'type' => Route::TYPE_LITERAL,
                                'equal_to' => '/altcha',
                                'defaults' => [
                                    'action' => 'post'
                                ],
                                'controller' => \Controller\Captcha\AltchaController::class,
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ]
];
