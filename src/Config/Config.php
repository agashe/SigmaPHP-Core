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
     * Get full path for file/folder , relevant to 
     * the framework base path (outside vendor).
     * 
     * @param string $dis
     * @return string
     */
    public function getFullPath($dis)
    {
        $basePath = dirname(
            (new \ReflectionClass(
                \Composer\Autoload\ClassLoader::class
            ))->getFileName()
        , 3);

        return $basePath . '/' . $dis;
    }

    /**
     * Load all config files.
     * 
     * @return array
     */
    public function load()
    {
        $path = $this->getFullPath('config');

        if ($handle = opendir($path)) {
            while (($file = readdir($handle))) {
                if (in_array($file, ['.', '..'])) continue;
                $this->configs[str_replace('.php', '', $file)] = 
                    require $path . '/' . $file;
            }
        
            closedir($handle);
        }
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
     * Get config value , and support dot notation.
     * 
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public function get($key, $default = '')
    {
        $value = $this->configs;

        foreach (explode('.', $key) as $option) {
            $value = $value[$option] ?? null;
        }

        return $value ?? $default;
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
        error_reporting(($env == 'production')? 0 : E_ALL);
    }
}
