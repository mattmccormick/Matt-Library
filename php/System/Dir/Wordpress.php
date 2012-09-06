<?php

namespace Matt\System\Dir;

use Matt\System\Dir;

require_once DIR_WORDPRESS . DIRECTORY_SEPARATOR . 'wp-load.php';

class Wordpress extends Dir
{
	const DIR_LIB = '/home/matt/www/libs/wordpress/';
	const WP_INCLUDES = 'wp-includes';
	const WP_ADMIN = 'wp-admin';
	const WP_CONTENT = 'wp-content';

	/**
	 * @var boolean
	 */
	private $upgrade = null;

	public function __construct()
	{
		if (!defined('DIR_WORDPRESS')) {
			throw new \Exception('The constant DIR_WORDPRESS must be defined prior to instantiating this object');
		}

		parent::__construct(DIR_WORDPRESS);
	}

	public function upgrade()
	{
		if (!$this->isUpgradeAvailable()) {
			echo "No upgrade available\n";
			return false;
		}

		$update = $this->getUpgradeStatus();

		$url = $update->download;

		$lib = new Dir(self::DIR_LIB);

		$curl = new \Matt\Curl($url);
		$download = $curl->download($lib);
		$files = $download->extract();

		$this->backup();

		$delete_copy = array(self::WP_ADMIN, self::WP_INCLUDES);

		$wp_dir = $files->getChild('wordpress');

		foreach ($delete_copy as $d) {
			$this->getChild($d)->delete();
			$wp_dir->getChild($d)->copy($this . $d);
		}

		$wp_dir->getChild(self::WP_CONTENT)->copy($this . self::WP_CONTENT);

		$wp_dir->copy($this, true);
	}

	public function isUpgradeAvailable()
	{
		$status = $this->getUpgradeStatus();
		return $status && $status->response == 'upgrade';
	}

	private function getUpgradeStatus()
	{
		if ($this->upgrade === null) {
			require_once $this . 'wp-admin/includes/update.php';
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