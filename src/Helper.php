<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote;

use Closure;
use Generator;
use Traversable;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Class Helper
 * @package Technote
 */
class Helper implements HelperInterface {

	/**
	 * @param array|Traversable $items
	 *
	 * @return Collection
	 */
	public function get_collection( $items ) {
		return new Collection( $items );
	}

	/**
	 * @param string $dir
	 *
	 * @return Generator
	 */
	public function dirlist( $dir ) {
		$dir = rtrim( $dir, DIRECTORY_SEPARATOR );
		if ( is_dir( $dir ) ) {
			foreach ( scandir( $dir ) as $item ) {
				if ( substr( $item, 0, 1 ) === '.' ) {
					continue;
				}

				$path = $dir . DIRECTORY_SEPARATOR . $item;
				if ( is_dir( $path ) ) {
					yield $path;
				}
			}
		}
	}

	/**
	 * @param string $haystack
	 * @param string $needle
	 *
	 * @return bool
	 */
	public function starts_with( $haystack, $needle ) {
		if ( '' === $haystack || '' === $needle ) {
			return false;
		}
		if ( $haystack === $needle ) {
			return true;
		}

		return strncmp( $haystack, $needle, strlen( $needle ) ) === 0;
	}

	/**
	 * @return string
	 */
	public function get_wp_version() {
		global $wp_version;

		return $wp_version;
	}

	/**
	 * @param string $version
	 * @param string $operator
	 *
	 * @return bool
	 */
	public function compare_wp_version( $version, $operator ) {
		return version_compare( $this->get_wp_version(), $version, $operator );
	}

	/**
	 * @param string $plugin
	 *
	 * @return bool
	 */
	public function is_plugin_active( $plugin ) {
		return in_array( $plugin, $this->get_active_plugins(), true );
	}

	/**
	 * @return array
	 */
	public function get_active_plugins() {
		$option = get_option( 'active_plugins', [] );
		if ( $this->is_multisite() ) {
			$option = array_merge( $option, array_keys( get_site_option( 'active_sitewide_plugins' ) ) );
			$option = array_unique( $option );
		}

		return array_values( $option );
	}

	/**
	 * @return bool
	 */
	protected function is_multisite() {
		return is_multisite();
	}

	/**
	 * @param string $version
	 *
	 * @return false|string
	 */
	public function get_release_version( $version ) {
		if ( empty( $version ) ) {
			return false;
		}

		if ( preg_match( '#v?(\d+)(\.\d+)(\.\d+)?#', $version, $matches ) ) {
			return $matches[1] . $matches[2];
		}

		return false;
	}

	/**
	 * @param string $url
	 *
	 * @return false|string
	 */
	public function get_remote( $url ) {
		$result = wp_remote_get( $url );
		if ( ! is_wp_error( $result ) && 200 === $result['response']['code'] ) {
			return $result['body'];
		}

		return false;
	}

	/**
	 * @param string $key
	 * @param Closure $get_value
	 *
	 * @return mixed
	 */
	public function get_cache( $key, $get_value ) {
		$value = get_transient( $key );
		if ( false === $value ) {
			$value = $get_value();
			set_transient( $key, $value, WEEK_IN_SECONDS );
		}

		return $value;
	}

}
