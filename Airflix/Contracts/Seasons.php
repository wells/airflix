<?php

namespace Airflix\Contracts;

interface Seasons
{
    public function get($id, $relationships, $query);
    public function updateTotalViews($season);
    public function refreshSeason($seasonNumber, $show);
    public function truncate();
}
