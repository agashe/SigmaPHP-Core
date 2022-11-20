<?php

namespace SigmaPHP\Core\Controllers;

use RyanChandler\Blade\Blade;

/**
 * Base Controller 
 */
class BaseController
{
    /**
     * @var RyanChandler\Blade\Blade $viewHandler
     */
    protected $viewHandler;

    /**
     * @var array $sharedTemplatesVariables
     */
    protected $sharedTemplatesVariables;

    /**
     * Controller Constructor
     */
    public function __construct()
    {
        $this->viewHandler = new Blade('../src/Views', '../../storage/Cache');
        $this->sharedTemplatesVariables = [];
    }

    /**
     * Render html template
     * 
     * @param string $templateName
     * @param array $variables
     * @return void
     */
    final public function render($templateName = '', $variables = [])
    {
        echo $this->viewHandler
            ->make(
                $templateName,
                $this->sharedTemplatesVariables + $variables
            )
            ->render();
    }
}