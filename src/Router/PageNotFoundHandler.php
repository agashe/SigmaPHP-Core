<?php

namespace SigmaPHP\Core\Router;

use SigmaPHP\Router\Interfaces\PageNotFoundHandlerInterface;
use SigmaPHP\Core\Exceptions\HttpException;

/**
 * Page Not Found Handler Class
 */
class PageNotFoundHandler implements PageNotFoundHandlerInterface
{
    /**
     * Handle the the 404 - page not found case.
     *
     * @return Response
     */
    public function handle()
    {
        return container('response')->responseData(
            container('view')->render('errors.404'),
            'text/html',
            404
        );
    }
}
