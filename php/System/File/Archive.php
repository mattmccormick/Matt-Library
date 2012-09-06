<?php

namespace Matt\System\File;

use \Matt\System\File;
use \Matt\System\Dir;

abstract class Archive extends File
{
	/**
	 * Extract the file to the $directory
	 * @param string $directory
	 * @return Dir
	 * @throws Exception
	 */
	abstract public function extract(Dir $directory = null);

	/**
	 * @param Dir $directory
	 * @return Dir
	 */
	protected function getDestination(Dir $directory = null)
	{
		return $directory ? $directory : new Dir($this->getParent() . $this->getFilename(), true);
	}
}