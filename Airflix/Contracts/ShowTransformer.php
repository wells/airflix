<?php

namespace Airflix\Contracts;

interface ShowTransformer
{
	public function transform($show);
	public function includeBackdrops($show);
	public function includeEpisodes($show);
	public function includeGenres($show);
	public function includePosters($show);
	public function includeResults($show);
	public function includeSeasons($show);
	public function includeViews($show);
}
