<?php

namespace SigmaPHP\Core\Interfaces\App;

/**
 * Kernal Interface
 */
interface KernalInterface
{
    /**
     * Load configs, routes then run the app.
     * 
     * @return void
     */
    public function init();
}