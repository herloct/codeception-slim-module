<?php
namespace Herloct\Codeception;

use Herloct\Codeception\FunctionalTester;

class PutCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests

    public function tryPut(FunctionalTester $I)
    {
        $data = [
            'id' => 'some id',
            'name' => 'Someone'
        ];
        $I->haveHttpHeader('content-type', 'application/x-www-form-urlencoded');
        $I->sendPUT('/complex/some', $data);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();

        $I->canSeeResponseContainsJson([
            'parsed_body' => $data
        ]);
    }

    public function tryPutJson(FunctionalTester $I)
    {
        $data = [
            'id' => 'some id',
            'name' => 'Someone'
        ];
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/complex/some', $data);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();

        $I->canSeeResponseContainsJson([
            'parsed_body' => $data
        ]);
    }
}
