<?php 

class HomeCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function titleTest(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->see('MSSE 661');
    }

    public function contentLinkTest(FunctionalTester $I) {
        $I->amOnPage('/');
        $I->see('Test Content 1');
        $I->click('Test Content 1');
        $I->see('Test Content 1', '.content-title');
        $I->see('Fred Flintstone', '.user-link');
        $I->click('MSSE 661');
    }

    public function userLinkTest(FunctionalTester $I) {
        $I->amOnPage('/');
        $I->see('Fred Flintstone');
        $I->click('Fred Flintstone');
        $I->see('Fred Flintstone', '.user-name');
        $I->see('fred.flintstone@gmail.com', '.user-email');
        $I->click('MSSE 661');
    }
}
