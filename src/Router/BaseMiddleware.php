<?php

namespace SigmaPHP\Core\Router;

use SigmaPHP\Core\Interfaces\Router\BaseMiddlewareInterface;

/**
 * Base Middleware Class
 */
abstract class BaseMiddleware implements BaseMiddlewareInterface
{
    /**
     * Redirect to an URL.
     * 
     * @param string $url
     * @return SigmaPHP\Core\Http\Response
     */
    public function redirect($url)
    {
        return container('response')->redirect($url);
    }
    
    /**
     * Redirect to a route.
     * 
     * @param string $routeName
     * @param array $parameters
     * @return SigmaPHP\Core\Http\Response
    */
    public function route($routeName, $parameters = [])
    {
        return container('response')->route($routeName, $parameters);
    }

    /**
     * Redirect to previous URL.
     * 
     * @return SigmaPHP\Core\Http\Response
     */
    public function back()
    {
        return container('response')->redirect(
            container('request')->previous()
        );
    }

    /**
     * Handle cookies.
     * 
     * @return SigmaPHP\Core\Http\Cookie
     */
    public function cookie()
    {
        return container('cookie');
    }
    
    /**
     * Handle sessions.
     * 
     * @return SigmaPHP\Core\Http\Session
     */
    public function session()
    {
        return container('session');
    }
    
    /**
     * Handle files.
     * 
     * @return SigmaPHP\Core\Http\File
     */
    public function file()
    {
        return container('file');
    }

    /**
     * Get the current request.
     * 
     * @return SigmaPHP\Core\Http\Request
     */
    public function request()
    {
        return container('request');
    }
}