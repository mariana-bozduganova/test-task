<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['gender', 'size', 'status', 'subscriber_id'];

    /**
     * The subscription criteria with which the subscription is instantiated
     *
     * @var
     */
    protected static $subscriptionCriteria;

    /**
     * Instantiates a new subscription instance with criteria information filled in
     *
     * @param SubscriptionCriteria $criteria
     * @return static
     */
    public static function withCriteria(SubscriptionCriteria $criteria)
    {
        self::$subscriptionCriteria = $criteria;

        return new static($criteria->toArray());
    }

    /**
     * Creates a new subscription
     *
     * @param $email
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function make($email, array $options = [])
    {
        $subscriber = Subscriber::register($email);

        if ($subscriber->alreadySubscribedWith(self::$subscriptionCriteria)) {
            throw new \Exception('A user with this email already subscribed for the given criteria.');
        }

        $this->setDefaultStatus();

        $this->fill($this->filterOptions($options));

        $this->owner()->associate($subscriber);

        $this->save();

        return $this;
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
     * Exclude the subscription criteria parameters from the options array
     *
     * @param $options
     * @return array
     */
    protected function filterOptions($options)
    {
        return array_diff_key($options, self::$subscriptionCriteria->toArray());
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
