<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['email'];

    /**
     * Register a new subscriber
     *
     * @param $email
     * @return static
     */
    public static function register($email)
    {
        if ( ! $subscriber = self::exists($email)) {
            return self::create(['email' => $email]);
        }

        return $subscriber;
    }

    /**
     * Checks if subscriber already owns a subscription with the given subscription criteria
     *
     * @param SubscriptionCriteria $criteria
     * @return bool
     */
    public function alreadySubscribedWith(SubscriptionCriteria $criteria)
    {
        return $this->subscriptions()->matchingCriteria($criteria)->count() > 0;
    }

    /**
     * Checks if subscriber with the given email exists
     *
     * @param $email
     * @return mixed
     */
    protected static function exists($email)
    {
        return self::withEmail($email)->first();
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
