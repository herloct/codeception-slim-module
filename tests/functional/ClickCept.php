<?php
$I = new FunctionalTester($scenario);
$I->wantTo('click a link and see a change in url');

$I->amOnPage('/');
$I->click('Ping Test');
$I->seeCurrentUrlEquals('/api/ping');
$I->see("ack");