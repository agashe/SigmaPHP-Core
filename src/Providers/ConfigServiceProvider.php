<?php

namespace SigmaPHP\Core\Providers;

use SigmaPHP\Container\Interfaces\ServiceProviderInterface;
use SigmaPHP\Container\Container;
use SigmaPHP\Core\Config\Config;
use EnvParser\Parser;

/**
 * Config Service Provider Class
 */
class ConfigServiceProvider implements ServiceProviderInterface
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
        $container->set('config', function () {
            // create new config manager
            $configManager = new Config();

            // load environment variables
            $envParser = new Parser();

            $envParser->parse(
                $configManager->getFullPath('.env')
            );
            
            // load all config files
            $configManager->load();
            
            // set error display
            $configManager->setErrorsDisplay(
                $configManager->get('app.env')
            );

            return $configManager;
        });
    }
}