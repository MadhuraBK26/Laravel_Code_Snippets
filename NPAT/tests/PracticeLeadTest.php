<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PracticeLeadTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPracticeLeadFunctionalities()
    {
        $this->visit('/account/login')
            ->type(config('custom.plLogin'), 'email')
            ->type(config('custom.plPassword'), 'password')
            ->press('Login')
            ->seePageIs('/')
            ->click('Feedback Form')
            ->seePageIs('/plfeedback-form')
            ->click('Report')
            ->seePageIs('/getpldashboard')
            ->click('User Management')
            ->seePageIs('/register');
    }
}
