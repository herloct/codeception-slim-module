<?php
$I = new FunctionalTester($scenario);

$I->wantTo('test that the request and response objects are being created from the container');
$I->haveHttpHeader('content-type', 'application/json');
$I->sendPOST('/rest');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseContainsJson([
  'responseClass' => 'NewResponse',
  'requestClass' => 'NewRequest',
]);

