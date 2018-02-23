<?php


class SubscriberTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /** @test */
    public function it_registers_subscriber()
    {
        $subscriber = \App\Subscriber::register('123', 'testing@test.com');

        $this->assertInstanceOf(\App\Subscriber::class, $subscriber);
        $this->assertEquals($subscriber->id, '123');
        $this->assertEquals($subscriber->email, 'testing@test.com');
    }

    /** @test */
    public function it_adds_subscription_to_subscriber()
    {
        $subscriber = factory(App\Subscriber::class)->make();
        $subscription = factory(\App\Subscription::class)->make();

        $subscriber->addSubscription($subscription);

        $this->assertEquals($subscription->subscriber_id, $subscriber->id);
    }

    /**
     * @test
     * @expectedException     Exception
     */
    public function it_doesnt_add_subscription_to_subscriber_if_subscription_with_given_criteria_already_exists()
    {
        $subscriber = factory(App\Subscriber::class)->create();
        $subscription = factory(\App\Subscription::class)->create(['subscriber_id' => $subscriber->id]);
        $duplicateSubscription = factory(\App\Subscription::class)->make(['gender' => $subscription->gender, 'size' => $subscription->size]);

        $subscriber->addSubscription($duplicateSubscription);
    }
}