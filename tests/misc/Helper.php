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

require_once dirname( __FILE__ ) . '/SetupArgsHelper.php';

/**
 * Class TestHelper
 * @property-read $plugins
 * @property-read $get_remote
 * @property-read $is_multisite
 */
class TestHelper extends Helper {

	use SetupArgsHelper;

	/**
	 * TestHelper constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		parent::__construct( isset( $args['cache_expiration'] ) ? $args['cache_expiration'] : null );
		$this->setup_args( $args );
	}

	/**
	 * @return array
	 */
	protected function target_args() {
		return [
			'plugins',
			'get_remote',
			'is_multisite',
		];
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
