<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

use Technote\GutenbergHelper;
use Technote\HelperInterface;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

require_once dirname( __FILE__ ) . '/SetupArgsHelper.php';

/**
 * Class TestGutenbergHelper
 * @property-read $can_use_block_editor
 * @property-read $is_gutenberg_active
 * @property-read $github_url
 * @property-read $gutenberg_packages_from_library
 * @property-read $gutenberg_packages_from_api
 * @property-read $gutenberg_package_version_from_library
 * @property-read $gutenberg_package_version_from_api
 * @property-read $gutenberg_absolute_path
 * @property-read $gutenberg_package_version
 */
class TestGutenbergHelper extends GutenbergHelper {

	use SetupArgsHelper;

	/**
	 * TestGutenbergHelper constructor.
	 *
	 * @param array $args
	 * @param HelperInterface|null $helper
	 */
	public function __construct( array $args, HelperInterface $helper = null ) {
		parent::__construct( $helper );
		$this->setup_args( $args );
	}

	/**
	 * @return array
	 */
	protected function target_args() {
		return [
			'can_use_block_editor',
			'is_gutenberg_active',
			'github_url',
			'gutenberg_packages_from_library',
			'gutenberg_packages_from_api',
			'gutenberg_package_version_from_library',
			'gutenberg_package_version_from_api',
			'gutenberg_absolute_path',
			'gutenberg_package_version',
		];
	}

	/**
	 * @return bool
	 */
	public function can_use_block_editor() {
		if ( isset( $this->can_use_block_editor ) ) {
			return $this->can_use_block_editor;
		}

		return parent::can_use_block_editor();
	}

	/**
	 * @return bool
	 */
	public function is_gutenberg_active() {
		if ( isset( $this->is_gutenberg_active ) ) {
			return $this->is_gutenberg_active;
		}

		return parent::is_gutenberg_active();
	}

	/**
	 * @return string
	 */
	public function get_gutenberg_absolute_path() {
		if ( isset( $this->gutenberg_absolute_path ) ) {
			return $this->gutenberg_absolute_path;
		}

		return parent::get_gutenberg_absolute_path();
	}

	/**
	 * @param string $package
	 * @param string|null $tag
	 *
	 * @return false|string
	 */
	public function get_gutenberg_package_version( $package, $tag = null ) {
		if ( isset( $this->gutenberg_package_version ) ) {
			return call_user_func( $this->gutenberg_package_version, $package, parent::get_gutenberg_package_version( $package, $tag ) );
		}

		return parent::get_gutenberg_package_version( $package, $tag );
	}

	/**
	 * @param string $version
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_repository_url( $version, ...$append ) {
		if ( isset( $this->github_url ) ) {
			return $this->github_url;
		}

		return parent::get_repository_url( $version, ...$append );
	}

	/**
	 * @param string $tag
	 *
	 * @return array|null
	 */
	protected function get_gutenberg_packages_from_library( $tag ) {
		if ( isset( $this->gutenberg_packages_from_library ) ) {
			return $this->gutenberg_packages_from_library;
		}

		return parent::get_gutenberg_packages_from_library( $tag );
	}

	/**
	 * @param string $tag
	 *
	 * @return array|null
	 */
	protected function get_gutenberg_packages_from_api( $tag ) {
		if ( isset( $this->gutenberg_packages_from_api ) ) {
			return $this->gutenberg_packages_from_api;
		}

		return parent::get_gutenberg_packages_from_api( $tag );
	}

	/**
	 * @param string $tag
	 * @param string $package
	 *
	 * @return false|string
	 */
	protected function get_gutenberg_package_version_from_library( $tag, $package ) {
		if ( isset( $this->gutenberg_package_version_from_library ) ) {
			return $this->gutenberg_package_version_from_library;
		}

		return parent::get_gutenberg_package_version_from_library( $tag, $package );
	}

	/**
	 * @param string $tag
	 * @param string $package
	 *
	 * @return false|string
	 */
	protected function get_gutenberg_package_version_from_api( $tag, $package ) {
		if ( isset( $this->gutenberg_package_version_from_api ) ) {
			return $this->gutenberg_package_version_from_api;
		}

		return parent::get_gutenberg_package_version_from_api( $tag, $package );
	}

}
