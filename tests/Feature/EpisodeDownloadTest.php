<?php

namespace Tests\Feature;

use Tests\TestCase;
use Airflix\Episode;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        $response = $this->get('/downloads/episodes/'.$this->episode->uuid);
        
        $response->assertStatus(404);
    }
}
