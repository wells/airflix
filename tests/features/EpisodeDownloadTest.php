<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Airflix\Episode;

class EpisodeDownloadTest extends TestCase
{
    use DatabaseMigrations;

    protected $episode;

    function setUp()
    {
        parent::setUp();

        $this->episode = factory(Episode::class)->create();
    }

    /** @test */
    public function it_fails_to_download_a_episode()
    {
        $this->get('/downloads/episodes/'.$this->episode->uuid)
            ->assertResponseStatus(404);
    }
}
