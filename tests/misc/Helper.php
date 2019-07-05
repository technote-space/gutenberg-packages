<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

use Technote\Helper;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// @codeCoverageIgnoreEnd

class TestHelper extends Helper {

	private $plugins;
	private $get_remote;
	private $is_multisite;

	public function __construct( $plugins = null, $get_remote = null, $is_multisite = null ) {
		$this->plugins      = $plugins;
		$this->get_remote   = $get_remote;
		$this->is_multisite = $is_multisite;
	}

	/**
	 * @return array
	 */
	public function get_active_plugins() {
		if ( isset( $this->plugins ) ) {
			return $this->plugins;
		}

		return parent::get_active_plugins();
	}

	/**
	 * @param string $url
	 *
	 * @return false|string
	 */
	public function get_remote( $url ) {
		if ( isset( $this->get_remote ) ) {
			return $this->get_remote;
		}

		return parent::get_remote( $url );
	}

	/**
	 * @return bool
	 */
	public function is_multisite() {
		if ( isset( $this->is_multisite ) ) {
			return $this->is_multisite;
		}

		return parent::is_multisite();
	}

}
