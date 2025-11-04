<?php

/**
 * This file contains definitions for all HTTP based Template Engine Directives  
 */
return [
    'current' => function() {
        return container('request')->current();
    },
    'previous' => function() {
        return container('request')->previous();
    },
    'cookie' => function($name) {
        return container('cookie')->get($name);
    },
    'session' => function($name) {
        return container('session')->get($name);
    },
    'method' => function($method) {
        return '<input type="hidden" value="_' . strtolower($method) . '" />';
    },
];
