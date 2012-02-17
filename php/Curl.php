<?php

namespace Matt;

use Matt\System\Dir;
use Matt\System\File;

class Curl
{
	private $url;
	private $filename;
	private $headers;

	public function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * This method tries to emulate wget in that it takes a URL and saves the file
	 * received to the $directory directory.
	 * Warning: This method overwrites any file with the same name
	 * @param Dir $directory
	 * @return File the file downloaded
	 * @throws Exception
	 */
	public function download(Dir $dir)
	{
		$temp_file = tempnam(sys_get_temp_dir(), 'curl');

		$fp = fopen($temp_file, 'w');

		$ch = curl_init($this->url);

	    curl_setopt($ch, CURLOPT_FILE, $fp);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, 'headerCallback'));
	    $out = curl_exec($ch);

		if (!$this->filename) {
			throw new \Exception("No filename found\n{$this->headers}");
		}

		$fullpath = $dir . $this->filename;

		copy($temp_file, $fullpath);

		curl_close($ch);
	    fclose($fp);

	    return File::factory($fullpath);
	}

	public function headerCallback($ch, $line)
	{
		$match = 'attachment; filename=';
		$pos = stripos($line, $match);

		if ($pos) {
			$this->filename = trim(str_replace('"', '', substr($line, $pos + strlen($match))));
		}

		$this->headers .= $line;
		return strlen($line);
	}
}