<?php

class SubscriptionTest extends \Codeception\Test\Unit
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
    public function it_makes_new_subscription()
    {
        $subscription = \App\Subscription::make('123123', new \App\SubscriptionCriteria('W', '38'));

        $this->assertInstanceOf(\App\Subscription::class, $subscription);
        $this->assertEquals($subscription->id, '123123');
        $this->assertEquals($subscription->gender, 'W');
        $this->assertEquals($subscription->size, '38');
        $this->assertEquals($subscription->status, 'active');
    }

    /** @test */
    public function it_correctly_extracts_criteria_from_subscription()
    {
        $subscription = factory(\App\Subscription::class)->make();

        $criteria = $subscription->criteria();

        $this->assertInstanceOf(\App\SubscriptionCriteria::class, $criteria);
        $this->assertEquals($criteria->toArray(), ['gender' => $subscription->gender, 'size' => $subscription->size]);
    }
}