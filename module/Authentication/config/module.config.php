<?php

namespace Authentication;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Crypt\Password\Bcrypt;

return [
    'router' => [
        'routes' => [
            'register' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => Controller\RegisterController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'captcha_form_generate' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/[captcha/[:id]]',
                            'defaults' => [
                                'controller' => Controller\RegisterController::class,
                                'action' => 'generate',
                            ],
                        ],
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\LogoutController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'authentication' => [
            'orm_default' => [
                'identity_class'      => 'Application\Entity\User',
                'identity_property'   => 'name',
                'credential_property' => 'password',
                'credential_callable' => function (\Application\Entity\User $user, $password) {
                    $bcrypt = new Bcrypt();
                    if ($bcrypt->verify($password, $user->getPassword())) {
                        return true;
                    } else {
                        return false;
                    }
                },
            ],
        ],
    ],
];
