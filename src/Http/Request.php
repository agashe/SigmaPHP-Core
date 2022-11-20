<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Interfaces\Http\RequestInterface;

/**
 * Request Class
 */
class Request implements RequestInterface
{
    /**
     * Search array for value.
     * 
     * @param array $source
     * @param string $key
     * @return array|null
     */
    private function data($source = [], $key = null)
    {
        $result = $source;

        if (!empty($key)) {
            $result = array_key_exists($key, $result)? $result[$key] : null;
        }

        return $result;
    }

    /**
     * Get HTTP Request Data.
     * 
     * @param string $key
     * @return array|null
     */
    final public function get($key = null)
    {
        return $this->data($_GET, $key);
    }

    /**
     * Get HTTP Request Data.
     * 
     * @param string $key
     * @return array|null
     */
    final public function post($key = null)
    {
        return $this->data($_POST, $key);
    }

    /**
     * Get HTTP Request Uploaded Files.
     * 
     * @param string $key
     * @return array|null
     */
    final public function files($key = null)
    {
        return $this->data($_FILES, $key);
    }
}