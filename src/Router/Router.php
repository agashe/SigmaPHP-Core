<?php

namespace SigmaPHP\Core\Router;

use SigmaPHP\Core\Interfaces\Router\RouterInterface;
use SigmaPHP\Router\Router as RouterEngine;

/**
 * Router Class
 */
class Router implements RouterInterface
{
    /**
     * @var \SigmaPHP\Router\Router $routerEngine
     */
    private $routerEngine;

    /**
     * @var string $routeFilesPath
     */
    private $routeFilesPath;

    /**
     * @var string $basePath
     */
    private $basePath;

    /**
     * @var array $routes
     */
    private $routes;
    
    /**
     * Router Constructor
     * 
     * @param string $routeFilesPath
     * @param string $basePath
     */
    public function __construct($routeFilesPath, $basePath = '')
    {
        $this->routeFilesPath = $routeFilesPath;
        $this->basePath = $basePath;
        $this->routes = [];

        $this->loadRoutes();

        $this->routerEngine = new RouterEngine($this->routes, $this->basePath);
    }

    /**
     * Load routes from all routes files.
     * 
     * @return void
     */
    final public function loadRoutes()
    {
        if ($handle = opendir($this->routeFilesPath)) {
            while (($file = readdir($handle))) {
                if (in_array($file, ['.', '..'])) continue;
                $this->routes += require $this->routeFilesPath . '/' . $file;
            }
        
            closedir($handle);
        }
    }

    /**
     * Generate URL from route's name.
     * 
     * @param string $routeName
     * @param array $parameters
     * @return string
     */
    public function url($routeName, $parameters = [])
    {
        return $this->routerEngine->url($routeName, $parameters);
    }

    /**
     * Set page not found handler.
     * 
     * @param string|array $handler 
     * @return void
     */
    public function setPageNotFoundHandler($handler)
    {
        $this->routerEngine->setPageNotFoundHandler($handler);
    }
    
    /**
     * Start the router execution.
     *
     * @return void
     */
    final public function start()
    {        
        $this->routerEngine->setActionRunner(CoreActionRunner::class);
        $this->routerEngine->run();
    }
}