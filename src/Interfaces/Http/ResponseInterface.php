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
     * @param mixed $data
     * @param string $type
     * @param int $statusCode
     * @param array $headers
     * @return mixed
     */
    public function response($data, $type, $statusCode, $headers);

    /**
     * Return JSON Response.
     * 
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     * @return mixed
     */
    public function responseJSON($data, $statusCode, $headers);
}