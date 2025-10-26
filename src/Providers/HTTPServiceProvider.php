<?php

namespace SigmaPHP\Core\Providers;

use SigmaPHP\Container\Interfaces\ServiceProviderInterface;
use SigmaPHP\Container\Container;
use SigmaPHP\Core\Http\Request;
use SigmaPHP\Core\Http\Response;
use SigmaPHP\Core\Http\Cookie;
use SigmaPHP\Core\Http\Session;
use SigmaPHP\Core\Http\File;

/**
 * HTTP Service Provider Class
 */
class HTTPServiceProvider implements ServiceProviderInterface
{
    /**
     * The boot method , will be called after all 
     * dependencies were defined in the container.
     * 
     * @param Container $container
     * @return void
     */
    public function boot(Container $container)
    {
        //
    }

    /**
     * Add a definition to the container.
     * 
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $container->set('request', function (Container $container) {
            return (new Request());
        });
        
        $container->set('response', function (Container $container) {
            return (new Response());
        });
        
        $container->set('cookie', function (Container $container) {
            return (new Cookie());
        });
        
        $container->set('session', function (Container $container) {
            return (new Session());
        });

        $container->set('file', function (Container $container) {
            return new File($container->get('config')->get('app.upload_path'));
        });
    }
}