<?php
$I = new FunctionalTester($scenario);
$I->wantTo('perform request and match text in response');

$I->amOnPage('/');
$I->seeResponseCodeIs(200);
$I->see('HTTP messages are the foundation of web development.');