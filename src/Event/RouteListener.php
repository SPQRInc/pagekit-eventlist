<?php

namespace Spqr\Eventlist\Event;

use Pagekit\Application as App;
use Spqr\Eventlist\UrlResolver;
use Pagekit\Event\EventSubscriberInterface;

class RouteListener implements EventSubscriberInterface
{
    
    /**
     * Registers permalink route alias.
     */
    public function onConfigureRoute($event, $route)
    {
        if ($route->getName() == '@eventlist/id') {
            App::routes()->alias(dirname($route->getPath()).'/{slug}',
                '@eventlist/id', ['_resolver' => 'Spqr\Eventlist\UrlResolver']);
        }
    }
    
    /**
     * Clears resolver cache.
     */
    public function clearCache()
    {
        App::cache()->delete(UrlResolver::CACHE_KEY);
    }
    
    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'route.configure'               => 'onConfigureRoute',
            'model.eventlist.event.saved'   => 'clearCache',
            'model.eventlist.event.deleted' => 'clearCache',
        ];
    }
}