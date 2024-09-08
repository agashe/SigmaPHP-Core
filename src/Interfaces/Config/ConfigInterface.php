<?php

namespace SigmaPHP\Core\Interfaces\Config;

/**
 * Config Interface
 */
interface ConfigInterface
{
    /**
     * Get full path for file/folder , relevant to 
     * the framework base path (outside vendor).
     * 
     * @param string $dis
     * @return string
     */
    public function getFullPath($dis);

    /**
     * Load all config files.
     * 
     * @return array
     */
    public function load();

    /**
     * Get all config values.
     * 
     * @return array
     */
    public function getAll();

    /**
     * Get config value.
     * 
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public function get($key, $default = '');

    /**
     * Set config value.
     * 
     * @param string $key
     * @param mixed $val
     * @return bool
     */
    public function set($key, $val);

    /**
     * Check if config value exists.
     * 
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * Set errors display.
     * 
     * @param string $env
     * @return bool
     */
    public function setErrorsDisplay($env);
}