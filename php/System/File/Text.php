<?php

namespace Matt\System\File;

use Matt\System\File;

class Text extends File
{
	private $fp;

	public function getLine()
	{
		$fp = $this->getFp();

		$line = fgets($fp);

		if ($line === false) {
			fclose($this->fp);
			$this->fp = null;
		}

		return $line;
	}

	public static function create($file, $data)
	{
		file_put_contents($file, $data);
		return new Text($file);
	}

	private function getFp()
	{
		if (!$this->fp) {
			$this->fp = fopen($this, 'r');
		}

		return $this->fp;
	}
}