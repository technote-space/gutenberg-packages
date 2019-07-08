<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

use /** @noinspection PhpUndefinedClassInspection */
	PHPUnit\Framework\TestCase;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

require_once dirname( __FILE__ ) . '/misc/Helper.php';

/**
 * @noinspection PhpUndefinedClassInspection
 * GutenbergHelper test case.
 *
 * @mixin TestCase
 */
class GutenbergHelper extends WP_UnitTestCase {

	private function get_instance( $plugins = [], $get_remote = null ) {
		return new \Technote\GutenbergHelper( new TestHelper( $plugins, $get_remote ) );
	}

	public function test_can_use_block_editor() {
		global $wp_version;
		$tmp = $wp_version;

		$wp_version = '4.9';
		$this->assertFalse( $this->get_instance()->can_use_block_editor() );

		$wp_version = '5.0';
		$this->assertTrue( $this->get_instance()->can_use_block_editor() );

		$wp_version = '5.0.0';
		$this->assertTrue( $this->get_instance()->can_use_block_editor() );

		$wp_version = $tmp;
	}

	public function test_get_gutenberg_file() {
		$this->assertEquals( 'gutenberg/gutenberg.php', $this->get_instance()->get_gutenberg_file() );
	}

	public function test_get_gutenberg_absolute_path() {
		$this->assertEquals( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'gutenberg/gutenberg.php', $this->get_instance()->get_gutenberg_absolute_path() );
	}

	public function test_is_gutenberg_active() {
		$this->assertTrue( $this->get_instance( [
			'gutenberg/gutenberg.php',
			'a/a.php',
		] )->is_gutenberg_active() );

		$this->assertFalse( $this->get_instance( [
			'a/a.php',
			'b/b.php',
		] )->is_gutenberg_active() );
	}

	public function test_get_gutenberg_version() {
		$this->assertEquals( '', $this->get_instance()->get_gutenberg_version() );
		$this->assertNotEmpty( $this->get_instance( [
			'gutenberg/gutenberg.php',
		] )->get_gutenberg_version() );
	}

	public function test_get_release_version() {
		$this->assertNotEmpty( $this->get_instance( [ 'gutenberg/gutenberg.php' ] )->get_gutenberg_release_version() );
		$this->assertFalse( $this->get_instance()->get_gutenberg_release_version() );
	}

	public function test_get_gutenberg_packages() {
		$this->assertEquals( [], $this->get_instance()->get_gutenberg_packages() );
	}

	public function test_get_gutenberg_package_version() {
		$this->assertFalse( $this->get_instance()->get_gutenberg_package_version( 'test' ) );
		$this->assertFalse( $this->get_instance( [ 'gutenberg/gutenberg.php' ], false )->get_gutenberg_package_version( 'test' ) );
	}

}
