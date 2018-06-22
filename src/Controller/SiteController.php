<?php

namespace Spqr\Eventlist\Controller;

use Pagekit\Application as App;
use Spqr\Eventlist\Model\Event;

/**
 * Class SiteController
 *
 * @package Spqr\Eventlist\Controller
 */
class SiteController
{
    /**
     * @Route("/")
     * @Access("eventlist: view events")
     */
    public function indexAction($value = '')
    {
        $query = Event::where(['status = ?'], [
            Event::STATUS_PUBLISHED,
        ]); //TODO: Datum mit einfließen lassen
        
        foreach ($events = $query->get() as $event) {
            $event->content = App::content()->applyPlugins($event->content, [
                'event'    => $event,
                'markdown' => $event->get('markdown'),
            ]);
        }
        
        return [
            '$view'  => [
                'title' => App::node()->title ? : __('Eventlist'),
                'name'  => 'spqr/eventlist:views/event-index.php',
            ],
            '$data'  => [
                'config' => App::module('spqr/eventlist')->config(),
                'events' => array_values($events),
            ],
            'config' => App::module('spqr/eventlist')->config(),
        ];
    }
    
    /**
     * @Route("/details/{id}", name="events-details")
     * @Access("events: view events")
     */
    public function detailsAction($id = '')
    {
        if (!$event
            = Event::where(['id = ?', 'status = ?', 'date < ?'],
            [$id, Event::STATUS_PUBLISHED, new \DateTime])->first()
        ) {
            App::abort(404, __('Event not found!'));
        }
        
        if ($breadcrumbs = App::module('bixie/breadcrumbs')) {
            $breadcrumbs->addUrl([
                'title' => $event->title,
                'url'   => '',
            ]);
        }
        
        $event->content
            = App::content()->applyPlugins($event->content, [
            'event'    => $event,
            'markdown' => $event->get('markdown'),
        ]);
        
        $description = strip_tags($event->content);
        $description = rtrim(mb_substr($description, 0, 150), " \t\n\r\0\x0B.,")
            .'...';
        
        return [
            '$view' => [
                'title'          => $event->title ? : __('Eventlist'),
                'name'           => 'spqr/eventlist:views/event-details.php',
                'og:title'       => $event->title,
                'og:description' => $description,
            ],
            '$data' => [
                'config' => App::module('spqr/eventlist')->config(),
                'event'  => $event,
            ],
            'event' => $event,
        ];
    }
    
}