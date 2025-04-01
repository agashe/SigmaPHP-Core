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
     * @param int $code
     * @param array $headers
     * @return mixed
     */
    public function response($data, $type, $code, $headers);

    /**
     * Return JSON Response.
     * 
     * @param mixed $data
     * @param int $code
     * @param array $headers
     * @return mixed
     */
    public function responseJSON($data, $code, $headers);
}