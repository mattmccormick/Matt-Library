<?php

namespace Matt;

use Matt\System\File;
use Matt\System\Dir;

abstract class System
{
	abstract public function __toString();

	/**
	 * @return Dir
	 */
	abstract public function getParent();

	abstract public function getBasename();

	abstract public function copy($destination);

	abstract public function delete();

	public function is_dir()
	{
		return $this instanceof Dir;
	}

	public function is_file()
	{
		return $this instanceof File;
	}

	public function getOwner()
	{
		$owner = posix_getpwuid(fileowner($this));
		return $owner['name'];
	}

	/**
	 * @param string $filepath
	 * @throws \Exception
	 * @return System
	 */
	public static function factory($filepath)
	{
		if (is_dir($filepath)) {
			return new Dir($filepath);
		} else if (is_file($filepath)) {
			return File::factory($filepath);
		} else {
			throw new \Exception("No System implementation for {$filepath}");
		}
	}
}