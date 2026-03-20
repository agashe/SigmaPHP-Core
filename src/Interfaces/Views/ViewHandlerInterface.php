<?php

namespace SigmaPHP\Core\Interfaces\Views;

/**
 * ViewHandler Interface
 */
interface ViewHandlerInterface
{
    /**
     * Render html template.
     *
     * @param string $templateName
     * @param array $variables
     * @return void
     */
    public function render($templateName = '', $variables = []);

    /**
     * Check if template exists.
     *
     * @param string $templateName
     * @return bool
     */
    public function templateExists($templateName);
}
