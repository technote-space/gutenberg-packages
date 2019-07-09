<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote;

use Closure;

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

	/** @var string $cache_key */
	private $cache_key;

	/** @var GutenbergPackageVersionProvider[] $provider */
	private $provider = [];

	/**
	 * GutenbergHelper constructor.
	 *
	 * @param HelperInterface|null $helper
	 */
	public function __construct( HelperInterface $helper = null ) {
		$this->helper = isset( $helper ) ? $helper : new Helper();
	}

	/**
	 * @return HelperInterface
	 */
	public function get_helper() {
		return $this->helper;
	}

	/**
	 * @param string|null $target
	 *
	 * @return GutenbergPackageVersionProvider
	 */
	public function get_provider( $target = null ) {
		$target = $this->normalize_target( $target );
		if ( ! isset( $this->provider[ $target ] ) ) {
			$this->provider[ $target ] = new GutenbergPackageVersionProvider( $target );
		}

		return $this->provider[ $target ];
	}

	/**
	 * @param string $target
	 *
	 * @return string
	 */
	protected function normalize_target( $target ) {
		if ( ! isset( $target ) ) {
			$target = 'gutenberg';
		} elseif ( 'gutenberg' !== $target ) {
			$target = 'wp-core';
		}

		return $target;
	}

	/**
	 * @return bool
	 */
	public function can_use_block_editor() {
		return $this->get_helper()->compare_wp_version( '5.0', '>=' );
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
		return $this->get_helper()->is_plugin_active( $this->get_gutenberg_file() );
	}

	/**
	 * @return string
	 */
	public function get_gutenberg_tag() {
		return $this->is_gutenberg_active() ? $this->get_helper()->get_collection( get_plugin_data( $this->get_gutenberg_absolute_path() ) )->get( 'Version', '' ) : '';
	}

	/**
	 * @param string $tag
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_repository_url( $tag, ...$append ) {
		return "https://raw.githubusercontent.com/WordPress/gutenberg/release/{$this->get_helper()->get_release_tag( $tag )}/" . implode( '/', $append );
	}

	/**
	 * @param string $target
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_api_url( $target, ...$append ) {
		return "https://api.wp-framework.dev/api/v1/{$this->normalize_target( $target )}/" . implode( '/', $append );
	}

	/**
	 * @param string|null $tag
	 *
	 * @return array
	 */
	public function get_gutenberg_packages( $tag = null ) {
		if ( ! isset( $tag ) ) {
			$tag = $this->get_gutenberg_tag();
		}
		if ( empty( $tag ) ) {
			return [];
		}

		return $this->get_helper()->get_data( [],
			function ( $data ) {
				return is_array( $data );
			},
			function () use ( $tag ) {
				return $this->get_gutenberg_packages_from_library( $tag );
			},
			function () use ( $tag ) {
				return $this->get_gutenberg_packages_from_api( $tag );
			},
			function () use ( $tag ) {
				return $this->get_gutenberg_packages_from_repository( $tag );
			}
		);
	}

	/**
	 * @param string $tag
	 *
	 * @return array|null
	 */
	protected function get_gutenberg_packages_from_library( $tag ) {
		$versions = $this->get_provider()->get_versions( $tag );
		if ( isset( $versions ) ) {
			return array_keys( $versions );
		}

		return null;
	}

	/**
	 * @param string $tag
	 *
	 * @return array|null
	 */
	protected function get_gutenberg_packages_from_api( $tag ) {
		$versions = $this->get_helper()->get_remote( $this->get_api_url( null, 'tags', "{$this->get_provider()->normalize_tag($tag)}.json" ) );
		if ( ! empty( $versions ) ) {
			return array_keys( json_decode( $versions, true ) );
		}

		return null;
	}

	/**
	 * @param string $tag
	 *
	 * @return array
	 */
	protected function get_gutenberg_packages_from_repository( $tag ) {
		$body = $this->get_helper()->get_remote( $this->get_repository_url( $tag, 'package.json' ) );
		if ( empty( $body ) ) {
			return [];
		}

		return $this->get_helper()->get_collection( $this->get_helper()->get_collection( json_decode( $body, true ) )->get( 'dependencies' ) )->map( function ( $value ) {
			return $this->get_helper()->normalize_package( basename( $value ) );
		} )->to_array();
	}

	/**
	 * @param string $package
	 * @param string|null $tag
	 *
	 * @return false|string
	 */
	public function get_gutenberg_package_version( $package, $tag = null ) {
		if ( ! isset( $tag ) ) {
			$tag = $this->get_gutenberg_tag();
		}
		if ( empty( $tag ) ) {
			return false;
		}

		return $this->get_helper()->get_data( false,
			function ( $data ) {
				return false !== $data;
			},
			function () use ( $tag, $package ) {
				return $this->get_gutenberg_package_version_from_library( $tag, $package );
			},
			function () use ( $tag, $package ) {
				return $this->get_gutenberg_package_version_from_api( $tag, $package );
			},
			function () use ( $tag, $package ) {
				return $this->get_gutenberg_package_version_from_repository( $tag, $package );
			}
		);
	}

	/**
	 * @param string $tag
	 * @param string $package
	 *
	 * @return false|string
	 */
	protected function get_gutenberg_package_version_from_library( $tag, $package ) {
		return $this->get_provider()->get_package_version( $tag, $this->get_helper()->normalize_package( $package ) );
	}

	/**
	 * @param string $tag
	 * @param string $package
	 *
	 * @return false|string
	 */
	protected function get_gutenberg_package_version_from_api( $tag, $package ) {
		$versions = $this->get_helper()->get_remote( $this->get_api_url( null, 'tags', "{$this->get_provider()->normalize_tag($tag)}.json" ) );
		if ( ! empty( $versions ) ) {
			$versions = json_decode( $versions, true );
			$package  = $this->get_helper()->normalize_package( $package );
			if ( isset( $versions[ $package ] ) ) {
				return $versions[ $package ];
			}
		}

		return false;
	}

	/**
	 * @param string $tag
	 * @param string $package
	 *
	 * @return false|string
	 */
	protected function get_gutenberg_package_version_from_repository( $tag, $package ) {
		$body = $this->get_helper()->get_remote( $this->get_repository_url( $tag, 'packages', $this->get_helper()->normalize_package( $package, '' ), 'package.json' ) );
		if ( empty( $body ) ) {
			return false;
		}

		return $this->get_helper()->get_collection( json_decode( $body, true ) )->get( 'version', false );
	}

	/**
	 * @return string
	 */
	public function get_cache_key() {
		if ( ! isset( $this->cache_key ) ) {
			$this->cache_key = $this->generate_cache_key();
		}

		return $this->cache_key;
	}

	/**
	 * @return string
	 */
	protected function generate_cache_key() {
		return sha1( wp_json_encode( [
			$this->get_helper()->get_wp_version(),
			$this->get_gutenberg_tag(),
		] ) );
	}

	/**
	 * @param string $key
	 * @param Closure $get_value
	 *
	 * @return mixed
	 */
	public function get_cache( $key, $get_value ) {
		return $this->get_helper()->get_cache( $key, $this->get_cache_key(), $get_value );
	}

}
