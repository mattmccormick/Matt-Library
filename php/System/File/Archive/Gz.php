<?php

namespace Matt\System\File\Archive;

use Matt\System\Dir;
use Matt\System\File\Archive;

class Gz extends Archive
{
	public function extract(Dir $directory = null)
	{

	}

	/**
	 * @param Dir $dir
	 * @return Gz
	 */
	public static function compressDir(Dir $dir)
	{
		$file = $dir->getWithoutTrailingSlash() . '.tar.gz';
		$cmd = "tar -C {$dir->getParent()} -zcf {$file} {$dir->getBasename()}";
		exec($cmd);

		return new Gz($file);
	}
}