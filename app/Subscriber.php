<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $incrementing = false;

    /**
     * Register a new subscriber
     *
     * @param $email
     * @return static
     */
    public static function register($id, $email)
    {
        return new self([
            'id' => $id,
            'email' => $email
        ]);
    }

    public function addSubscription(Subscription $subscription)
    {
        if ($this->alreadySubscribedWith($subscription->criteria())) {
            throw new \Exception('A user with this email already subscribed for the given criteria.');
        }

        $subscription->subscriber_id = $this->id;
    }


    /**
     * Checks if subscriber already owns a subscription with the given subscription criteria
     *
     * @param SubscriptionCriteria $criteria
     * @return bool
     */
    protected function alreadySubscribedWith(SubscriptionCriteria $criteria)
    {
        return $this->subscriptions()->matchingCriteria($criteria)->count() > 0;
    }


    /**
     * Finds subscribers with the given email
     *
     * @param $query
     * @param $email
     * @return mixed
     */
    public function scopeWithEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Subscriptions owned by the current subscriber
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
