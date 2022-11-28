<?php

namespace SigmaPHP\Core\Interfaces\Kernal;

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