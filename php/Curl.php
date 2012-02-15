<?php

namespace Matt;

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
	 * received to the $directory directory
	 * @param string $directory
	 * @throws Exception
	 */
	public function download($directory)
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
			throw new Exception("No filename found\n{$this->headers}");
		}

		if (substr($directory, -1) != DIRECTORY_SEPARATOR) {
			$directory .= DIRECTORY_SEPARATOR;
		}

		copy($temp_file, $directory . $this->filename);

		curl_close($ch);
	    fclose($fp);
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