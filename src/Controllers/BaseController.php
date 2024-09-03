<?php

namespace SigmaPHP\Core\Controllers;

use SigmaPHP\Core\Interfaces\Controllers\BaseControllerInterface;
use SigmaPHP\Core\Config\Config;
use SigmaPHP\Core\Http\Request;
use SigmaPHP\Core\Http\Response;
use SigmaPHP\Core\Http\Cookie;
use SigmaPHP\Core\Http\Session;
use SigmaPHP\Core\Http\FileUpload;
use SigmaPHP\Core\Views\ViewHandler;

/**
 * Base Controller Class
 */
class BaseController implements BaseControllerInterface
{
    /**
     * @var SigmaPHP\Core\Config\Config $config
     */
    private $config;

    /**
     * @var SigmaPHP\Core\Http\Request $request
     */
    protected $request;

    /**
     * @var SigmaPHP\Core\Http\Response $response
     */
    protected $response;

    /**
     * @var SigmaPHP\Core\Http\Cookie $cookies
     */
    protected $cookies;
    
    /**
     * @var SigmaPHP\Core\Http\Session $sessions
     */
    protected $sessions;

    /**
     * @var SigmaPHP\Core\Http\FileUpload $filesUploader
     */
    protected $filesUploader;

    /**
     * @var SigmaPHP\Core\Views\ViewHandler $views
     */
    protected $views;

    /**
     * BaseController Constructor
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->config->load();

        $this->request = new Request();
        $this->response = new Response();
        $this->cookies = new Cookie();
        $this->sessions = new Session();
        $this->filesUploader = new FileUpload(
            $this->config->get('app.upload_path')
        );

        $this->views = new ViewHandler(
            $this->config->get('app.views_path'),
            $this->config->get('app.cache_path')
        );
    }
}