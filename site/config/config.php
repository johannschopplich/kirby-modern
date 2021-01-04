<?php

$base = dirname(__DIR__, 2);
\KirbyExtended\EnvAdapter::load($base);

return [

    'debug' => env('KIRBY_DEBUG', false),

    'panel' => [
        'install' => env('KIRBY_PANEL_INSTALL', false)
    ],

    'routes' => require __DIR__ . '/routes.php',

    'cache' => [
        'pages' => [
            'active' => env('KIRBY_CACHE', false),
            'ignore' => function ($page) {
                if (kirby()->user() !== null) return true;
                $options = $page->blueprint()->options();
                return isset($options['cache']) ? !$options['cache'] : false;
            }
        ]
    ],

    'kirby-extended.sitemap' => [
        'enable' => true,
        'templatesInclude' => [
            'about',
            'album',
            'default',
            'home',
            'note',
            'notes',
            'photography'
        ]
    ]

];
