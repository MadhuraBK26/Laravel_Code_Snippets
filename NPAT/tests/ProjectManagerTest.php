<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectManagerTest extends TestCase
{
    /**
     *Project Manager Functionalities Test
     * @return void
     */
    public function testProjectManagerFunctionalities()
    {
        $this->visit('/account/login')
            ->type(config('custom.pmLogin'), 'email')
            ->type(config('custom.pmPassword'), 'password')
            ->press('Login')
            ->seePageIs('/')
            ->click('Feedback Form')
            ->seePageIs('/')
            ->click('Navigators')
            ->seePageIs('/report1/projectManagerNavigators')
            ->click('Reports')
            ->seePageIs('/report1');
    }
}
