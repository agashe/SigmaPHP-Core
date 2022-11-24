<?php

namespace SigmaPHP\Core\Controllers;

// todo

/**
 * Base Controller 
 */
class BaseController
{
    /**
     * Controller Constructor
     */
    public function __construct()
    {
        $this->sharedTemplatesVariables = [];
    }
}