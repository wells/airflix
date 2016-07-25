<?php

namespace Airflix\Contracts;

interface GenreTransformer
{
    public function transform($genre);
    public function includeMovies($genre);
    public function includeShows($genre);
}
