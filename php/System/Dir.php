<?php

namespace Matt\System;

use Matt\System\File\Text;

use Matt\System;
use Matt\System\File\Archive\Gz;

class Dir extends System
{
	private $dir;

	public function __construct($directory, $create = false)
	{
		if ($create && !is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		if (!is_dir($directory)) {
			throw new \Exception("{$directory} is not a directory");
		}

		$this->dir = $this->addTrailingSlash($directory);
	}

	/**
	 * Gets the directory with the trailing slash
	 * @return string
	 */
	public function __toString()
	{
		return $this->dir;
	}

	public function getBasename()
	{
		return basename($this);
	}

	/**
	 * @return Dir
	 */
	public function getParent()
	{
		return new Dir(dirname($this));
	}

	/**
	 * @param string $name
	 * @return Dir
	 */
	public function getChild($name)
	{
		return new Dir($this . $name);
	}

	/**
	 * @return Gz
	 */
	public function backup()
	{
		return Gz::compressDir($this);
	}

	public function getWithoutTrailingSlash()
	{
		return rtrim($this, DIRECTORY_SEPARATOR);
	}

	public function delete()
	{
		foreach ($this->getAllFiles() as $obj) {
			$obj->delete();
		}

		$result = rmdir($this);
		$this->dir = null;

		return $result;
	}

	public function copy($destination, $files_only = false)
	{
		$result = true;

		$dir = new Dir($destination, true);

		foreach ($this->getAllFiles($files_only) as $obj) {
			$out = $obj->copy($dir . $obj->getBasename());
			$result = $result && $out;	// will be false if any fail
		}

		return $result;
	}

	/**
	 * Get all the files that match $pattern
	 * @param string $pattern
	 * @return array of System
	 */
	public function getFiles($pattern)
	{
		$result = array();

		foreach (glob($this . $pattern) as $file) {
			$result[] = System::factory($file);
		}

		return $result;
	}

	/**
	 *
	 * @param string $filename
	 * @return File
	 */
	public function getFile($filename)
	{
		return System::factory($this->dir . $filename);
	}

	public function hasFile($filename)
	{
		return is_file($this->dir . $filename);
	}

	/**
	 * @param string $name
	 * @param string $data
	 * @return Text
	 */
	public function addTextFile($name, $data)
	{
		return Text::create($this . $name, $data);
	}

	private function addTrailingSlash($path)
	{
		$real = realpath($path);

		if (!$real) {
			throw new \Exception("{$path} could not be found.  Is the directory created?");
		}

		return $real . DIRECTORY_SEPARATOR;
	}

	/**
	 * @return array of System
	 */
	private function getAllFiles($files_only = false)
	{
		$files = scandir($this);

		$result = array();

		foreach ($files as $name) {
			if ($name == '.' || $name == '..') {
				continue;	// ignore these special files as they could be dangerous
			}

			$sys = System::factory($this . $name);

			if (($files_only && $sys->is_file()) || !$files_only) {
				$result[] = $sys;
			}
		}

		return $result;
	}
}