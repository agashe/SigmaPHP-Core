<?php

namespace SigmaPHP\Core\App;

use App\Interfaces\MailServiceInterface;
use SigmaPHP\Core\Interfaces\App\KernelInterface;
use SigmaPHP\Container\Container;

/**
 * Kernel Class
 */
class Kernel implements KernelInterface
{
    /**
     * @var Container $container
     */
    private static $container;

    /**
     * Kernel Constructor
     */
    public function __construct()
    {
        // create DI container and load all service providers
        self::$container = new Container();

        // user defined providers (App\Providers)
        $providersPath = dirname(
            (new \ReflectionClass(
                \Composer\Autoload\ClassLoader::class
            ))->getFileName()
        , 3) . '/app/Providers';

        $providers = [];

        if ($handle = opendir($providersPath)) {
            while (($file = readdir($handle))) {
                if (in_array($file, ['.', '..'])) continue;
                $providers[] = ("App\\Providers\\" . 
                    (str_replace('.php', '', $file)));
            }
        
            closedir($handle);
        }

        // core providers
        foreach (array_merge([
            \SigmaPHP\Core\Providers\ConfigServiceProvider::class,
            \SigmaPHP\Core\Providers\RouterServiceProvider::class,
            \SigmaPHP\Core\Providers\ViewServiceProvider::class,
            \SigmaPHP\Core\Providers\DBServiceProvider::class,
            \SigmaPHP\Core\Providers\HTTPServiceProvider::class,
        ], $providers) as $provider) {
            self::$container->registerProvider($provider);
        }

        // enable the autowiring for all classes
        self::$container->autowire();
    }

    /**
     * Get the DI container instance from the Kernel.
     * 
     * @return Container
     */
    final public static function getContainer()
    {
        return self::$container;
    }

    /**
     * Load configs, routes then run the app.
     * 
     * @return void
     */
    final public function init()
    {
        // run the app
        self::$container->get('router')->start();
    }
}