<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppPageTest extends TestCase
{
    /** @test */
    public function it_fetches_home_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Airflix');
    }
}
