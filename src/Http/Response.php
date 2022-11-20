<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Interfaces\Http\ResponseInterface;

/**
 * Response Class
 */
class Response implements ResponseInterface
{
    /**
     * Return Response.
     * 
     * @param mixed $data
     * @param string $type
     * @param int $code
     * @param array $headers
     * @return mixed
     */
    final public function response(
        $data = [], 
        $type = 'text/html', 
        $statusCode = 200, 
        $headers = []
    ) {
        header("Content-Type: $type");
        http_response_code($statusCode);

        foreach ($headers as $key => $val) {
            header("$key: $val");
        }

        echo $data;
    }

    /**
     * Return Response.
     * 
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     * @return mixed
     */
    final public function responseJSON(
        $data = [],
        $statusCode = 200,
        $headers = []
    ) {
        $this->response($data, 'application/json', $statusCode, $headers);
    }
}