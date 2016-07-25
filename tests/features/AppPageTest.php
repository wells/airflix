<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppPageTest extends TestCase
{
    /** @test */
    public function it_fetches_home_page()
    {
        $this->visit('/')
            ->see('Airflix')
            ->assertResponseOk();
    }
}
