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
     * @return self
     */
    final public function response(
        $data = [], 
        $type = 'text/html', 
        $code = 200, 
        $headers = []
    ) {
        header("Content-Type: $type");
        http_response_code($code);

        foreach ($headers as $key => $val) {
            header("$key: $val");
        }

        ob_start();

        echo $data;

        ob_end_flush();

        return $this;
    }

    /**
     * Return JSON Response.
     * 
     * @param mixed $data
     * @param int $code
     * @param array $headers
     * @return self
     */
    final public function responseJSON(
        $data = [],
        $code = 200,
        $headers = []
    ) {
        return $this->response(
            json_encode($data), 
            'application/json', 
            $code, 
            $headers
        );
    }
}