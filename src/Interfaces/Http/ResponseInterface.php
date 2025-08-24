<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * Response Interface
 */
interface ResponseInterface
{
    /**
     * Return Response.
     * 
     * @param string $data
     * @param string $type
     * @param int $code
     * @param array $headers
     * @return self
     */
    public function responseData($data, $type, $code, $headers);

    /**
     * Return JSON Response.
     * 
     * @param array $data
     * @param int $code
     * @param array $headers
     * @return self
     */
    public function responseJSON($data, $code, $headers);

    /**
     * Redirect to an URL.
     * 
     * This method will create dummy html to handle the the redirect with
     * HTTP status code 302
     * 
     * @param string $url
     * @return self
     */
    public function redirect($url);

    /**
     * Redirect to a route.
     * 
     * @param string $routeName
     * @param array $parameters
     * @return self
     */
    public function route($routeName, $parameters = []);
}