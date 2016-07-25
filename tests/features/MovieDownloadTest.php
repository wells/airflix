<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Airflix\Movie;

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
        $this->get('/downloads/movies/'.$this->movie->uuid)
            ->assertResponseStatus(404);
    }
}
