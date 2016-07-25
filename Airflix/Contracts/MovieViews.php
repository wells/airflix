<?php

namespace Airflix\Contracts;

interface MovieViews
{
    public function link($movie, $tmdbMovieId);
    public function unlink();
    public function watch($movie, $timeLimit);
    public function clearHistory($clearToday);
}
