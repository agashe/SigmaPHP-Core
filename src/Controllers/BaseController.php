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

    /**
     * Get HTTP Request Data
     * 
     * @param string $key
     * @return array
     */
    final public function getRequestData($key = null)
    {
        $result = $_GET + $_POST;

        if (!empty($key)) {
            $result = array_key_exists($key, $result)? $result[$key] : false;
        }

        return $result;
    }

    /**
     * Get HTTP Request Uploaded Files
     * 
     * @param string $key
     * @return array
     */
    final public function getRequestFiles($key = null)
    {
        $result = $_FILES;

        if (!empty($key)) {
            $result = array_key_exists($key, $result)? $result[$key] : false;
        }

        return $result;
    }

    /**
     * Upload File
     * 
     * @param string $name
     * @return bool
     */
    final public function uploadFile($name = null)
    {
        $file = $this->getRequestFiles($name);

        if (empty($file))
            throw new \Exception("Error : File doesn't exist");

        if (move_uploaded_file($_FILES[$name]["tmp_name"], 
            './storage/Uploads/'.$_FILES[$name]["name"]))
            return true;

        return false;
    }

    /**
     * Return Response
     * 
     * @param mixed $data
     * @param string $type
     * @param int $code
     * @param array $headers
     * @return mixed
     */
    final public function response($data = [], $type = 'text/html', $code = 200, $headers = [])
    {
        header("Content-Type: $type");
        http_response_code($code);

        foreach ($headers as $key => $val) {
            header("$key: $val");
        }

        echo $data;
    }

    /**
     * Create Cookie
     * 
     * @param string $name
     * @param mixed $value
     * @param int $expireAt
     * @param array $options
     * @return bool
     */
    final public function setCookie(
        $name = null,
        $value = null,
        $expireAt = 0,
        $options = []
    ) {
        if (empty($name) || empty($value))
            return false;

        return setcookie($name, $value, ([
            'expires' => $expireAt
        ] + $options));
    }

    /**
     * Get Cookie Value
     * 
     * @param string $name
     * @return mixed
     */
    final public function getCookie($name = null)
    {
        return (!empty($name) && isset($_COOKIE[$name])) ?
            $_COOKIE[$name] : false;        
    }

    /**
     * Delete Cookie
     * 
     * @param string $name
     * @return bool
     */
    final public function delCookie($name = null)
    {
        if (empty($name) || !isset($_COOKIE[$name]))
            return false;

        return setcookie($name, '', [
            'expires' => time()-3600
        ]);
    }

    /**
     * Create Session
     * 
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    final public function setSession($name = null, $value = null)
    {
        if (empty($name) || empty($value))
            return false;

        @session_start();        
        $_SESSION[$name] = $value;

        return true;
    }

    /**
     * Get Session Value
     * 
     * @param string $name
     * @return mixed
     */
    final public function getSession($name = null)
    {
        if (empty($name) || !isset($_SESSION[$name]))
            return false;
        
        @session_start();
        return  $_SESSION[$name];        
    }

    /**
     * Delete Session
     * 
     * @param string $name
     * @return bool
     */
    final public function delSession($name = null)
    {
        if (empty($name) || !isset($_SESSION[$name]))
            return false;

        @session_start();        
        unset($_SESSION[$name]);

        return true;
    }
}