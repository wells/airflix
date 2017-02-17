<?php

namespace Tests\Feature;

use Airflix\Season;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SeasonsApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $season;

    function setUp()
    {
        parent::setUp();

        $this->season = factory(Season::class)->create();
    }

    /** @test */
    public function it_fetches_a_single_season()
    {
        $response = $this->json('GET', '/api/seasons/'.$this->season->uuid);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $this->season->name,
        ]);
        $response->assertJsonStructure([
            'data' => [
                'relationships' => [
                    'episodes',
                    'show',
                    'views',
                ],
            ], 
            'included', 
            'meta',
        ]);
    }

    /** @test */
    public function it_404s_if_a_season_is_not_found()
    {
        $response = $this->json('GET', '/api/seasons/x');

        $response->assertStatus(404);
    }
}
