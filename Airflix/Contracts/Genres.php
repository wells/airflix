<?php

namespace Airflix\Contracts;

interface Genres
{
    public function index($relationships);
    public function getIds($ids);
    public function refreshGenres();
    public function refreshTotalMovies();
    public function refreshTotalShows();
}
