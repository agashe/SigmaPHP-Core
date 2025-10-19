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
     * @var array $httpDirectives
     */
    private $httpDirectives;

    /**
     * ViewHandler Constructor
     * 
     * @param string $viewsPath
     * @param string $cachePath
     */
    public function __construct($viewsPath, $cachePath)
    {
        $this->templateEngine = new Engine($viewsPath, $cachePath);
        $this->httpDirectives = require('directives/http.php');
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

        $templateDirectives = array_merge(
            container('custom_template_directives'),
            $this->httpDirectives
        );
        
        foreach ($templateDirectives as $name => $callback)
        {
            $this->templateEngine->registerCustomDirective($name, $callback);
        }

        return $this->templateEngine->render($templateName, $variables);
    }
}