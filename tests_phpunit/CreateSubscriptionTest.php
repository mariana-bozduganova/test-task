<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ProductVariationsCollection;
use App\SubscriptionCriteria;
use App\Subscription;
use App\Subscriber;

class CreateSubscriptionTest extends TestCase
{

    use DatabaseTransactions;

    const EMAIL = 'testemail@test.com';

    /** @test */
    public function it_creates_a_new_subscriber_during_the_subscription_process_if_one_with_that_email_does_not_exist()
    {
        $this->getSubscriptionInstanceWithCriteria()->make(self::EMAIL);

        $this->seeInDatabase('subscribers', ['email' => self::EMAIL]);
    }

    /** @test */
    public function it_errors_when_an_existing_subscriber_tries_to_subscribe_with_the_same_criteria()
    {
        $subscriber = Subscriber::create(['email' => self::EMAIL]);
        $productVariation = $this->getRandomProductVariation();
        Subscription::create([
           'gender' => $productVariation->getGender(),
            'size' => $productVariation->getSize(),
            'subscriber_id' => $subscriber->id
        ]);

        $this->expectException(\Exception::class);

        $this->getSubscriptionInstanceWithCriteria($productVariation)->make(self::EMAIL);
    }

    /** @test */
    public function it_creates_subscription()
    {
        $subscriber = Subscriber::create(['email' => self::EMAIL]);
        $productVariation = $this->getRandomProductVariation();

        $this->getSubscriptionInstanceWithCriteria($productVariation)->make(self::EMAIL);

        $this->seeInDatabase('subscriptions', ['gender' => $productVariation->getGender(), 'size' => $productVariation->getSize(), 'subscriber_id' => $subscriber->id, 'status' => 'active']);
    }

    /** @test */
    public function it_creates_subscription_with_active_status_if_no_options_array_passed()
    {
        $result = $this->getSubscriptionInstanceWithCriteria()->make(self::EMAIL);

        $this->assertEquals('active', $result->status);
    }

    /** @test */
    public function it_reads_data_from_the_options_parameters()
    {
        $options = ['status' => 'sent'];

        $result = $this->getSubscriptionInstanceWithCriteria()->make(self::EMAIL, $options);

        $this->assertEquals('sent', $result->status);
    }

    /** @test */
    public function it_excludes_the_subscription_criteria_fields_from_the_options_parameters()
    {
        $productVariation = $this->getRandomProductVariation();
        $options = ['gender' => 'test_gender', 'size' => 'test_size', 'test_option' => 'test_option_value'];

        $result = $this->getSubscriptionInstanceWithCriteria($productVariation)->make(self::EMAIL, $options);

        $this->assertEquals($productVariation->getGender(), $result->gender);
        $this->assertEquals($productVariation->getSize(), $result->size);
    }

    protected function getSubscriptionInstanceWithCriteria($productVariation = null)
    {
        $productVariation = $productVariation ? : $this->getRandomProductVariation();

        return Subscription::withCriteria(new SubscriptionCriteria($productVariation->getGender(), $productVariation->getSize()));
    }

    protected function getRandomProductVariation()
    {
        return app(ProductVariationsCollection::class)->random(1);
    }

}
