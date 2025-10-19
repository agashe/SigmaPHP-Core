<?php

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
];
