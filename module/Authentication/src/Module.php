<?php

namespace Authentication;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Application\Entity\User;
use Zend\Session\SessionManager;
use Zend\Mvc\MvcEvent;

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
                // forms
                Form\RegisterForm::class => function ($container) {
                    $form = new Form\RegisterForm('/register/captcha/');
                    $form->setInputFilter($container->get(Filter\RegisterFilter::class));
                    return $form;
                },
                Form\LoginForm::class => function ($container) {
                    $form = new Form\LoginForm();
                    $form->setInputFilter($container->get(Filter\LoginFilter::class));
                    return $form;
                },

                // filters
                Filter\RegisterFilter::class => function ($container) {
                    return new Filter\RegisterFilter();
                },
                Filter\LoginFilter::class => function ($container) {
                    return new Filter\LoginFilter();
                },

                // authentication

                AuthenticationService::class => function ($container) {
                    return $container->get('doctrine.authenticationservice.orm_default');
                },
            ],
            'invokables' => [
                'authStorage' => Model\AppAuthStorage::class,
                'validationService' => Service\ValidationService::class,
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\RegisterController::class => function ($container) {
                    return new Controller\RegisterController(
                        $container->get(EntityManager::class),
                        $container->get(Form\RegisterForm::class),
                        $container->get('validationService'),
                        $container->get(AuthenticationService::class)
                    );
                },
                Controller\LoginController::class => function ($container) {
                    return new Controller\LoginController(
                        $container->get(EntityManager::class),
                        $container->get(Form\LoginForm::class),
                        $container->get(AuthenticationService::class),
                        $container->get('authStorage')
                    );
                },
                Controller\LogoutController::class => function ($container) {
                    return new Controller\LogoutController(
                        $container->get(AuthenticationService::class),
                        $container->get('authStorage')
                    );
                },
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

        /*
             When browser was updated, changing its User Agent string an error occurred: Session validation failed.
             I added this block and app again started to work.

             Start block
         */
        try {
            if (! $sessionManager->isValid()) {
                $sessionManager->forgetMe();
                throw new \Exception('An error occurred. Please re-login.');
            }
        }catch (\Exception $e) {
            echo $e->getMessage();
        }

        /* End block */
    }
}
