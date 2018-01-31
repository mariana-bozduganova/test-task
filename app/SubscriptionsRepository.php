<?php

namespace App;


class SubscriptionsRepository {

    public function subscriptionsMatchingCriteria(SubscriptionCriteria $criteria)
    {
        return Subscription::matchingCriteria($criteria)->get();
    }

} 