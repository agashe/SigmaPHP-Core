<?php

namespace SigmaPHP\Core\Router\StaticAssets;

use SigmaPHP\Router\Interfaces\StaticAssetsHandlerInterface;

/**
 * Static Assets Handler Class
 */
class Handler implements StaticAssetsHandlerInterface
{
    /**
     * Serve static assets files.
     *
     * @param string $resourcePath
     * @return resource
     */
    public function handle($resourcePath)
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
            pathinfo($resourcePath, PATHINFO_EXTENSION),
            200,
            [
                'Content-Disposition' => 'inline;'
            ]
        );
    }
}
