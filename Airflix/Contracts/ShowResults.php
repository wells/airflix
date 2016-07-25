<?php

namespace Airflix\Contracts;

interface ShowResults
{
    public function get($show, $currentPage, $url);
}
