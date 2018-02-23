<?php
namespace App\Repositories;

use App\Subscription;
use App\SubscriptionCriteria;

class SubscriptionsRepository extends BaseRepository
{
    public function findWithmatchingCriteria(SubscriptionCriteria $criteria)
    {
        return Subscription::matchingCriteria($criteria)->get();
    }

    public function save(Subscription $subscription)
    {
        $subscription->save();
    }
}