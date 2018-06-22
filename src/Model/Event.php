<?php

namespace Spqr\Eventlist\Model;

use Pagekit\Application as App;
use Pagekit\System\Model\DataModelTrait;
use Pagekit\User\Model\User;

/**
 * @Entity(tableClass="@eventlist_event", eventPrefix="eventlist.event")
 */
class Event implements \JsonSerializable
{
    use EventModelTrait, DataModelTrait;
    
    /* Event draft. */
    const STATUS_DRAFT = 0;
    
    /* Event pending review. */
    const STATUS_PENDING_REVIEW = 1;
    
    /* Event published. */
    const STATUS_PUBLISHED = 2;
    
    /* Event unpublished. */
    const STATUS_UNPUBLISHED = 3;
    
    /** @var array */
    protected static $properties
        = [
            'published'  => 'isPublished',
            'accessible' => 'isAccessible',
        ];
    
    /** @Column(type="integer") @Id */
    public $id;
    
    /** @Column(type="integer") */
    public $category_id = 0;
    
    /** @Column(type="integer") */
    public $status;
    
    /** @Column(type="string") */
    public $slug;
    
    /** @Column(type="string") */
    public $title;
    
    /** @Column(type="string") */
    public $content = '';
    
    /** @Column(type="json_array") */
    public $performer;
    
    /** @Column(type="float") */
    public $price = 0.00;
    
    /** @Column(type="string") */
    // public $currency;
    
    /** @Column(type="datetime") */
    public $date;
    
    /** @Column(type="datetime") */
    public $modified;
    
    /**
     * @BelongsTo(targetEntity="Spqr\Eventlist\Model\Category", keyFrom="category_id")
     */
    public $category;
    
    
    /**
     * @param $item
     *
     * @return mixed
     */
    public static function getPrevious($item)
    {
        return self::where(['date > ?', 'date < ?', 'status = 1'], [
            $item->date,
            new \DateTime,
        ])->orderBy('date', 'ASC')->first();
    }
    
    /**
     * @param $item
     *
     * @return mixed
     */
    public static function getNext($item)
    {
        return self::where(['date < ?', 'status = 1'], [$item->date])
            ->orderBy('date', 'DESC')->first();
    }
    
    /**
     * @return array
     */
    public static function getCategories()
    {
        return Category::findAll();
    }
    
    /**
     * @return mixed
     */
    public function getStatusText()
    {
        $statuses = self::getStatuses();
        
        return isset($statuses[$this->status]) ? $statuses[$this->status]
            : __('Unknown');
    }
    
    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PUBLISHED      => __('Published'),
            self::STATUS_PENDING_REVIEW => __('Pending Review'),
            self::STATUS_UNPUBLISHED    => __('Unpublished'),
            self::STATUS_DRAFT          => __('Draft'),
        ];
    }
    
    /**
     * @param \Pagekit\User\Model\User|null $user
     *
     * @return bool
     */
    public function isAccessible(User $user = null)
    {
        return $this->isPublished();
    }
    
    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->date < new \DateTime;
    }
    
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [
            'url' => App::url('@eventlist/id', ['id' => $this->id], 'base'),
        ];
    
        return $this->toArray($data);
    }
}