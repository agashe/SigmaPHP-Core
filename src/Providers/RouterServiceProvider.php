<?php

namespace SigmaPHP\Core\Providers;

use SigmaPHP\Container\Interfaces\ServiceProviderInterface;
use SigmaPHP\Container\Container;
use SigmaPHP\Core\Router\PageNotFound\Handler;
use SigmaPHP\Core\Router\Router;
use SigmaPHP\Router\Router as RouterEngine;

/**
 * Router Service Provider Class
 */
class RouterServiceProvider implements ServiceProviderInterface
{
    /**
     * The boot method , will be called after all 
     * dependencies were defined in the container.
     * 
     * @param Container $container
     * @return void
     */
    public function boot(Container $container)
    {
        //
    }

    /**
     * Add a definition to the container.
     * 
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $container->set('router', function (Container $container) {
            // get config manager
            $configManager = $container->get('config');

            $router = new Router(
                $configManager::getFullPath(
                    $configManager->get('app.routes_path')
                )
            );

            // load the routes from the routing files (web.php.....etc)
            $router->loadRoutes();

            // set the Routing Engine (SigmaPHP-Router)            
            $router->setRouterEngine(new RouterEngine(
                $router->listRoutes(),
                $configManager->get('app.base_path')
            ));

            // register default 404 - page not found handler
            $router->setPageNotFoundHandler([Handler::class, 'handle']);

            return $router;
        });
    }
}