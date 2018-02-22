<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Subscription;
use App\Subscriber;
use App\Mail\PriceDropped;
use App\ProductVariationsCollection;
use App\SubscriptionsRepository;

class NotifySubscribersAboutPriceDropTest extends TestCase
{

    use DatabaseTransactions;

    const EMAIL = 'testemail@test.com';

    protected $subscriptions;

    public function setUp()
    {
        parent::setUp();

        $this->subscriptions = new \Illuminate\Database\Eloquent\Collection();
    }

    /** @test */
    public function it_does_not_send_notification_to_subscription_owners_if_price_did_not_drop()
    {
        $this->createSubscriptionFromENVInfo(1);

        $this->stubRepository();

        $this->stubProductVariationsWithENVInfo();

        $this->runCommandWithMailFake();

        Mail::assertNotSent(PriceDropped::class);
    }

    /** @test */
    public function it_does_not_send_notifications_if_price_dropped_but_no_subscriptions_match_the_given_criteria()
    {
        $this->createSubscriptionFromENVInfo(1);

        $this->stubRepository();

        $this->stubProductVariationsWithENVInfo();

        $this->runCommandWithMailFake();

        Mail::assertNotSent(PriceDropped::class);
    }

    /** @test */
    public function it_sends_notification_to_subscription_owners_if_price_dropped()
    {
        $this->createSubscriptionFromENVInfo();
        $subscriber = Subscriber::create(['email' => 'testemail1@test.com']);
        $this->createSubscriptionFromENVInfo($subscriber->id);

        $this->stubRepository();

        $this->stubProductVariationsWithENVInfo(env('PRICE') + 5000);

        $this->runCommandWithMailFake();

        Mail::assertSentTo([self::EMAIL], PriceDropped::class);
        Mail::assertSentTo(['testemail1@test.com'], PriceDropped::class);
    }

    /** @test */
    public function it_marks_subscription_as_sent()
    {
        $subscriberID = Subscriber::create(['email' => 'testemail1@gmail.com'])->id;
        $this->createSubscriptionFromENVInfo($subscriberID);

        $this->subscriptions->each(function ($subscription){ $subscription->save(); });

        $this->stubProductVariationsWithENVInfo(env('PRICE') + 5000);

        $this->runCommandWithMailFake();

        $this->seeInDatabase('subscriptions', ['subscriber_id' => $subscriberID, 'gender' => env('GENDER'), 'size' => env('SIZE'), 'status' => 'sent']);
    }


    protected function createSubscriptionFromENVInfo($subscriberID = null)
    {
        $subscriberID = ! is_null($subscriberID) ? $subscriberID : Subscriber::create(['email' => self::EMAIL])->id;

        $this->subscriptions->push((new Subscription())->fill(['gender' => env('GENDER'), 'size' => env('SIZE'), 'subscriber_id' => $subscriberID]));
    }

    protected function stubRepository()
    {
        $repositoryStub = Mockery::mock(SubscriptionsRepository::class);
        $repositoryStub->shouldReceive('subscriptionsMatchingCriteria')->andReturn($this->subscriptions);

        $this->app->instance(SubscriptionsRepository::class, $repositoryStub);
    }

    protected function stubProductVariationsWithENVInfo($price = null)
    {
        $price = ! is_null($price) ? $price : env('PRICE');

        $productVariationsCollecion = ProductVariationsCollection::buildFromConfig([env('GENDER') . '-' . env('SIZE') => $price]);

        $this->app->instance(ProductVariationsCollection::class, $productVariationsCollecion);
    }

    protected function runCommandWithMailFake()
    {
        Mail::fake();

        $this->artisan('notify:price-drop');
    }

}
