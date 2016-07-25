<?php

namespace Airflix\Contracts;

interface MovieImages
{
    public function getBackdrops($movie);
    public function getPosters($movie);
}
