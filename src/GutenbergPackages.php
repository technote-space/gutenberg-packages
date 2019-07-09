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

	/** @var GutenbergHelperInterface $helper */
	private $helper;

	/** @var array|false $cache */
	private $cache;

	/**
	 * GutenbergPackages constructor.
	 *
	 * @param GutenbergHelperInterface|null $helper
	 */
	public function __construct( GutenbergHelperInterface $helper = null ) {
		$this->helper = isset( $helper ) ? $helper : new GutenbergHelper();
	}

	/**
	 * @return GutenbergHelperInterface
	 */
	public function get_gutenberg_helper() {
		return $this->helper;
	}

	/**
	 * @return HelperInterface
	 */
	protected function get_helper() {
		return $this->get_gutenberg_helper()->get_helper();
	}

	/**
	 * @return bool
	 */
	public function is_block_editor() {
		if ( ! is_admin() ) {
			return false;
		}

		if ( $this->get_gutenberg_helper()->can_use_block_editor() ) {
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
		if ( ! $this->get_gutenberg_helper()->can_use_block_editor() ) {
			return [];
		}

		if ( ! isset( $this->cache ) ) {
			$this->cache = $this->get_gutenberg_helper()->get_cache( function () {
				return $this->get_helper()->get_data( false,
					function ( $data ) {
						return false !== $data;
					},
					function () {
						return $this->get_gutenberg_package_versions();
					},
					function () {
						return $this->get_wp_core_package_versions();
					}
				);
			} );
		}

		return $this->cache;
	}

	/**
	 * @return array
	 */
	public function get_wp_core_package_versions() {
		$tag = $this->get_gutenberg_helper()->get_provider()->normalize_tag( $this->get_helper()->get_wp_version() );

		return $this->get_helper()->get_data( [],
			function ( $data ) {
				return is_array( $data );
			},
			function () use ( $tag ) {
				return $this->get_wp_core_package_versions_from_library( $tag );
			},
			function () use ( $tag ) {
				return $this->get_wp_core_package_versions_from_api( $tag );
			},
			function () {
				return $this->get_wp_core_package_versions_from_repository();
			}
		);
	}

	/**
	 * @param $tag
	 *
	 * @return array|null
	 */
	protected function get_wp_core_package_versions_from_library( $tag ) {
		return $this->get_gutenberg_helper()->get_provider( 'wp' )->get_versions( $tag );
	}

	/**
	 * @param $tag
	 *
	 * @return false|string
	 */
	protected function get_wp_core_package_versions_from_api( $tag ) {
		return $this->get_helper()->get_remote( $this->get_gutenberg_helper()->get_api_url( 'wp', 'tags', "{$tag}.json" ) );
	}

	/**
	 * @return array
	 */
	protected function get_wp_core_package_versions_from_repository() {
		$scripts = new WP_Scripts();
		wp_default_packages_scripts( $scripts );

		return $this->get_helper()->get_collection( $scripts->registered )->map( function ( $script ) {
			return $script->ver;
		} )->filter( function ( $version, $key ) {
			return $version && $this->get_helper()->starts_with( $key, 'wp-' );
		} )->to_array();
	}

	/**
	 * @return array|false
	 */
	public function get_gutenberg_package_versions() {
		if ( $this->get_gutenberg_helper()->is_gutenberg_active() ) {
			return $this->get_helper()->get_collection( $this->get_gutenberg_helper()->get_gutenberg_packages() )->map( function ( $package ) {
				$version = $this->get_gutenberg_helper()->get_gutenberg_package_version( $package );
				if ( empty( $version ) ) {
					return false;
				}

				return [
					'package' => $package,
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
		return $this->get_helper()->get_collection( $this->get_editor_package_versions() )->exists( $this->get_helper()->normalize_package( $package ) );
	}

	/**
	 * @param string $package
	 *
	 * @return string|false
	 */
	public function get_editor_package_version( $package ) {
		return $this->get_helper()->get_collection( $this->get_editor_package_versions() )->get( $this->get_helper()->normalize_package( $package ), false );
	}

	/**
	 * @return string
	 */
	public function get_gutenberg_version() {
		return $this->get_gutenberg_helper()->get_gutenberg_tag();
	}
}
