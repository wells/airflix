<?php

namespace Tests\Feature;

use Airflix\Movie;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovieDownloadTest extends TestCase
{
    use DatabaseMigrations;

    protected $movie;

    function setUp()
    {
        parent::setUp();

        $this->movie = factory(Movie::class)->create();
    }

    /** @test */
    public function it_fails_to_download_a_movie()
    {
        $response = $this->get('/downloads/movies/'.$this->movie->uuid);
        
        $response->assertStatus(404);
    }
}
