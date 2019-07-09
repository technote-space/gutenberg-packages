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
 * Interface GutenbergHelperInterface
 * @package Technote
 */
interface GutenbergHelperInterface {

	/**
	 * @return HelperInterface
	 */
	public function get_helper();

	/**
	 * @param string|null $target
	 *
	 * @return GutenbergPackageVersionProvider
	 */
	public function get_provider( $target = null );

	/**
	 * @return bool
	 */
	public function can_use_block_editor();

	/**
	 * @return string
	 */
	public function get_gutenberg_file();

	/**
	 * @return string
	 */
	public function get_gutenberg_absolute_path();

	/**
	 * @return bool
	 */
	public function is_gutenberg_active();

	/**
	 * @return string
	 */
	public function get_gutenberg_tag();

	/**
	 * @param string $version
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_repository_url( $version, ...$append );

	/**
	 * @param string $target
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_api_url( $target, ...$append );

	/**
	 * @return array
	 */
	public function get_gutenberg_packages();

	/**
	 * @param string $package
	 * @param string|null $tag
	 *
	 * @return false|string
	 */
	public function get_gutenberg_package_version( $package, $tag = null );

	/**
	 * @return string
	 */
	public function get_cache_key();

	/**
	 * @param string $key
	 * @param Closure $get_value
	 *
	 * @return mixed
	 */
	public function get_cache( $key, $get_value );

}
