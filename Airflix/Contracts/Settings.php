<?php

namespace Airflix\Contracts;

interface Settings
{
	public function get();
	public function getMoviesFolderPath();
	public function getShowsFolderPath();
	public function setMoviesFolderPath($folderPath);
	public function setShowsFolderPath($folderPath);
}
