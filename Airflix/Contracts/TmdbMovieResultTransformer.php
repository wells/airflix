<?php

namespace Airflix\Contracts;

interface TmdbMovieResultTransformer
{
    public function transform($result);
}
