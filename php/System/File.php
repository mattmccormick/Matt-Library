<?php

namespace Matt\System;

use Matt\System;

class File extends System
{
	const MIME_ZIP = 'application/zip';

	private $file;
	private $info;
	private $contents;

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

	public function __toString()
	{
		return $this->file;
	}

	public function getExt()
	{
		return $this->info['extension'];
	}

	/**
	 * @return Dir
	 */
	public function getParent()
	{
		if (!$this->dir) {
			$this->dir = new Dir($this->info['dirname']);
		}

		return $this->dir;
	}

	/**
	 * @return string the filename without the extension
	 */
	public function getFilename()
	{
		return $this->info['filename'];
	}

	public function getBasename()
	{
		return $this->info['basename'];
	}

	public function getMimeType()
	{
		return self::get_mime_type($this->path);
	}

	public function copy($destination)
	{
		return copy($this, $destination);
	}

	public function delete()
	{
		$result = unlink($this);
		$this->file = null;

		return $result;
	}

	public function getContents()
	{
		if (!$this->contents) {
			$this->contents = file_get_contents($this);
		}

		return $this->contents;
	}

	public function getContentsBefore($delimiter)
	{
		$pos = strpos($this->getContents(), $delimiter);

		return substr($this->getContents(), 0, $pos);
	}

	public function getContentsAfter($delimiter)
	{
		$pos = strpos($this->getContents(), $delimiter) + strlen($delimiter);

		return substr($this->getContents(), $pos);
	}

	public static function get_mime_type($file)
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $file);
		finfo_close($finfo);
		return $mime;
	}

	/**
	 * @param string $file
	 * @return File
	 */
	public static function factory($file)
	{
		$mime_type = self::get_mime_type($file);

		switch ($mime_type) {
			case File::MIME_ZIP:
				return new File\Archive\Zip($file);

			default:
				return new File($file);
		}
	}
}