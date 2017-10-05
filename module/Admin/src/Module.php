<?php

namespace Admin;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\EntityManager;
use Zend\Session\SessionManager;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                // Forms

                Form\CategoryForm::class => function ($container) {
                    $form = new Form\CategoryForm(
                        $container->get(EntityManager::class)
                    );
                    $form->setInputFilter($container->get(Filter\CategoryFilter::class));
                    return $form;
                },
                Form\ArticleForm::class => function ($container) {
                    $form = new Form\ArticleForm(
                        $container->get(EntityManager::class)
                    );
                    $form->setInputFilter($container->get(Filter\ArticleFilter::class));
                    return $form;
                },

                // Filters

                Filter\CategoryFilter::class => function ($container) {
                    return new Filter\CategoryFilter();
                },
                Filter\ArticleFilter::class => function ($container) {
                    return new Filter\ArticleFilter();
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\UserController::class => function ($container) {
                    return new Controller\UserController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\CategoryController::class => function ($container) {
                    return new Controller\CategoryController(
                        $container->get(EntityManager::class),
                        $container->get(Form\CategoryForm::class),
                        $container->get('formService'),
                        $container->get('validationService')
                    );
                },
                Controller\ArticleController::class => function ($container) {
                    return new Controller\ArticleController(
                        $container->get(EntityManager::class),
                        $container->get(Form\ArticleForm::class),
                        $container->get('formService'),
                        $container->get('validationService')
                    );
                },
                Controller\CommentController::class => function ($container) {
                    return new Controller\CommentController(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }

    public function getControllerPluginConfig()
    {
        return [
            'factories' => [
                'getAccess' => function ($container) {
                    return new Controller\Plugin\GetAccess(
                        $container->get(AuthenticationService::class)
                    );
                },
            ],
        ];
    }

    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'cutString' => View\Helper\CutString::class,
            ],
            'factories' => [
                'getCategoryParentName' => function ($container) {
                    return new View\Helper\GetCategoryParentName(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }

    public function onBootstrap(MvcEvent $e)
    {
        // For check access to admin area
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            __NAMESPACE__,
            'dispatch',
            function ($e) {
                $controller = $e->getTarget();
                $controller->getAccess();
                $controller->layout('layout/adminLayout');
            }
        );

        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();

        // Следующая строка инстанцирует SessionManager и автоматически
        // делает его выбираемым 'по умолчанию'.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }
}
