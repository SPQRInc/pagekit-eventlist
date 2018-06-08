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