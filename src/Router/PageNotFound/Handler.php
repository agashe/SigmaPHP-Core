<?php

namespace SigmaPHP\Core\Router\PageNotFound;

use SigmaPHP\Router\Interfaces\PageNotFoundHandlerInterface;

/**
 * Page Not Found Handler Class
 */
class Handler implements PageNotFoundHandlerInterface
{
    /**
     * Handle the the 404 - page not found case.
     *
     * @return text the html template
     */
    public function handle()
    {
        return container('response')->responseData(
            container('view')->render('errors/404'),
            'text/html',
            404
        );
    }
}
