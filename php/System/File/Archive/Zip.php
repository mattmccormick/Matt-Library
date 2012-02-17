<?php

namespace Matt\System\File\Archive;

use Matt\System\Dir;
use Matt\System\File\Archive;

class Zip extends Archive
{
	public function extract(Dir $directory = null)
	{
		$destination = $this->getDestination($directory);

		$zip = new \ZipArchive();
		$zip->open($this);
		$result = $zip->extractTo($destination);
		$zip->close();

		return $destination;
	}
}