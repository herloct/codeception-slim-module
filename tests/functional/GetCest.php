<?php
namespace Herloct\Codeception;

use Herloct\Codeception\FunctionalTester;

class GetCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests

    public function trySimpleGet(FunctionalTester $I)
    {
        $I->sendGET('/simple-get');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeHttpHeader('content-type', 'text/html; charset=UTF-8');

        $I->canSeeResponseEquals('Hello there');
    }

    public function tryWithHeaders(FunctionalTester $I)
    {
        $I->amBearerAuthenticated('some_access_token');
        $I->haveHttpHeader('x-something', 'some_value');
        $I->sendGET('/complex-get');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeHttpHeader('content-type', 'application/json');

        $I->canSeeResponseContainsJson([
            'query' => [],
            'headers' => [
                'HTTP_AUTHORIZATION' => ['Bearer some_access_token'],
                'HTTP_X_SOMETHING' => ['some_value']
            ]
        ]);
    }

    public function tryWithQueryParams(FunctionalTester $I)
    {
        $queryParams = [
            'some-key' => 'some value',
            'another' => 'another value'
        ];
        $I->sendGET('/complex-get', $queryParams);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeHttpHeader('content-type', 'application/json');

        $I->canSeeResponseContainsJson([
            'query' => $queryParams
        ]);
    }

    public function tryWithUriParams(FunctionalTester $I)
    {
        $I->sendGET('/complex-get/some-id/hello');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeHttpHeader('content-type', 'application/json');

        $I->canSeeResponseContainsJson([
            'attributes' => [
                'id' => 'some-id',
                'name' => 'hello'
            ]
        ]);
    }

    public function tryNotFound(FunctionalTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->sendGET('/somewhere');

        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseIsJson();
    }
}
