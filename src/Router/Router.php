<?php

namespace SigmaPHP\Core\Router;

use SigmaPHP\Core\Interfaces\Router\RouterInterface;
use Bramus\Router\Router as RouterEngine;

/**
 * Router Class
 */
class Router implements RouterInterface
{
    /**
     * @var \Bramus\Router\Router $routerEngine
     */
    private $routerEngine;

    /**
     * @var string $routesPath
     */
    private $routesPath;

    /**
     * @var array $routes
     */
    private $routes;
    
    /**
     * Router Constructor
     */
    public function __construct($routesPath)
    {
        $this->routerEngine = new RouterEngine();
        $this->routesPath = $routesPath;
        $this->routes = [];
    }

    /**
     * Load routes from all routes files.
     * 
     * @return void
     */
    final public function loadRoutes()
    {
        if ($handle = opendir($this->routesPath)) {
            while (($file = readdir($handle))) {
                if (in_array($file, ['.', '..'])) continue;
                $this->routes += require $this->routesPath . '/' . $file;
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
        foreach ($this->routes as $route) {
            $this->routerEngine
                ->{$route['method']}(
                    $route['uri'], 
                    "{$route['controller']}@{$route['action']}"
                );
        }
        
        $this->routerEngine->run();
    }
}