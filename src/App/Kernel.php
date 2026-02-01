<?php

namespace SigmaPHP\Core\App;

use SigmaPHP\Core\Interfaces\App\KernelInterface;
use SigmaPHP\Container\Container;
use SigmaPHP\Core\Config\Config;
use EnvParser\Parser;

/**
 * Kernel Class
 */
class Kernel implements KernelInterface
{
    /**
     * @var string framework version
     */
    const SIGMAPHP_FRAMEWORK_VERSION = '0.1.3';

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

        // load environment variables
        if (file_exists(Config::getFullPath('.env'))) {
            (new Parser())->parse(Config::getFullPath('.env'));
        }

        // Please note , since the config manager still not registered in the
        // container , it's nearly impossible to read the service providers
        // path from the config file , maybe in the future we can find a
        // solution for this issue , but as for now we will hardcoded it !
        $userDefinedProvidersPath = '/app/Providers';

        // user defined providers (App\Providers)
        $userDefinedProvidersPath = Config::getFullPath(
            $userDefinedProvidersPath
        );

        $userDefinedProviders = [];

        if (file_exists($userDefinedProvidersPath)) {
            if ($handle = opendir($userDefinedProvidersPath)) {
                while (($file = readdir($handle))) {
                    if (in_array($file, ['.', '..'])) continue;
                    $userDefinedProviders[] = ("App\\Providers\\" .
                    (str_replace('.php', '', $file)));
                }

                closedir($handle);
            }
        }

        // core providers
        self::$container->registerProviders(array_merge([
            \SigmaPHP\Core\Providers\ConfigServiceProvider::class,
            \SigmaPHP\Core\Providers\RouterServiceProvider::class,
            \SigmaPHP\Core\Providers\ViewServiceProvider::class,
            \SigmaPHP\Core\Providers\DBServiceProvider::class,
            \SigmaPHP\Core\Providers\HTTPServiceProvider::class,
        ], $userDefinedProviders));

        // define shared template variables in the container
        self::$container->set('shared_template_variables', []);

        // define custom template directives in the container
        self::$container->set('custom_template_directives', []);

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
        return !is_null(self::$container) ?
            self::$container :
            (new self)::$container;
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
