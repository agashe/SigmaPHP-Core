<?php

namespace SigmaPHP\Core\Providers;

use SigmaPHP\Container\Interfaces\ServiceProviderInterface;
use SigmaPHP\Container\Container;
use SigmaPHP\DB\Connectors\Connector;

/**
 * DB Service Provider Class
 */
class DBServiceProvider implements ServiceProviderInterface
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
        $container->set('db', function (Container $container) {
            // get config manager
            $configManager = $container->get('config');
            $dbConfigs = $configManager->get('database.database_connection');

            // create new DB connection
            $connector = new Connector($dbConfigs);
            $connection = $connector->connect();

            return $connection;
        });
    }
}