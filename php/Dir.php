<?php

namespace Matt;

class Dir
{
	private $dir;

	public function __construct($directory)
	{
		if (!is_dir($directory)) {
			throw new \Exception("{$directory} is not a directory");
		}

		$this->dir = realpath($directory) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Gets the directory with the trailing slash
	 * @return string
	 */
	public function get()
	{
		return $this->dir;
	}

	public function backup()
	{
		exec(DIR_LIBRARY . "../ruby/tarball.rb {$this->get()}");
	}
}