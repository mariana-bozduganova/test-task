<?php
namespace App\Services;

use App\Subscriber;
use App\Subscription;
use App\SubscriptionCriteria;
use App\Repositories\SubscriptionsRepository;
use App\Repositories\SubscribersRepository;

class SubscriptionsService
{
    protected $subscribersRepository;
    protected $subscriptionsRepository;

    public function __construct(SubscribersRepository $subscribersRepository, SubscriptionsRepository $subscriptionsRepository)
    {
        $this->subscribersRepository = $subscribersRepository;
        $this->subscriptionsRepository = $subscriptionsRepository;
    }

    public function addSubscription(array $data)
    {
        // register subscriber if one doesn't exist
        if ( ! $subscriber = $this->subscribersRepository->findByEmail($data['email'])) {
            $subscriber = Subscriber::register($this->subscribersRepository->nextID(), $data['email']);
            $this->subscribersRepository->save($subscriber);
        }

        // make a new subscription
        $subscriptionCriteria = new SubscriptionCriteria($data['gender'], $data['size']);
        $subscription = Subscription::make($this->subscriptionsRepository->nextID(), $subscriptionCriteria);

        // add the new subscription to subscriber's subscriptions list
        $subscriber->addSubscription($subscription);

        $this->subscriptionsRepository->save($subscription);
    }
}