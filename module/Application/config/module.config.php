<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/[home/page/:page]',
                    'constraints'    => [
                        'page' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'category' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/category[/:id][/page/:page]',
                    'constraints'    => [
                        'id'   => '[0-9]+',
                        'page' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CategoryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'article' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/article[/:action][/:id]',
                    'constraints'    => [
                        'action' => '[a-zA-Z-_]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ArticleController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'top_navigation' => Library\TopNavigationFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            //Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'label' => 'Home',
                'route' => 'home',
                'pages' => [
                    'contact' => [
                        'label' => 'Contact Us',
                        'route' => 'contact-us',
                    ],
                    'register' => [
                        'label' => 'Register',
                        'route' => 'register',
                    ],
                    'login' => [
                        'label' => 'Login',
                        'route' => 'login',
                    ],
                    'admin' => [
                        'label' => 'Admin Area',
                        'route' => 'admin',
                        'pages' => [
                            'users' => [
                                'label' => 'Users',
                                'route' => 'admin/users',
                                'pages' => [
                                    'edit' => [
                                        'label' => 'Edit',
                                        'route' => 'admin/users',
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'categories' => [
                                'label' => 'Categories',
                                'route' => 'admin/categories',
                                'pages' => [
                                    'add' => [
                                        'label' => 'Add',
                                        'route' => 'admin/categories',
                                        'action' => 'add',
                                    ],
                                    'edit' => [
                                        'label' => 'Edit',
                                        'route' => 'admin/categories',
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'articles' => [
                                'label' => 'Articles',
                                'route' => 'admin/articles',
                                'pages' => [
                                    'add' => [
                                        'label' => 'Add',
                                        'route' => 'admin/articles',
                                        'action' => 'add',
                                    ],
                                    'edit' => [
                                        'label' => 'Edit',
                                        'route' => 'admin/articles',
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'comments' => [
                                'label' => 'Comments',
                                'route' => 'admin/comments',
                            ],
                        ],
                    ],
                    'category' => [
                        'label' => 'Recipes',
                        'route' => 'category',
                    ],
                    'article' => [
                        'label' => 'Recipe',
                        'route' => 'article',
                    ],
                ],
            ],
        ],
        'top_navigation' => [
            'contact' => [
                'label' => 'Contact Us',
                'route' => 'contact-us',
            ],
            'portfolio' => [
                'label' => 'Portfolio',
                'route' => 'portfolio',
            ],
        ],
    ],
];
