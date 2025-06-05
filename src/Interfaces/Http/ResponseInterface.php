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
}