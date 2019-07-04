<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote;

use WP_Scripts;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Class GutenbergPackages
 * @package Technote
 */
class GutenbergPackages {

	/** @var HelperInterface $helper */
	private $helper;

	/** @var GutenbergHelperInterface $gutenberg_helper */
	private $gutenberg_helper;

	/** @var array|false $cache */
	private $cache;

	/** @var bool $is_admin */
	private $is_admin;

	/**
	 * GutenbergPackages constructor.
	 *
	 * @param HelperInterface|null $helper
	 * @param GutenbergHelperInterface|null $gutenberg_helper
	 * @param bool|null $is_admin
	 */
	public function __construct( HelperInterface $helper = null, GutenbergHelperInterface $gutenberg_helper = null, $is_admin = null ) {
		$this->helper           = isset( $helper ) ? $helper : new Helper();
		$this->gutenberg_helper = isset( $gutenberg_helper ) ? $gutenberg_helper : new GutenbergHelper( $this->helper );
		$this->is_admin         = isset( $is_admin ) ? $is_admin : is_admin();
	}

	/**
	 * @return bool
	 */
	public function is_block_editor() {
		if ( ! $this->is_admin ) {
			return false;
		}

		if ( $this->gutenberg_helper->can_use_block_editor() ) {
			return get_current_screen()->is_block_editor();
		}

		/** @noinspection PhpDeprecationInspection */
		// @codeCoverageIgnoreStart
		return function_exists( 'is_gutenberg_page' ) && is_gutenberg_page();
		// @codeCoverageIgnoreEnd
	}

	/**
	 * @return array|false
	 */
	public function get_editor_package_versions() {
		if ( ! $this->gutenberg_helper->can_use_block_editor() ) {
			return [];
		}

		if ( ! isset( $this->cache ) ) {
			$this->cache = $this->get_gutenberg_package_versions();
			if ( false === $this->cache ) {
				$this->cache = $this->get_wp_editor_package_versions();
			}
		}

		return $this->cache;
	}

	/**
	 * @return array
	 */
	public function get_wp_editor_package_versions() {
		$scripts = new WP_Scripts();
		wp_default_packages_scripts( $scripts );

		return $this->helper->get_collection( $scripts->registered )->map( function ( $script ) {
			return $script->ver;
		} )->filter( function ( $version, $key ) {
			return $version && $this->helper->starts_with( $key, 'wp-' );
		} )->to_array();
	}

	/**
	 * @return array|false
	 */
	public function get_gutenberg_package_versions() {
		if ( $this->gutenberg_helper->is_gutenberg_active() ) {
			return $this->helper->get_collection( $this->gutenberg_helper->get_gutenberg_packages() )->map( function ( $package ) {
				$version = $this->gutenberg_helper->get_gutenberg_package_version( $package );
				if ( empty( $version ) ) {
					return false;
				}

				return [
					'package' => 'wp-' . $package,
					'version' => $version,
				];
			} )->filter( function ( $data ) {
				return false !== $data;
			} )->combine( 'package', 'version' );
		}

		return false;
	}


	/**
	 * @param string $package
	 *
	 * @return bool
	 */
	public function is_support_editor_package( $package ) {
		return $this->helper->get_collection( $this->get_editor_package_versions() )->exists( $package );
	}

	/**
	 * @param string $package
	 *
	 * @return string|false
	 */
	public function get_editor_package_version( $package ) {
		return $this->helper->get_collection( $this->get_editor_package_versions() )->get( $package, false );
	}
}
