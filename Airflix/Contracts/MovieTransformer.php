<?php

namespace Airflix\Contracts;

interface MovieTransformer
{
	public function transform($movie);
	public function includeBackdrops($movie);
	public function includeGenres($movie);
	public function includePosters($movie);
	public function includeResults($movie);
	public function includeViews($movie);
}
