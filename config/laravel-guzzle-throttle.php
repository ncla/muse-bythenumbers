<?php

// @codeCoverageIgnoreStart

// example configuration
return [
    'cache' => [
        // Name of the configured driver in the Laravel cache config file / Also needs to be set when "no-cache" is set! Because it's used for the internal timers
        'driver'   => 'file',
        // Cache strategy: no-cache, cache, force-cache
        'strategy' => 'no-cache',
        // TTL in minutes
        'ttl'      => 60
    ],
    'rules' => [
        // host (including scheme)
        'https://api.discogs.com' => [
            [
                // maximum number of requests in the given interval
                'max_requests'     => 20,
                // interval in seconds till the limit is reset
                'request_interval' => 60
            ]
        ]
    ]
];

// @codeCoverageIgnoreEnd