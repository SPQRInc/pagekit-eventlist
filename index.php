<?php

use Pagekit\Application;
use Spqr\Eventlist\Event\RouteListener;

return [
    'name' => 'spqr/eventlist',
    'type' => 'extension',
    'main' => function (Application $app) {
    },
    
    'autoload' => [
        'Spqr\\Eventlist\\' => 'src',
    ],
    
    'nodes' => [
        'eventlist' => [
            'name'       => '@eventlist',
            'label'      => 'Eventlist',
            'controller' => 'Spqr\\Eventlist\\Controller\\SiteController',
            'protected'  => false,
            'frontpage'  => true,
        ],
    ],
    
    'routes' => [
        '/eventlist'     => [
            'name'       => '@eventlist',
            'controller' => [
                'Spqr\\Eventlist\\Controller\\EventlistController',
                'Spqr\\Eventlist\\Controller\\EventController',
                'Spqr\\Eventlist\\Controller\\CategoryController',
            ],
        ],
        '/api/eventlist' => [
            'name'       => '@eventlist/api',
            'controller' => [
                'Spqr\\Eventlist\\Controller\\EventApiController',
                'Spqr\\Eventlist\\Controller\\CategoryApiController',
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
            'label'  => 'Events',
            'icon'   => 'spqr/eventlist:icon.svg',
            'url'    => '@eventlist/event',
            'access' => 'eventlist: manage events',
            'active' => '@eventlist/event*',
        ],
        'eventlist: category' => [
            'parent' => 'eventlist',
            'label'  => 'Categories',
            'icon'   => 'spqr/eventlist:icon.svg',
            'url'    => '@eventlist/category',
            'access' => 'eventlist: manage categories',
            'active' => '@eventlist/category*',
        ],
        'eventlist: settings' => [
            'parent' => 'eventlist',
            'label'  => 'Settings',
            'url'    => '@eventlist/settings',
            'access' => 'eventlist: manage settings',
        ],
    ],
    
    'permissions' => [
        'eventlist: manage settings'   => [
            'title' => 'Manage settings',
        ],
        'eventlist: manage events'     => [
            'title' => 'Manage events',
        ],
        'eventlist: manage categories' => [
            'title' => 'Manage categories',
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
            $app->subscribe(new RouteListener);
        },
        'site'         => function ($event, $app) {
        },
        'view.scripts' => function ($event, $scripts) use ($app) {
            $scripts->register('link',
                'spqr/eventlist:app/bundle/admin/link.js', '~panel-link');
        },
    ],
];