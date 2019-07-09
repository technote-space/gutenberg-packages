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
 * Interface HelperInterface
 * @package Technote
 */
interface HelperInterface {

	/**
	 * @param array|Traversable $items
	 *
	 * @return Collection
	 */
	public function get_collection( $items );

	/**
	 * @param int $cache_expiration
	 */
	public function set_cache_expiration( $cache_expiration );

	/**
	 * @param string $dir
	 *
	 * @return Generator
	 */
	public function dirlist( $dir );

	/**
	 * @param string $haystack
	 * @param string $needle
	 *
	 * @return bool
	 */
	public function starts_with( $haystack, $needle );

	/**
	 * @return string
	 */
	public function get_wp_version();

	/**
	 * @param string $version
	 * @param string $operator
	 *
	 * @return bool
	 */
	public function compare_wp_version( $version, $operator );

	/**
	 * @param string $plugin
	 *
	 * @return bool
	 */
	public function is_plugin_active( $plugin );

	/**
	 * @return array
	 */
	public function get_active_plugins();

	/**
	 * @param string $version
	 *
	 * @return false|string
	 */
	public function get_release_tag( $version );

	/**
	 * @param string $package
	 * @param string $prefix
	 *
	 * @return string
	 */
	public function normalize_package( $package, $prefix = 'wp-' );

	/**
	 * @param string $url
	 *
	 * @return false|string
	 */
	public function get_remote( $url );

	/**
	 * @param string $key
	 * @param string $cache_key
	 * @param Closure $get_value
	 *
	 * @return mixed
	 */
	public function get_cache( $key, $cache_key, $get_value );

	/**
	 * @param mixed $default
	 * @param Closure $check
	 * @param Closure ...$methods
	 *
	 * @return mixed
	 */
	public function get_data( $default, $check, ...$methods );

}
