<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
            ->see('login');
    }

    /**
     * User Admin Functionalities Test
     * @return void
     */
    public function testAdminFunctionalities()
    {
        $this->visit('/account/login')
            ->type(config('custom.adminLogin'), 'email')
            ->type(config('custom.adminPassword'), 'password')
            ->press('Login')
            ->seePageIs('/')
            ->click('Projects')
            ->seePageIs('/project')
            ->click('Navigators')
            ->seePageIs('/navigator')
            ->click('User Management')
            ->seePageIs('/register')
            ->click('Metrics')
            ->seePageIs('/metrics')
            ->click('Designation')
            ->seePageIs('/designation')
            ->click('Reports')
            ->seePageIs('/getadmindashboard');
    }


}
