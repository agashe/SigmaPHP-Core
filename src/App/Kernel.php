<?php

namespace SigmaPHP\Core\App;

use SigmaPHP\Core\Interfaces\App\KernelInterface;
use SigmaPHP\Core\Config\Config;
use SigmaPHP\Core\Router\Router;
use EnvParser\Parser;

/**
 * Kernel Class
 */
class Kernel implements KernelInterface
{
    /**
     * @var SigmaPHP\Core\Config\Config $configManager
     */
    private $configManager;

    /**
     * Kernel Constructor
     */
    public function __construct()
    {
        $this->configManager = new Config();
    }

    /**
     * Load configs, routes then run the app.
     * 
     * @return void
     */
    final public function init()
    {
        // Load environment variables
        $envParser = new Parser();

        $envParser->parse(
            $this->configManager->getFullPath('.env')
        );
        
        // load all config files
        $this->configManager->load();
        
        // set error display
        $this->configManager->setErrorsDisplay(
            $this->configManager->get('app.env')
        );

        // load the routes        
        $router = new Router(
            $this->configManager->getFullPath(
                $this->configManager->get('app.routes_path')
            ),
            $this->configManager->get('app.base_path')
        );
        
        $router->loadRoutes();
        
        // run the app
        $router->start();
    }
}