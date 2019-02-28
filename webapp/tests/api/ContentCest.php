<?php 

class ContentCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function multiContentResourceTest(ApiTester $I)
    {
        $I->sendGET('/content');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id'            => 'string',
            'title'         => 'string',
            'description'   => 'string|null',
            'users'         => 'string',
            'state_name'    => 'string',
        ]);
    }
}
