<?php

return [
    'stats' => [
        'max-redirects' => 250,
    ],
    'jobs' => [
        'chunk-url-count' => 15,
        'stat-timeout' => 30
    ],
    'store' => [
        'stats-header-name' => 'X-Stats',
        'stat-timeout' => 2.0
    ],
    'index' => [
        'last-stats-minutes' => 10
    ],
    'old-stats-minutes' => 10
];
