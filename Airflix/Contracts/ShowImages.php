<?php

namespace Airflix\Contracts;

interface ShowImages
{
    public function getBackdrops($show);
    public function getPosters($show);
}
