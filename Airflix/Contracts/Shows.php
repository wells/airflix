<?php

namespace Airflix\Contracts;

interface Shows
{
    public function index($relationships, $pagination, $query);
    public function get($id, $relationships, $query);
    public function patch($id, $input);
    public function updateTotalViews($show);
    public function refreshShow($show, $tmdbShowId, $useDefaults);
    public function refreshShows($onlyNewFolders);
    public function truncate();
}
