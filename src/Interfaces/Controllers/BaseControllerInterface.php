<?php

namespace SigmaPHP\Core\Interfaces\Controllers;

/**
 * Base Controller Interface
 */
interface BaseControllerInterface
{
    /**
     * Return new response.
     * 
     * @param string $data
     * @param string $type
     * @param int $code
     * @param array $headers
     * @return SigmaPHP\Core\Http\Response
     */
    public function response(
        $data, 
        $type = 'text/html', 
        $code = 200, 
        $headers = []
    );

    /**
     * Return new JSON response.
     * 
     * @param array $data
     * @param int $code
     * @param array $headers
     * @return SigmaPHP\Core\Http\Response
     */
    public function json($data, $code = 200, $headers = []);

    /**
     * Render html template and return new Response.
     * 
     * @param string $templateName
     * @param array $variables
     * @return SigmaPHP\Core\Http\Response
     */
    public function render($templateName = '', $variables = []);
    
    /**
     * Render html template and return the content as string.
     * 
     * @param string $templateName
     * @param array $variables
     * @return string html content
     */
    public function renderView($templateName = '', $variables = []);
    
    /**
     * Handle cookies.
     * 
     * @return SigmaPHP\Core\Http\Cookie
     */
    public function cookie();
    
    /**
     * Handle sessions.
     * 
     * @return SigmaPHP\Core\Http\Session
     */
    public function session();
    
    /**
     * Handle files.
     * 
     * @return SigmaPHP\Core\Http\FileUpload
     */
    public function file();
}