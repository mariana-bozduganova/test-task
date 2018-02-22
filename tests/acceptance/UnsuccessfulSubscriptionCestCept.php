<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('subscribe');
$I->amOnPage('/');
$I->submitForm('#subscription-form', [
    'gender' => 'W',
    'size' => '38',
    'email' => 'testing@gmail.com'
]);
$I->submitForm('#subscription-form', [
    'gender' => 'W',
    'size' => '38',
    'email' => 'testing@gmail.com'
]);
$I->seeCurrentURLEquals('/');
$I->see('A user with this email already subscribed for the given criteria.');
