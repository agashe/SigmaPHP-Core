<?php

namespace SigmaPHP\Core\Router\StaticAssets;

/**
 * Static Assets Handler Class
 */
class Handler
{
    /**
     * Serve static assets files.
     *
     * @param string $resourcePath
     * @return resource
     */
    public static function handle($resourcePath)
    {
        $resourcePath = root_path('public/' . $resourcePath);

        if (!file_exists($resourcePath)) {
            return container('response')->responseData(
                container('view')->render('errors/404'),
                'text/html',
                404
            );
        }

        return container()->get('response')->responseData(
            file_get_contents($resourcePath),
            mime_content_type($resourcePath),
            200,
            [
                'Content-Disposition' => 'inline;'
            ]
        );
    }
}
