<?php

namespace SigmaPHP\Core\Config;

use SigmaPHP\Core\Interfaces\Config\ConfigInterface;

/**
 * Config Class
 */
class Config implements ConfigInterface
{
    /**
     * @var array $configs
     */
    private $configs;

    /**
     * Config Constructor
     */
    public function __constructor()
    {
        $this->configs = [];
    }

    /**
     * Get all config values.
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->configs;
    }

    /**
     * Get config value.
     * 
     * @param string $key
     * @return array|null
     */
    public function get($key)
    {
        return $this->configs[$key] ?? null;
    }

    /**
     * Set config value.
     * 
     * @param string $key
     * @param mixed $val
     * @return bool
     */
    public function set($key, $val)
    {
        return (bool) ($this->configs[$key] = $val);
    }

    /**
     * Check if config value exists.
     * 
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return (bool) $this->configs[$key];
    }

    /**
     * Set errors display.
     * 
     * @param string $env
     * @return bool
     */
    public function setErrorsDisplay($env)
    {        
        ini_set('display_errors', ($env != 'production'));
        ini_set('display_startup_errors', ($env != 'production'));
        error_reporting(($env == 'production')? 0 : 'E_ALL');
    }
}
