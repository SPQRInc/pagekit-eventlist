<?php

use Pagekit\Application;

return [
    'name' => 'spqr/eventlist',
    'type' => 'extension',
    'main' => function (Application $app) {
    },
    
    'autoload' => [
        'Spqr\\Eventlist\\' => 'src',
    ],
    
    'nodes'  => [
        'eventlist' => [
            'name'       => '@eventlist',
            'label'      => 'Eventlist',
            'controller' => 'Spqr\\Eventlist\\Controller\\SiteController',
            'protected'  => true,
            'frontpage'  => false,
        ],
    ],
    'routes' => [
        '/eventlist'     => [
            'name'       => '@eventlist',
            'controller' => [
                'Spqr\\Eventlist\\Controller\\EventlistController',
            ],
        ],
        '/api/eventlist' => [
            'name'       => '@eventlist/api',
            'controller' => [
                'Spqr\\Eventlist\\Controller\\EventApiController',
            ],
        ],
    ],
    
    'widgets' => [],
    
    'menu' => [
        'eventlist'           => [
            'label'  => 'Eventlist',
            'url'    => '@eventlist/event',
            'active' => '@eventlist/event*',
            'icon'   => 'spqr/eventlist:icon.svg',
        ],
        'eventlist: events'   => [
            'parent' => 'eventlist',
            'label'  => 'Targets',
            'icon'   => 'spqr/eventlist:icon.svg',
            'url'    => '@eventlist/event',
            'access' => 'eventlist: manage events',
            'active' => '@eventlist/target*',
        ],
        'eventlist: settings' => [
            'parent' => 'eventlist',
            'label'  => 'Settings',
            'url'    => '@eventlist/settings',
            'access' => 'eventlist: manage settings',
        ],
    ],
    
    'permissions' => [
        'eventlist: manage settings' => [
            'title' => 'Manage settings',
        ],
        'eventlist: manage events'   => [
            'title' => 'Manage targets',
        ],
    ],
    
    'settings' => '@eventlist/settings',
    
    'resources' => [
        'spqr/eventlist:' => '',
    ],
    
    'config' => [
        'items_per_page' => 20,
    ],
    
    'events' => [
        'boot'         => function ($event, $app) {
        },
        'site'         => function ($event, $app) {
        },
        'view.scripts' => function ($event, $scripts) use ($app) {
        },
    ],
];