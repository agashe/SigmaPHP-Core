<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * Cookie Interface
 */
interface CookieInterface
{
    /**
     * Create Cookie.
     * 
     * @param string $name
     * @param string $value
     * @param int $expireAt
     * @param array $options
     * @return bool
     */
    public function set($name, $value, $expireAt, $option);

    /**
     * Get Cookie Value.
     * 
     * @param string $name
     * @return string
     */
    public function get($name);

    /**
     * Delete Cookie.
     * 
     * @param string $name
     * @return bool
     */
    public function delete($name);
}