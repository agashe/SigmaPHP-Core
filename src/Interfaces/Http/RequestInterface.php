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
}