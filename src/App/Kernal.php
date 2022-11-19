<?php

require '../vendor/autoload.php';

// Load app config
$appConfig = require '../config/app.php';

// Show errors
$errorsDisplay = [
    'display_errors'         => true,
    'display_startup_errors' => true,
    'error_reporting'        => E_ALL
];

if ($appConfig['env'] == 'production') {
    $errorsDisplay = [
        'display_errors'         => false,
        'display_startup_errors' => false,
        'error_reporting'        => false
    ];
}

ini_set('display_errors', $errorsDisplay['display_errors']);
ini_set('display_startup_errors', $errorsDisplay['display_startup_errors']);
error_reporting($errorsDisplay['error_reporting']);

// Create new router and load routes
$routes = [];

if ($handle = opendir($appConfig['routes_path'])) {
    while (($file = readdir($handle))) {
        if (in_array($file, ['.', '..'])) continue;
        $routes += require $appConfig['routes_path'] . '/' . $file;
    }

    closedir($handle);
}

$router = new \Bramus\Router\Router();

foreach ($routes as $route) {
    $router->{$route['method']}($route['uri'], $route['controller']);
}

$router->run();