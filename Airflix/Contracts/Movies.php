<?php

namespace Airflix\Contracts;

interface Movies
{
    public function index($relationships, $pagination, $query);
    public function get($id, $relationships, $query);
    public function patch($id, $input);
    public function updateTotalViews($movie);
    public function refreshMovie($movie, $tmdbMovieId, $useDefaults);
    public function refreshMovies($onlyNewFolders);
    public function truncate();
}
