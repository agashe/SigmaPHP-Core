<?php

namespace SigmaPHP\Core\Views;

use SigmaPHP\Core\Interfaces\Views\ViewHandlerInterface;
use Jenssegers\Blade\Blade;

/**
 * View Handler Class
 */
class ViewHandler implements ViewHandlerInterface
{
    /**
     * @var RyanChandler\Blade\Blade $templateEngine
     */
    private $templateEngine;
    
    /**
     * @var array $sharedTemplatesVariables
     */
    private $sharedTemplatesVariables;

    /**
     * ViewHandler Constructor
     */
    public function __construct($viewsPath, $cachePath, $sharedVariables = [])
    {
        $this->templateEngine = new Blade($viewsPath, $cachePath);
        $this->sharedTemplatesVariables = $sharedVariables;
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
        echo $this->templateEngine
            ->make(
                $templateName,
                $this->sharedTemplatesVariables + $variables
            )
            ->render();
    }
}