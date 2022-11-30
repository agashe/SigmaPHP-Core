<?php

namespace SigmaPHP\Core\Helpers;

use SigmaPHP\Core\Interfaces\Helpers\HelperInterface;

/**
 * Helper Class
 */
class Helper implements HelperInterface
{
    /**
     * Get the value of an environment variable.
     * 
     * @param string $key
     * @return string|null
     */
    final public function env($key)
    {
        return $_ENV[$key] ?? null;
    }

    /**
     * Generate URL from route or path.
     * 
     * @param string $route
     * @return string
     */
    final public function url($route)
    {
        $https = isset($_SERVER['HTTPS']) && 
            $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        
        return "{$https}://" . $_SERVER['SERVER_NAME'] . $route;
    }

    /**
     * Encrypt string using.
     * 
     * @param string $text
     * @param string $salt
     * @return string
     */
    final public function encrypt($text, $salt)
    {
        return openssl_encrypt(
            $text,
            'aes128',
            $this->env('APP-SECRET-KEY'),
            0,
            $salt
        );
    }

    /**
     * Decrypt string using.
     * 
     * @param string $text
     * @param string $salt
     * @return string
     */
    final public function decrypt($text, $salt)
    {
        return openssl_decrypt(
            $text,
            'aes128',
            $this->env('APP-SECRET-KEY'),
            0,
            $salt
        );
    }
}