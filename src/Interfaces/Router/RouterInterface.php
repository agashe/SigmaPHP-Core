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
     * Start the router.
     *
     * @return void
     */
    public function start();
}