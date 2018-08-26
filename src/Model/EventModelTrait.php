<?php

namespace Spqr\Eventlist\Model;

use Pagekit\Database\ORM\ModelTrait;

/**
 * Class EventModelTrait
 *
 * @package Spqr\Eventlist\Model
 */
trait EventModelTrait
{
    use ModelTrait;
    
    /**
     * @Saving
     */
    public static function saving($app_event, Event $event)
    {
        $event->modified = new \DateTime();
        $i               = 2;
        $id              = $event->id;
        while (self::where('slug = ?', [$event->slug])->where(function (
            $query
        ) use ($id) {
            if ($id) {
                $query->where('id <> ?', [$id]);
            }
        })->first()) {
            $event->slug = preg_replace('/-\d+$/', '', $event->slug).'-'.$i++;
        }
    }
    
    /**
     * @return array
     */
    public function getPerformer()
    {
        if ($this->performer) {
            return array_values(array_map(function ($performer) {
                    return $performer;
                }, $this->performer));
        }
        
        return [];
    }
    
}