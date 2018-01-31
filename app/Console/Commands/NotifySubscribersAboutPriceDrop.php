<?php

namespace App\Console\Commands;

use App\SubscriptionsRepository;
use Illuminate\Console\Command;
use App\ProductVariationsCollection;
use App\SubscriptionCriteria;
use App\Mail\PriceDropped;

class NotifySubscribersAboutPriceDrop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:price-drop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify the subscription owners when the price of a product variation matching their subscription criteria drops';


    protected $productVariations;

    protected $repository;

    protected $subscriptionCriteria;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SubscriptionsRepository $repository, ProductVariationsCollection $productVariations)
    {
        parent::__construct();

        $this->repository = $repository;

        $this->productVariations = $productVariations;

        $this->subscriptionCriteria = new SubscriptionCriteria(env('GENDER'), env('SIZE'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ( ! $this->priceDropped())
        {
            return;
        }

        foreach ($this->repository->subscriptionsMatchingCriteria($this->subscriptionCriteria) as $subscription)
        {
            $this->notifySubscriptionOwner($subscription->owner->email);

            $subscription->markSent();
        }
    }

    /**
     * Checks if price dropped
     *
     * @return bool
     */
    protected function priceDropped()
    {
        return $this->subscribedForPrice() > env('PRICE');
    }

    /**
     * Finds the subscribed for price from the config variations
     *
     * @return int
     */
    protected function subscribedForPrice()
    {
        return $this->productVariations->findBySubscriptionCriteria($this->subscriptionCriteria)->getPrice();
    }

    /**
     * Sends an email to the subscription owners informing them about the price drop
     *
     * @param $ownerEmail
     */
    protected function notifySubscriptionOwner($ownerEmail)
    {
        \Mail::to($ownerEmail)->send(new PriceDropped($this->subscriptionCriteria, env('PRICE')));
    }
}
