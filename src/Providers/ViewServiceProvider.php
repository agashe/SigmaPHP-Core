<?php

namespace SigmaPHP\Core\Providers;

use SigmaPHP\Container\Interfaces\ServiceProviderInterface;
use SigmaPHP\Container\Container;
use SigmaPHP\Core\Views\ViewHandler;

/**
 * View Service Provider Class
 */
class ViewServiceProvider implements ServiceProviderInterface
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
        $container->set('view', function (Container $container) {
            // get config manager
            $configManager = $container->get('config');

            $viewHandler = new ViewHandler(
                $configManager->get('app.views_path'),
                $configManager->get('app.cache_path')
            );

            return $viewHandler;
        });
    }
}