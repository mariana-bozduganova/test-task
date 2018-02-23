<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $incrementing = false;

    /**
     * Creates a new subscription
     *
     * @param $email
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public static function make(string $id, SubscriptionCriteria $criteria)
    {
        $subscription = new self();

        $subscription->id = $id;

        $subscription->fill($criteria->toArray());

        $subscription->setDefaultStatus();

        return $subscription;
    }

    public function criteria()
    {
        return new SubscriptionCriteria($this->gender, $this->size);
    }

    /**
     * Marks subscription as sent
     */
    public function markSent()
    {
        $this->status = 'sent';
        $this->save();
    }

    /**
     * Sets the default status to "active"
     */
    protected function setDefaultStatus()
    {
        $this->status = 'active';
    }

    /**
     * Finds subscriptions matching given subscription criteria
     *
     * @param $query
     * @param SubscriptionCriteria $criteria
     * @return mixed
     */
    public function scopeMatchingCriteria($query, SubscriptionCriteria $criteria)
    {
        extract($criteria->toArray());

        return $query->where('gender', $gender)->where('size', $size);
    }

    /**
     * Subscription owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }
}
