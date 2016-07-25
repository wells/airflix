<?php

namespace Airflix\Contracts;

interface Episodes
{
    public function get($id, $relationships, $query);
    public function updateTotalViews($episode);
    public function refreshEpisode($result, $show, $season);
    public function truncate();
}
