<?php

namespace SigmaPHP\Core\Views;

use SigmaPHP\Core\Interfaces\Views\ViewHandlerInterface;
use SigmaPHP\Template\Engine;

/**
 * View Handler Class
 */
class ViewHandler implements ViewHandlerInterface
{
    /**
     * @var SigmaPHP\Template\Engine $templateEngine
     */
    private $templateEngine;
    
    /**
     * @var array $sharedTemplatesVariables
     */
    private $sharedTemplatesVariables;

    /**
     * ViewHandler Constructor
     * 
     * @param string $viewsPath
     * @param string $cachePath
     * @param array $sharedVariables
     */
    public function __construct($viewsPath, $cachePath, $sharedVariables = [])
    {
        $this->templateEngine = new Engine($viewsPath, $cachePath);
        $this->templateEngine->setSharedVariables($sharedVariables);
    }

    /**
     * Render html template.
     * 
     * @param string $templateName
     * @param array $variables
     * @return string
     */
    final public function render($templateName = '', $variables = [])
    {
        return $this->templateEngine->render($templateName, $variables);
    }
}