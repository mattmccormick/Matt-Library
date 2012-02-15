<?php

namespace Matt;

class Wordpress
{
	private $path;
	private $upgrade = null;

	public function __construct($path)
	{
		$this->path = $path . DIRECTORY_SEPARATOR;
		$this->init();
	}

	private function init()
	{
		require_once $this->path . 'wp-load.php';
	}

	public function upgrade()
	{
		if (!$this->isUpgradeAvailable()) {
			return false;
		}

define('DIR_WORDPRESS', '/home/matt/www/libs/wordpress/');

print_r($trans);


print_r($update);

exit;
if ($update[0]->response == 'upgrade') {
	$url = $update[0]->package;

	$curl = new \Matt\Curl($url);
	$curl->download(DIR_WORDPRESS);
}
	}

	public function isUpgradeAvailable()
	{
		$status = $this->getUpgradeStatus();
		print_r($status);
		return $status && $status->response == 'upgrade';
	}

	private function getUpgradeStatus()
	{
		if ($this->upgrade === null) {
			require_once $this->path . 'wp-admin/includes/update.php';
			$status = get_core_updates();

			if (!$status) {
				$this->upgrade = $status;
			} else {
				$this->upgrade = $status[0];
			}
		}

		return $this->upgrade;
	}
}