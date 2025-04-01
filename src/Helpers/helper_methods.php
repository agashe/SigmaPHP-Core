<?php

if (!function_exists('container')) {
    function container($item = '') {
        $container = \SigmaPHP\Core\App\Kernel::getContainer();
        return empty($item) ? $container : $container->get($item);
    }
}

if (!function_exists('env')) {
    function env($key, $default = '') {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    function config($key, $default = '') {
        return container('config')->get($key, $default);
    }
}

if (!function_exists('url')) {
    function url($route, $parameters = []) {
        return container('router')->url($route, $parameters);
    }
}

if (!function_exists('encrypt')) {
    function encrypt($text, $salt = '') {
        return openssl_encrypt(
            $text,
            'aes128',
            env('APP_SECRET_KEY'),
            0,
            !empty($salt) ? 
                $salt :
                substr(hash('sha256', env('APP_SECRET_KEY')), 0, 16)
        );
    }
}

if (!function_exists('decrypt')) {
    function decrypt($text, $salt = '') {
        return openssl_decrypt(
            $text,
            'aes128',
            env('APP_SECRET_KEY'),
            0,
            !empty($salt) ? 
                $salt :
                substr(hash('sha256', env('APP_SECRET_KEY')), 0, 16)
        );
    }
}