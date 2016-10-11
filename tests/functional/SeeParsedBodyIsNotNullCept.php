<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('test that the parsed request body is returned in tact');
$I->haveHttpHeader('content-type', 'application/json');
$I->sendPOST('/echo-parsed-body', ['testing' => '123']);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseContains('123');
