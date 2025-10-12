<?php

namespace SigmaPHP\Core\Interfaces\Router;

/**
 * Base Middleware Interface
 */
interface BaseMiddlewareInterface
{
    /**
     * Redirect to an URL.
     * 
     * @param string $url
     * @return SigmaPHP\Core\Http\Response
     */
    public function redirect($url);
    
    /**
     * Redirect to a route.
     * 
     * @param string $routeName
     * @param array $parameters
     * @return SigmaPHP\Core\Http\Response
    */
    public function route($routeName, $parameters = []);

    /**
     * Redirect to previous URL.
     * 
     * @param string $url
     * @return SigmaPHP\Core\Http\Response
     */
    public function back();

    /**
     * Handle cookies.
     * 
     * @return SigmaPHP\Core\Http\Cookie
     */
    public function cookie();
    
    /**
     * Handle sessions.
     * 
     * @return SigmaPHP\Core\Http\Session
     */
    public function session();
    
    /**
     * Handle files.
     * 
     * @return SigmaPHP\Core\Http\FileUpload
     */
    public function file();
}