<?php

namespace Matt;

class File
{
	private $file;
	private $info;

	/**
	 * @var Dir
	 */
	private $dir;

	public function __construct($file)
	{
		if (!is_file($file)) {
			throw new \Exception("{$file} is not a file");
		}

		$this->file = $file;
		$this->info = pathinfo($file);
	}

	/**
	 * Extract the file to the $directory
	 * @param string $directory
	 * @return boolean
	 * @throws Exception
	 */
	public function extract(Dir $directory = null)
	{
		$destination = $directory ? $directory->get() : $this->getDir()->get() . $this->getFilename();

		switch ($this->getExt()) {
			case 'zip':
				$zip = new \ZipArchive();
				$zip->open($this->file);
				$result = $zip->extractTo($destination);
				$zip->close();
				return $result;

			default:
				throw new \Exception("extract() not implemented for type {$this->getExt()}");
				break;
		}
	}

	public function getExt()
	{
		return $this->info['extension'];
	}

	/**
	 * @return Dir
	 */
	public function getDir()
	{
		if (!$this->dir) {
			$this->dir = new Dir($this->info['dirname']);
		}

		return $this->dir;
	}

	public function getFilename()
	{
		return $this->info['filename'];
	}
}