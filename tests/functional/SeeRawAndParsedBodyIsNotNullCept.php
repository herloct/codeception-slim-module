<?php 
$I = new FunctionalTester($scenario);

$data = ['testing' => '123'];
$I->wantTo('test that the raw and parsed request body is returned in tact');
$I->haveHttpHeader('content-type', 'application/json');
$I->sendPOST('/rest', $data);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->seeResponseContainsJson([
    'formParams' => $data,
    'rawBody' => json_encode($data)
]);
