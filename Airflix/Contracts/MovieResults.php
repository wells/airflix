<?php

namespace Airflix\Contracts;

interface MovieResults
{
    public function get($movie, $currentPage, $url);
}
