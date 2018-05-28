<?php

namespace Spqr\Eventlist\Controller;

use Pagekit\Application as App;
use Spqr\Eventlist\Model\Event;


/**
 * @Access(admin=true)
 * @return string
 */
class EventlistController
{
    /**
     * @Access("eventlist: manage events")
     * @Request({"filter": "array", "page":"int"})
     * @param null $filter
     * @param int  $page
     *
     * @return array
     */
    public function eventAction($filter = null, $page = 0)
    {
        return [
            '$view' => [
                'title' => 'Events',
                'name'  => 'spqr/eventlist:views/admin/event-index.php',
            ],
            '$data' => [
                'statuses' => Event::getStatuses(),
                'config'   => [
                    'filter' => (object)$filter,
                    'page'   => $page,
                ],
            ],
        ];
    }
    
    /**
     * @Route("/event/edit", name="event/edit")
     * @Access("eventlist: manage events")
     * @Request({"id": "int"})
     * @param int $id
     *
     * @return array
     */
    public function editAction($id = 0)
    {
        try {
            $module = App::module('spqr/eventlist');
            
            if (!$event = Event::where(compact('id'))->first()) {
                if ($id) {
                    App::abort(404, __('Invalid event id.'));
                }
                $event = Event::create([
                    'status' => Event::STATUS_DRAFT,
                    'date'   => new \DateTime(),
                ]);
                
                $event->set('markdown', true);
            }
            
            return [
                '$view' => [
                    'title' => $id ? __('Edit Event') : __('Add Event'),
                    'name'  => 'spqr/eventlist:views/admin/event-edit.php',
                ],
                '$data' => [
                    'event'    => $event,
                    'statuses' => Event::getStatuses(),
                ],
            ];
        } catch (\Exception $e) {
            App::message()->error($e->getMessage());
            
            return App::redirect('@eventlist/eventlist');
        }
    }
    
    /**
     * @Access("eventlist: manage settings")
     */
    public function settingsAction()
    {
        $module = App::module('spqr/eventlist');
        $config = $module->config;
        
        return [
            '$view' => [
                'title' => __('Eventlist Settings'),
                'name'  => 'spqr/eventlist:views/admin/settings.php',
            ],
            '$data' => [
                'config' => App::module('spqr/eventlist')->config(),
            ],
        ];
    }
    
    /**
     * @Request({"config": "array"}, csrf=true)
     * @param array $config
     *
     * @return array
     */
    public function saveAction($config = [])
    {
        App::config()->set('spqr/eventlist', $config);
        
        return ['message' => 'success'];
    }
    
}