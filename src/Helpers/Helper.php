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
     * @return mixed
     */
    public function config($optionName)
    {
        $values = null;
        $optionNameParts = explode('.', $optionName);

        if (!count($optionNameParts)) {
            return $values;
        }

        $config = new Config();
        $config->load();

        $configOptions = $config->get($optionNameParts[0]);

        if (!is_array($configOptions)) {
            return $configOptions;
        }

        unset($optionNameParts[0]);

        // loop through the remaining parts of the config's name
        // so for example if the required config is 'app.api.version'
        // then this loop will check for $values['api'] then in the
        // next loop $values['version']
        
        $values = $configOptions;
        foreach ($optionNameParts as $part) {
            $values = $values[$part];
        }
 
        return $values;
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