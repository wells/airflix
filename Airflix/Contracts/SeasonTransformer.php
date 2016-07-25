<?php

namespace Airflix\Contracts;

interface SeasonTransformer
{
	public function transform($season);
	public function includeEpisodes($season);
	public function includeShow($season);
	public function includeViews($season);
}
