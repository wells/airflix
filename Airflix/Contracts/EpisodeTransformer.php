<?php

namespace Airflix\Contracts;

interface EpisodeTransformer
{
    public function transform($episode);
    public function includeShow($episode);
    public function includeSeason($episode);
    public function includeViews($episode);
}
