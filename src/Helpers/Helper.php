<?php

namespace SigmaPHP\Core\Helpers;

use SigmaPHP\Core\Interfaces\Helpers\HelperInterface;
use SigmaPHP\Core\Config\Config;

/**
 * Helper Class
 */
class Helper implements HelperInterface
{
    /**
     * Get the value of an environment variable.
     * 
     * @param string $key
     * @param string $default
     * @return string|null
     */
    public function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Get the value of config option.
     * 
     * @param string $optionName
     * @param string $default
     * @return mixed
     */
    public function config($optionName, $default = '')
    {
        $config = new Config();
        $config->load();
 
        return $config->get($optionName, $default);
    }

    /**
     * Generate URL from route or path.
     * 
     * @param string $route
     * @return string
     */
    final public function url($route)
    {
        $url = '';

        if (!empty($this->env('APP_URL'))) {
            $url = $this->env('APP_URL') . '/' . $route;
        } else {
            $https = isset($_SERVER['HTTPS']) && 
                $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
            
            $url = "{$https}://" . $_SERVER['SERVER_NAME'] . '/' . $route;
        }

        return $url;
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
            $this->env('APP_SECRET_KEY'),
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
            $this->env('APP_SECRET_KEY'),
            0,
            $salt
        );
    }
}