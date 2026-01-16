<?php

use mrblue\mvc\Route;

return [
    'router' => [
        'routes' => [
            'POST' => [
                'childs' => [
                    'form' => [
                        'type' => Route::TYPE_SEGMENT,
                        'equal_to' => '/form/[:form_name]',
                        'constraints' => [
                            'form_name' => '[a-zA-Z0-9_]+'
                        ],
                        'may_terminate' => false,
                        'controller' => \Controller\FormController::class,
                        'childs' => [
                            'submit' => [
                                'type' => Route::TYPE_LITERAL,
                                'equal_to' => '/submit',
                                'defaults' => [
                                    'action' => 'submit'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
    ]
];
