<?php

namespace Spqr\Eventlist\Model;

use Pagekit\Application as App;
use Pagekit\User\Model\User;

/**
 * @Entity(tableClass="@eventlist_category")
 */
class Category implements \JsonSerializable
{
    use CategoryModelTrait;
    
    /* Category draft. */
    const STATUS_DRAFT = 0;
    
    /* Category published. */
    const STATUS_PUBLISHED = 2;
    
    /* Category unpublished. */
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
    public $status;
    
    /** @Column(type="string") */
    public $slug;
    
    /** @Column(type="string") */
    public $title;
    
    /** @Column(type="datetime") */
    public $date;
    
    /** @Column(type="datetime") */
    public $modified;
    
    /**
     * @param $category
     *
     * @return mixed
     */
    public static function getPrevious($category)
    {
        return self::where(['date > ?', 'date < ?', 'status = 1'], [
            $category->date,
            new \DateTime,
        ])->orderBy('date', 'ASC')->first();
    }
    
    /**
     * @param $category
     *
     * @return mixed
     */
    public static function getNext($category)
    {
        return self::where(['date < ?', 'status = 1'], [$category->date])
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
            self::STATUS_PUBLISHED   => __('Published'),
            self::STATUS_UNPUBLISHED => __('Unpublished'),
            self::STATUS_DRAFT       => __('Draft'),
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
        return $this->toArray();
    }
}