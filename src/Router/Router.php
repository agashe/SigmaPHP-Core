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
     * Render html template.
     *
     * @return void
     */
    final public function start()
    {
        $this->routerEngine = new RouterEngine($this->routes, $this->basePath);
        
        $this->routerEngine->run();
    }
}