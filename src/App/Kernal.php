<?php

namespace SigmaPHP\Core\App;

use SigmaPHP\Core\Interfaces\App\KernalInterface;
use SigmaPHP\Core\Config\Config;
use SigmaPHP\Core\Router\Router;

/**
 * Kernal Class
 */
class Kernal implements KernalInterface
{
    /**
     * @var string $configPath
     */
    private $configPath;

    /**
     * @var SigmaPHP\Core\Config\Config $configManager
     */
    private $configManager;

    /**
     * Kernal Constructor
     */
    public function __construct($configPath)
    {
        $this->configPath = $configPath;
        $this->configManager = new Config();
    }

    /**
     * Load configs, routes then run the app.
     * 
     * @return void
     */
    final public function init()
    {
        // load all config files
        $this->configManager->load($this->configPath);

        // load the routes
        $router = new Router($this->configManager->get('app')['routes_path']);

        // run the app
        $router->start();
    }
}