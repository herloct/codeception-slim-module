<?php
namespace Herloct\Codeception;

use Herloct\Codeception\FunctionalTester;

class DeleteCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests

    public function tryDelete(FunctionalTester $I)
    {
        $data = [
            'id' => 'some id',
            'name' => 'Someone'
        ];
        $I->haveHttpHeader('content-type', 'application/x-www-form-urlencoded');
        $I->sendDELETE('/complex/some', $data);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();

        $I->canSeeResponseContainsJson([
            'parsed_body' => $data
        ]);
    }

    public function tryDeleteJson(FunctionalTester $I)
    {
        $data = [
            'id' => 'some id',
            'name' => 'Someone'
        ];
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendDELETE('/complex/some', $data);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();

        $I->canSeeResponseContainsJson([
            'parsed_body' => $data
        ]);
    }
}
