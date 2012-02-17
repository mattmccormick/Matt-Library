<?php

namespace Matt;

spl_autoload_register('\Matt\Loader::registerAutoload');

define('DIR_LIBRARY', realpath(__DIR__) . DIRECTORY_SEPARATOR);

class Loader
{
	public static function registerAutoload($name)
	{
		if (strpos($name, __NAMESPACE__) === 0) {
			$class = str_replace('\\', DIRECTORY_SEPARATOR, substr($name, strlen(__NAMESPACE__) + 1));
			$file = DIR_LIBRARY . $class . '.php';

			if (file_exists($file)) {
				require $file;
			}
		}
	}
}