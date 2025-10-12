<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * Request Interface
 */
interface RequestInterface
{
    /**
     * Get HTTP GET Request Data.
     * 
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Get HTTP POST Request Data.
     * 
     * @param string $key
     * @return mixed
     */
    public function post($key);

    /**
     * Get HTTP Request Uploaded Files.
     * 
     * @param string $key
     * @return mixed
     */
    public function files($key);

    /**
     * Get current URL.
     * 
     * @return string
     */
    public function current();
    
    /**
     * Get request method.
     * 
     * @return string
     */
    public function method();
    
    /**
     * Get request headers.
     * 
     * @return string
     */
    public function headers();

    /**
     * Get previous URL.
     * 
     * @return string
     */
    public function previous();
}