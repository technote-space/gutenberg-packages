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
use WP_Filesystem_Direct;

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
	 * @return WP_Filesystem_Direct
	 */
	public function get_fs();

	/**
	 * @param string $version
	 *
	 * @return false|string
	 */
	public function get_release_version( $version );

	/**
	 * @param string $url
	 *
	 * @return false|string
	 */
	public function get_remote( $url );

	/**
	 * @param string $key
	 * @param Closure $get_value
	 *
	 * @return mixed
	 */
	public function get_cache( $key, $get_value );

}
