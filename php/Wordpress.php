<?php

namespace Matt;

require_once DIR_WORDPRESS . DIRECTORY_SEPARATOR . 'wp-load.php';

class Wordpress
{
	const DIR_LIB = '/home/matt/www/libs/wordpress/';

	/**
	 * @var Dir
	 */
	private $path;

	/**
	 * @var boolean
	 */
	private $upgrade = null;

	public function __construct()
	{
		if (!defined('DIR_WORDPRESS')) {
			throw new \Exception('The constant DIR_WORDPRESS must be defined prior to instantiating this object');
		}

		$this->path = new Dir(DIR_WORDPRESS);
	}

	public function upgrade()
	{
		if (!$this->isUpgradeAvailable()) {
			return false;
		}

		$update = $this->getUpgradeStatus();

		$url = $update->download;

		$curl = new \Matt\Curl($url);
		$download = $curl->download(self::DIR_LIB);
		$download->extract();

		$this->backup();
	}

	public function isUpgradeAvailable()
	{
		$status = $this->getUpgradeStatus();
		return $status && $status->response == 'upgrade';
	}

	public function backup()
	{
		$this->path->backup();
	}

	private function getUpgradeStatus()
	{
		if ($this->upgrade === null) {
			require_once $this->path->get() . 'wp-admin/includes/update.php';
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