<?php

namespace SigmaPHP\Core\Interfaces\Router;

/**
 * Router Interface
 */
interface RouterInterface
{
    /**
     * Set the router engine (the actual Router).
     *
     * @param object $routerEngine
     * @return void
     */
    public function setRouterEngine($routerEngine);

    /**
     * Load routes from all routes files.
     *
     * @return void
     */
    public function loadRoutes();

    /**
     * List all registered routes.
     *
     * @return array
     */
    public function listRoutes();

    /**
     * Generate URL from route's name.
     *
     * @param string $routeName
     * @param array $parameters
     * @return string
     */
    public function url($routeName, $parameters = []);

    /**
     * Set page not found handler.
     *
     * @param string|array $handler
     * @return void
     */
    public function setPageNotFoundHandler($handler);

    /**
     * Get base URL.
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Set static assets route path.
     *
     * @param string $path
     * @return void
     */
    public function setStaticAssetsRoutePath($path);

    /**
     * Set static assets route handler.
     *
     * @param StaticAssetsHandlerInterface $handler
     * @return void
     */
    public function setStaticAssetsRouteHandler($handler);


    /**
     * Start the router.
     *
     * @return void
     */
    public function start();
}
