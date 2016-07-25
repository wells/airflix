<?php

namespace Airflix\Contracts;

interface EpisodeViews
{
    public function link($show, $tmdbShowId);
    public function unlink();
    public function watch($episode, $timeLimit);
    public function clearHistory($clearToday);
}
