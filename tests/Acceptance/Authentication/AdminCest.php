<?php

namespace Tests\Acceptance\Authentication;

use App\Models\User;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class AdminCest extends BaseAcceptanceCest
{
    public function adminSuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $page->amOnPage('/login');

        $page->fillField('user[email]', $user->email);
        $page->fillField('user[password]', $user->password);

        $page->click('Entrar');

        $page->see('Login realizado com sucesso!');
        $page->seeInCurrentUrl('/events');

        $page->amOnPage('/admin');
    }

    public function adminUnsuccessfully(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano1@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $page->amOnPage('/login');

        $page->fillField('user[email]', $user->email);
        $page->fillField('user[password]', $user->password);

        $page->click('Entrar');

        $page->seeInCurrentUrl('/events');
        $page->see('Login realizado com sucesso!');

        $page->amOnPage('/admin');

        $page->seeInCurrentUrl('/login');
        $page->see('You do not have permission to access this page.');
    }
}
