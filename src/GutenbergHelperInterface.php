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
 * Interface GutenbergHelperInterface
 * @package Technote
 */
interface GutenbergHelperInterface {

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
	public function get_gutenberg_version();

	/**
	 * @return false|string
	 */
	public function get_gutenberg_release_version();

	/**
	 * @param string $version
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_github_url( $version, ...$append );

	/**
	 * @return array
	 */
	public function get_gutenberg_packages();

	/**
	 * @param $package
	 *
	 * @return bool|string
	 */
	public function get_gutenberg_package_version( $package );

}
