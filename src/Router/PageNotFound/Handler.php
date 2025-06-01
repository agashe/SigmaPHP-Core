<?php

namespace SigmaPHP\Core\Router\PageNotFound;

/**
 * Page Not Found Handler Class
 */
class Handler
{
    /**
     * Handle the the 404 - page not found case.
     * 
     * @return text the html template
     */
    public function handle()
    {
        return container('response')->response(
            container('view')->render('errors/404'),
            'text/html',
            404
        );
    }
}