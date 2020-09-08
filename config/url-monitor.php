<?php

return [
    'stats' => [
        'max-redirects' => 250,
    ],
    'jobs' => [
        'chunk-url-count' => 100,
        'stat-timeout' => 10
    ],
    'store' => [
        'stat-timeout' => 2.0
    ],
    'index' => [
        'last-stats-minutes' => 10
    ],
    'old-stats-minutes' => 10
];
