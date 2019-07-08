<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

use Technote\Helper;
use Technote\GutenbergHelper;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

class TestGutenbergHelper extends GutenbergHelper {

	private $can_use_block_editor;
	private $is_gutenberg_active;
	private $github_url;

	public function __construct( $can_use_block_editor = true, $is_gutenberg_active = false, $github_url = null ) {
		parent::__construct( new Helper() );
		$this->can_use_block_editor = $can_use_block_editor;
		$this->is_gutenberg_active  = $is_gutenberg_active;
		$this->github_url           = $github_url;
	}

	/**
	 * @return bool
	 */
	public function can_use_block_editor() {
		return $this->can_use_block_editor;
	}

	/**
	 * @return bool
	 */
	public function is_gutenberg_active() {
		return $this->is_gutenberg_active;
	}

	/**
	 * @return string
	 */
	public function get_gutenberg_absolute_path() {
		return '/tmp/wordpress/wp-content/plugins/gutenberg/gutenberg.php';
	}

	/**
	 * @param $package
	 *
	 * @return bool|string
	 */
	public function get_gutenberg_package_version( $package ) {
		if ( 'rich-text' === $package ) {
			return false;
		}

		if ( 'hooks' === $package ) {
			return parent::get_gutenberg_package_version( $package );
		}

		return wp_json_encode( [
			'version' => '1.2.3',
		] );
	}

	/**
	 * @param string $version
	 * @param mixed ...$append
	 *
	 * @return string
	 */
	public function get_github_url( $version, ...$append ) {
		if ( isset( $this->github_url ) ) {
			return $this->github_url;
		}

		return parent::get_github_url( $version, ...$append );
	}
}
