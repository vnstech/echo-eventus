<?php

namespace Tests\Acceptance\Home;

use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;
use App\Models\User;

class HomeIndexCest extends BaseAcceptanceCest
{
    public function seeHomePage(AcceptanceTester $page): void
    {
        $page->amOnPage('/');
        $page->see('Home Page', '//h1');
    }

    public function notSeeHomePage(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $page->login($user->email, $user->password);

        $page->amOnPage('/');

        $page->seeCurrentUrlEquals('/events');
    }
}
