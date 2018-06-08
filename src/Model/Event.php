<?php

namespace Spqr\Eventlist\Model;

use Pagekit\Application as App;
use Pagekit\System\Model\DataModelTrait;

/**
 * @Entity(tableClass="@eventlist_event")
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
    
    /** @Column(type="integer") @Id */
    public $id;
    
    /** @Column(type="integer") */
    public $status;
    
    /** @Column(type="string") */
    public $slug;
    
    /** @Column(type="string") */
    public $title;
    
    /** @Column(type="json_array") */
    public $performer;
    
    /** @Column(type="float") */
    public $price = 0.00;
    
    /** @Column(type="string") */
    public $currency;
    
    /** @Column(type="datetime") */
    public $date;
    
    /** @Column(type="datetime") */
    public $modified;
    
    /** @var array */
    protected static $properties
        = [
            'published' => 'isPublished',
        ];
    
    /**
     * @ManyToMany(targetEntity="Category", tableThrough="@classifieds_classified_category",
     *                                      keyThroughFrom="classified_id",
     *                                      keyThroughTo="category_id")
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
            self::STATUS_PUBLISHED => __('Published'),
            self::STATUS_PENDING_REVIEW => __('Pending Review'),
            self::STATUS_UNPUBLISHED => __('Unpublished'),
            self::STATUS_DRAFT => __('Draft'),
        ];
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
        return $this->toArray();
    }
}