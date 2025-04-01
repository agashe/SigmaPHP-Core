<?php

namespace SigmaPHP\Core\Interfaces\App;

use SigmaPHP\Container\Container;

/**
 * Kernel Interface
 */
interface KernelInterface
{
    /**
     * Get the DI container instance from the Kernel.
     * 
     * @return Container
     */
    public static function getContainer();

    /**
     * Load configs, routes then run the app.
     * 
     * @return void
     */
    public function init();
}