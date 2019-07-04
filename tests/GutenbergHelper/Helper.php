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

	public function __construct( $plugins = [], $get_remote = null ) {
		parent::__construct( null );
		$this->plugins    = $plugins;
		$this->get_remote = $get_remote;
	}

	/**
	 * @return array
	 */
	public function get_active_plugins() {
		return $this->plugins;
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

}
