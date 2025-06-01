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
        http_response_code(404);

        container('view')->render(root_path('404.template.html'));

        exit();
    }
}