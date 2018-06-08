<?php

namespace Spqr\Eventlist\Controller;

use Pagekit\Application as App;
use Spqr\Eventlist\Model\Event;

/**
 * @Access("eventlist: manage events")
 * @Route("event", name="event")
 */
class EventApiController
{
    /**
     * @param array $filter
     * @param int   $page
     * @param int   $limit
     * @Route("/", methods="GET")
     * @Request({"filter": "array", "page":"int", "limit":"int"})
     *
     * @return mixed
     */
    public function indexAction($filter = [], $page = 0, $limit = 0)
    {
        $query  = Event::query();
        $filter = array_merge(array_fill_keys([
            'status',
            'search',
            'limit',
            'order',
        ], ''), $filter);
        extract($filter, EXTR_SKIP);
        if (is_numeric($status)) {
            $query->where(['status' => (int)$status]);
        }
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere([
                    'title LIKE :search',
                ], ['search' => "%{$search}%"]);
            });
        }
        if (preg_match('/^(title)\s(asc|desc)$/i', $order, $match)) {
            $order = $match;
        } else {
            $order = [1 => 'title', 2 => 'asc'];
        }
        $default = App::module('spqr/eventlist')->config('items_per_page');
        $limit   = min(max(0, $limit), $default) ? : $default;
        $count   = $query->count();
        $pages   = ceil($count / $limit);
        $page    = max(0, min($pages - 1, $page));
        $events  = array_values($query->offset($page * $limit)->limit($limit)
            ->orderBy($order[1], $order[2])->get());
        
        return compact('events', 'pages', 'count');
    }
    
    /**
     * @Route("/{id}", methods="GET", requirements={"id"="\d+"})
     * @param $id
     *
     * @return static
     */
    public function getAction($id)
    {
        if (!$event = Event::where(compact('id'))->first()) {
            App::abort(404, 'Event not found.');
        }
        
        return $event;
    }
    
    /**
     * @Route(methods="POST")
     * @Request({"ids": "int[]"}, csrf=true)
     * @param array $ids
     *
     * @return array
     */
    public function copyAction($ids = [])
    {
        foreach ($ids as $id) {
            if ($event = Event::find((int)$id)) {
                if (!App::user()->hasAccess('eventlist: manage events')) {
                    continue;
                }
                $event         = clone $event;
                $event->id     = null;
                $event->status = $event::STATUS_UNPUBLISHED;
                $event->title  = $event->title.' - '.__('Copy');
                $event->date   = new \DateTime();
                $event->save();
            }
        }
        
        return ['message' => 'success'];
    }
    
    /**
     * @Route("/bulk", methods="POST")
     * @Request({"events": "array"}, csrf=true)
     * @param array $events
     *
     * @return array
     */
    public function bulkSaveAction($events = [])
    {
        foreach ($events as $data) {
            $this->saveAction($data, isset($data['id']) ? $data['id'] : 0);
        }
        
        return ['message' => 'success'];
    }
    
    /**
     * @Route("/", methods="POST")
     * @Route("/{id}", methods="POST", requirements={"id"="\d+"})
     * @Request({"event": "array", "id": "int"}, csrf=true)
     */
    public function saveAction($data, $id = 0)
    {
        if (!$id || !$event = Event::find($id)) {
            if ($id) {
                App::abort(404, __('Event not found.'));
            }
            $event = Event::create();
        }
        if (!$data['slug'] = App::filter($data['slug'] ? : $data['title'],
            'slugify')
        ) {
            App::abort(400, __('Invalid slug.'));
        }
        
        $event->save($data);
        
        return ['message' => 'success', 'event' => $event];
    }
    
    /**
     * @Route("/bulk", methods="DELETE")
     * @Request({"ids": "array"}, csrf=true)
     * @param array $ids
     *
     * @return array
     */
    public function bulkDeleteAction($ids = [])
    {
        foreach (array_filter($ids) as $id) {
            $this->deleteAction($id);
        }
        
        return ['message' => 'success'];
    }
    
    /**
     * @Route("/{id}", methods="DELETE", requirements={"id"="\d+"})
     * @Request({"id": "int"}, csrf=true)
     * @param $id
     *
     * @return array
     */
    public function deleteAction($id)
    {
        if ($event = Event::find($id)) {
            if (!App::user()->hasAccess('eventlist: manage events')) {
                App::abort(400, __('Access denied.'));
            }
            $event->delete();
        }
        
        return ['message' => 'success'];
    }
    
}