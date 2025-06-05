<?php

namespace SigmaPHP\Core\Controllers;

use SigmaPHP\Core\Interfaces\Controllers\BaseControllerInterface;

/**
 * Base Controller Class
 */
class BaseController implements BaseControllerInterface
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
    final public function response(
        $data, 
        $type = 'text/html', 
        $code = 200, 
        $headers = []
    ) {
        return container('response')->responseData(
            $data, $type, $code, $headers);
    }

    /**
     * Return new JSON response.
     * 
     * @param array $data
     * @param int $code
     * @param array $headers
     * @return SigmaPHP\Core\Http\Response
     */
    final public function json($data, $code = 200, $headers = []) {
        return container('response')->responseJSON($data, $code, $headers);
    }

    /**
     * Render html template and return new Response.
     * 
     * @param string $templateName
     * @param array $variables
     * @return SigmaPHP\Core\Http\Response
     */
    final public function render($templateName = '', $variables = [])
    {
        return $this->response(
            container('view')->render($templateName, $variables)
        );
    }

    /**
     * Render html template and return the content as string.
     * 
     * @param string $templateName
     * @param array $variables
     * @return string html content
     */
    public function renderView($templateName = '', $variables = [])
    {
        return container('view')->render($templateName, $variables);
    }
    
    /**
     * Handle cookies.
     * 
     * @return SigmaPHP\Core\Http\Cookie
     */
    final public function cookie()
    {
        return container('cookie');
    }
    
    /**
     * Handle sessions.
     * 
     * @return SigmaPHP\Core\Http\Session
     */
    final public function session()
    {
        return container('session');
    }
    
    /**
     * Handle files.
     * 
     * @return SigmaPHP\Core\Http\FileUpload
     */
    final public function file()
    {
        return container('file');
    }    
}