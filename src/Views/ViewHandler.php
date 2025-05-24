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
     * ViewHandler Constructor
     * 
     * @param string $viewsPath
     * @param string $cachePath
     */
    public function __construct($viewsPath, $cachePath)
    {
        $this->templateEngine = new Engine($viewsPath, $cachePath);
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
        $this->templateEngine->setSharedVariables(
            container('shared_template_variables')
        );

        foreach (container('custom_template_directives') as $name => $callback)
        {
            $this->templateEngine->registerCustomDirective($name, $callback);
        }

        return $this->templateEngine->render($templateName, $variables);
    }
}