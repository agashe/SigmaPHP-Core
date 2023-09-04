<?php

namespace SigmaPHP\Core\Interfaces\App;

/**
 * Kernel Interface
 */
interface KernelInterface
{
    /**
     * Load configs, routes then run the app.
     * 
     * @return void
     */
    public function init();
}