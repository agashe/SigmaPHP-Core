<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Interfaces\Http\ResponseInterface;

/**
 * Response Class
 */
class Response implements ResponseInterface
{
    /**
     * Return Response.
     * 
     * @param string $data
     * @param string $type
     * @param int $code
     * @param array $headers
     * @return self
     */
    final public function responseData(
        $data, 
        $type = 'text/html', 
        $code = 200, 
        $headers = []
    ) {
        if (!headers_sent()) {
            header("Content-Type: $type");
    
            foreach ($headers as $key => $val) {
                header("$key: $val");
            }
        }

        http_response_code($code);

        ob_start();

        echo $data;

        ob_end_flush();

        return $this;
    }

    /**
     * Return JSON Response.
     * 
     * @param array $data
     * @param int $code
     * @param array $headers
     * @return self
     */
    final public function responseJSON($data, $code = 200, $headers = []) {
        return $this->responseData(
            json_encode($data), 
            'application/json', 
            $code, 
            $headers
        );
    }
    
    /**
     * Redirect to an URL.
     * 
     * This method will create dummy html to handle the the redirect with
     * HTTP status code 302
     * 
     * @param string $url
     * @return self
     */
    final public function redirect($url) {
        $redirectContent = <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Redirecting...</title>
                <meta http-equiv="refresh" content="0; url={$url}">
            </head>
            <body>
                <p>You will be redirected to {$url}</p>
            </body>
            </html>
        HTML;

        // save current session as a previous_url
        container('session')->set(
            '_sigma_previous_url_', 
            container('request')->current()
        );

        return $this->responseData(
            $redirectContent, 
            'text/html', 
            302,
            [
                'Location' => $url
            ]
        );
    }

    /**
     * Redirect to a route.
     * 
     * @param string $routeName
     * @param array $parameters
     * @return self
     */
    public function route($routeName, $parameters = [])
    {
        return $this->redirect(url($routeName, $parameters));
    }
}