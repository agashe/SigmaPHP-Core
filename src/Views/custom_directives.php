<?php

return [
    'request' => function() {
        return container('request');
    },
    'cookie' => function() {
        return container('cookie');
    },
    'session' => function() {
        return container('session');
    },
    'file' => function() {
        return container('file');
    },
];
