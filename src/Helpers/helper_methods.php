<?php

if (!function_exists('env')) {
    function env($key, $default = '') {
        $helperClass = new \SigmaPHP\Core\Helpers\Helper();
        return $helperClass->env($key, $default);
    }
}

if (!function_exists('config')) {
    function config($key, $default = '') {
        $helperClass = new \SigmaPHP\Core\Helpers\Helper();
        return $helperClass->config($key, $default);
    }
}

if (!function_exists('url')) {
    function url($route) {
        $helperClass = new \SigmaPHP\Core\Helpers\Helper();
        return $helperClass->url($route);
    }
}

if (!function_exists('encrypt')) {
    function encrypt($text, $salt = '') {
        $helperClass = new \SigmaPHP\Core\Helpers\Helper();
        return $helperClass->encrypt($text, $salt);
    }
}

if (!function_exists('decrypt')) {
    function decrypt($text, $salt = '') {
        $helperClass = new \SigmaPHP\Core\Helpers\Helper();
        return $helperClass->decrypt($text, $salt);
    }
}