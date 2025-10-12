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
     * @return mixed
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
     * @return mixed
     */
    final public function get($key = null)
    {
        return $this->data($_GET, $key);
    }

    /**
     * Get HTTP Request Data.
     * 
     * @param string $key
     * @return mixed
     */
    final public function post($key = null)
    {
        return $this->data($_POST, $key);
    }

    /**
     * Get HTTP Request Uploaded Files.
     * 
     * @param string $key
     * @return mixed
     */
    final public function files($key = null)
    {
        return $this->data($_FILES, $key);
    }

    /**
     * Get current URL.
     * 
     * @return string
     */
    public function current()
    {
        return rtrim(baseUrl(), '/') . '/' . 
            ltrim($_SERVER['REQUEST_URI'], '/');
    }
    
    /**
     * Get request method.
     * 
     * @return string
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Get request headers.
     * 
     * @return string
     */
    public function headers()
    {
        $headers = [];

        // we could use getallheaders() built-in , but it's only supporting
        // apache , so better use the following loop , to make sure all
        // platforms are covered
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                // format the headers name , from in to out :
                // 1- remove HTTP_
                // 2- replace underscore with spaces
                // 3- make all lowercase
                // 4- capitalize first letter of each word
                // 5- replace spaces with dashes

                $formattedHeaderName = str_replace(' ', '-',
                    ucwords(
                        strtolower(
                            str_replace('_', ' ',
                                substr($key, 5)
                            )
                        )
                    )
                );

                $headers[$formattedHeaderName] = $value;
            }
            else if ($key == "CONTENT_TYPE") {
               $headers["Content-Type"] = $value;
            }
            else if ($key == "CONTENT_LENGTH") {
                $headers["Content-Length"] = $value;
            } 
        }
        
        return $headers;
    }

    /**
     * Get previous URL.
     * 
     * @return string
     */
    public function previous()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}