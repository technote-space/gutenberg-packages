<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote\Tests\Misc;

use ReflectionException;
use Technote\GutenbergHelperInterface;
use Technote\GutenbergPackages;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Class TestGutenbergPackages
 * @property-read $wp_core_package_versions_from_library
 * @property-read $wp_core_package_versions_from_api
 * @property-read $cache_exists
 */
class TestGutenbergPackages extends GutenbergPackages {

	use Testable;

	/**
	 * TestGutenbergPackages constructor.
	 *
	 * @param  array  $args
	 * @param  GutenbergHelperInterface|null  $helper
	 * @param  null|bool  $is_admin
	 *
	 * @throws ReflectionException
	 */
	public function __construct( array $args, GutenbergHelperInterface $helper = null, $is_admin = null ) {
		parent::__construct( $helper );
		$this->setup_args( $args );
		if ( isset( $is_admin ) ) {
			$this->set_property( 'in_admin', $is_admin, get_current_screen() );
		}
		$this->delete_transient( 'editor_package_versions' );
		$this->delete_transient( 'wp_core_package_versions' );
		$this->delete_transient( 'gutenberg_package_versions' );
	}

	/**
	 * @return array
	 */
	protected function target_args() {
		return [
			'wp_core_package_versions_from_library',
			'wp_core_package_versions_from_api',
			'cache_exists',
		];
	}

	/**
	 * @param $tag
	 *
	 * @return array|null
	 */
	protected function get_wp_core_package_versions_from_library( $tag ) {
		if ( isset( $this->wp_core_package_versions_from_library ) ) {
			return $this->wp_core_package_versions_from_library;
		}

		return parent::get_wp_core_package_versions_from_library( $tag );
	}

	/**
	 * @param $tag
	 *
	 * @return false|string
	 */
	protected function get_wp_core_package_versions_from_api( $tag ) {
		if ( isset( $this->wp_core_package_versions_from_api ) ) {
			return $this->wp_core_package_versions_from_api;
		}

		return parent::get_wp_core_package_versions_from_api( $tag );
	}

	/**
	 * @param  string  $key
	 *
	 * @return bool
	 */
	protected function cache_exists( $key ) {
		if ( isset( $this->cache_exists ) ) {
			return $this->cache_exists;
		}

		return parent::cache_exists( $key );
	}

}
