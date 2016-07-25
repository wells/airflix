<?php

namespace Airflix\Contracts;

interface TmdbImageClient
{
	public function download($fileName, $folder);
}
