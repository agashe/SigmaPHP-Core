<?php

namespace SigmaPHP\Core\Interfaces\Router;

/**
 * Router Interface
 */
interface RouterInterface
{
    /**
     * Load routes from all routes files.
     * 
     * @return void
     */
    public function loadRoutes();

    /**
     * Generate URL from route's name.
     * 
     * @param string $routeName
     * @param array $parameters
     * @return string
     */
    public function url($routeName, $parameters = []);

    /**
     * Start the router.
     *
     * @return void
     */
    public function start();
}