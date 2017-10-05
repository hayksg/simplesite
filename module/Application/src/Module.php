<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'getYear'           => View\Helper\GetYear::class,
                'getShortString'    => View\Helper\GetShortString::class,
                'getFlashMessenger' => View\Helper\GetFlashMessenger::class,
                'cutStr'            => View\Helper\CutStr::class,
                'getImage'          => View\Helper\GetImage::class,
            ],
            'factories' => [
                'getCategory' => function ($container) {
                    return new View\Helper\GetCategory(
                        $container->get(EntityManager::class)
                    );
                },
                'GetRouteParams' => function ($container) {
                    return new View\Helper\GetRouteParams(
                        $container->get('Application')
                    );
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function ($container) {
                    return new Controller\IndexController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\CategoryController::class => function ($container) {
                    return new Controller\CategoryController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\ArticleController::class => function ($container) {
                    return new Controller\ArticleController(
                        $container->get(EntityManager::class),
                        $container->get('formService')
                    );
                },
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'formService' => Service\FormService::class,
            ],
        ];
    }

    public function getControllerPluginConfig()
    {
        return [
            'invokables' => [
                'textFilter' => Controller\Plugin\TextFilter::class,
            ],
        ];
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // Следующая строка инстанцирует SessionManager и автоматически
        // делает его выбираемым 'по умолчанию'.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }
}
