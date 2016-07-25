<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Airflix\Genre;

class GenresApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $genre;

    function setUp()
    {
        parent::setUp();

        $this->genre = factory(Genre::class)->create();
    }

    /** @test */
    public function it_fetches_genres()
    {
        $this->json('GET', '/api/genres')
            ->seeJson([
                'name' => $this->genre->name,
            ])
            ->seeJsonStructure([
                'data',
                'meta',
            ]);
    }
}
