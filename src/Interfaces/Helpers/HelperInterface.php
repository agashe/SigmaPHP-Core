<?php

namespace SigmaPHP\Core\Interfaces\Helpers;

/**
 * Helper Interface
 */
interface HelperInterface
{
    /**
     * Get the value of an environment variable.
     * 
     * @param string $key
     * @param string $default
     * @return string|null
     */
    public function env($key, $default = null);

    /**
     * Get the value of config option.
     * 
     * @param string $optionName
     * @return mixed
     */
    public function config($optionName);

    /**
     * Generate URL from route or path.
     * 
     * @param string $route
     * @return string
     */
    public function url($route);

    /**
     * Encrypt string using.
     * 
     * @param string $text
     * @param string $salt
     * @return string
     */
    public function encrypt($text, $salt);

    /**
     * Decrypt string using.
     * 
     * @param string $text
     * @param string $salt
     * @return string
     */
    public function decrypt($text, $salt);
}