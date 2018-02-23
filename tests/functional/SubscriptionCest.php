<?php


class SubscriptionCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function it_creates_subscription(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->submitForm('#subscription-form', [
            'gender' => 'W',
            'size' => '38',
            'email' => 'testing@gmail.com'
        ]);
        $I->seeRecord('subscriptions', [
            'gender' => 'W',
            'size' => '38',
            'status' => 'active'
        ]);
        $I->seeRecord('subscribers', [
            'email' => 'testing@gmail.com'
        ]);
    }
}
