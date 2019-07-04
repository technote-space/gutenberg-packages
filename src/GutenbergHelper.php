<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// @codeCoverageIgnoreEnd

/**
 * Class GutenbergHelper
 * @package Technote
 */
class GutenbergHelper implements GutenbergHelperInterface {

	/** @var HelperInterface $helper */
	private $helper;

	/**
	 * GutenbergHelper constructor.
	 *
	 * @param HelperInterface $helper
	 */
	public function __construct( HelperInterface $helper ) {
		$this->helper = $helper;
	}

	/**
	 * @return bool
	 */
	public function can_use_block_editor() {
		return $this->helper->compare_wp_version( '5.0', '>=' );
	}

	/**
	 * @return string
	 */
	public function get_gutenberg_file() {
		return 'gutenberg/gutenberg.php';
	}

	/**
	 * @return string
	 */
	public function get_gutenberg_absolute_path() {
		return WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->get_gutenberg_file();
	}

	/**
	 * @return bool
	 */
	public function is_gutenberg_active() {
		return $this->helper->is_plugin_active( $this->get_gutenberg_file() );
	}

	/**
	 * @return string
	 */
	public function get_gutenberg_version() {
		return $this->is_gutenberg_active() ? $this->helper->get_collection( get_plugin_data( $this->get_gutenberg_absolute_path() ) )->get( 'Version', '' ) : '';
	}

	/**
	 * @return false|string
	 */
	public function get_gutenberg_release_version() {
		return $this->helper->get_release_version( $this->get_gutenberg_version() );
	}

	/**
	 * @param string $version
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_github_url( $version, ...$append ) {
		return "https://raw.githubusercontent.com/WordPress/gutenberg/release/{$version}/" . implode( '/', $append );
	}

	/**
	 * @return array
	 */
	public function get_gutenberg_packages() {
		$version = $this->get_gutenberg_release_version();
		if ( empty( $version ) ) {
			return [];
		}

		$body = $this->helper->get_remote( $this->get_github_url( $version, 'package.json' ) );
		if ( empty( $body ) ) {
			return [];
		}

		$dependencies = $this->helper->get_collection( json_decode( $body, true ) )->get( 'dependencies' );

		return $this->helper->get_collection( $dependencies )->map( function ( $value ) {
			return basename( $value );
		} )->to_array();
	}

	/**
	 * @param $package
	 *
	 * @return bool|string
	 */
	public function get_gutenberg_package_version( $package ) {
		$version = $this->get_gutenberg_release_version();
		if ( empty( $version ) ) {
			return false;
		}

		$body = $this->helper->get_remote( $this->get_github_url( $version, 'packages', $package, 'package.json' ) );
		if ( empty( $body ) ) {
			return false;
		}

		return $this->helper->get_collection( json_decode( $body, true ) )->get( 'version' );
	}

}
