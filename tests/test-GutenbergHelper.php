<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote\Tests;

use PHPUnit\Framework\TestCase;
use Technote\Tests\Misc\TestGutenbergHelper;
use Technote\Tests\Misc\TestHelper;
use /** @noinspection PhpUndefinedClassInspection */
	WP_UnitTestCase;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * @noinspection PhpUndefinedClassInspection
 * GutenbergHelper test case.
 *
 * @mixin TestCase
 */
class GutenbergHelper extends WP_UnitTestCase {

	private function get_instance( $args = [] ) {
		return new TestGutenbergHelper( $args, new TestHelper( $args ) );
	}

	public function test_can_use_block_editor() {
		global $wp_version;
		$tmp = $wp_version;

		$wp_version = '4.9'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertFalse( $this->get_instance()->can_use_block_editor() );

		$wp_version = '5.0'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertTrue( $this->get_instance()->can_use_block_editor() );

		$wp_version = '5.0.0'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertTrue( $this->get_instance()->can_use_block_editor() );

		$wp_version = $tmp; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	public function test_get_gutenberg_file() {
		$this->assertEquals( 'gutenberg/gutenberg.php', $this->get_instance()->get_gutenberg_file() );
	}

	public function test_get_gutenberg_absolute_path() {
		$this->assertEquals( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'gutenberg/gutenberg.php', $this->get_instance()->get_gutenberg_absolute_path() );
	}

	public function test_is_gutenberg_active() {
		$this->assertTrue( $this->get_instance( [
			'plugins' => [
				'gutenberg/gutenberg.php',
				'a/a.php',
			],
		] )->is_gutenberg_active() );

		$this->assertFalse( $this->get_instance( [
			'plugins' => [
				'a/a.php',
				'b/b.php',
			],
		] )->is_gutenberg_active() );
	}

	public function test_get_gutenberg_version() {
		$this->assertEquals( '', $this->get_instance()->get_gutenberg_tag() );
		$this->assertNotEmpty( $this->get_instance( [
			'plugins' => [ 'gutenberg/gutenberg.php' ],
		] )->get_gutenberg_tag() );
	}

	public function test_get_gutenberg_packages() {
		$this->assertEquals( [], $this->get_instance()->get_gutenberg_packages() );
		$this->assertEquals( [], $this->get_instance( [
			'plugins' => [ 'gutenberg/gutenberg.php' ],
		] )->get_gutenberg_packages( '0.0.0' ) );
	}

	public function test_get_gutenberg_package_version() {
		$this->assertFalse( $this->get_instance()->get_gutenberg_package_version( 'test-package' ) );
		$this->assertFalse( $this->get_instance( [
			'plugins'    => [ 'gutenberg/gutenberg.php' ],
			'get_remote' => false,
		] )->get_gutenberg_package_version( 'test-package' ) );
		$this->assertFalse( $this->get_instance( [
			'plugins' => [ 'gutenberg/gutenberg.php' ],
		] )->get_gutenberg_package_version( 'test-package', '0.0.0' ) );

		$this->assertNotEmpty( $this->get_instance( [
			'plugins'                                => [ 'gutenberg/gutenberg.php' ],
			'gutenberg_package_version_from_library' => false,
			'gutenberg_package_version_from_api'     => false,
		] )->get_gutenberg_package_version( 'wp-data', '5.0.0' ) );
		$this->assertFalse( $this->get_instance( [
			'plugins'                                => [ 'gutenberg/gutenberg.php' ],
			'gutenberg_package_version_from_library' => false,
			'gutenberg_package_version_from_api'     => false,
		] )->get_gutenberg_package_version( 'test-package', '5.0.0' ) );
		$this->assertNotEmpty( $this->get_instance( [
			'plugins'                                => [ 'gutenberg/gutenberg.php' ],
			'gutenberg_package_version_from_library' => false,
		] )->get_gutenberg_package_version( 'wp-data', '5.0.0' ) );
		$this->assertFalse( $this->get_instance( [
			'plugins'                                => [ 'gutenberg/gutenberg.php' ],
			'gutenberg_package_version_from_library' => false,
		] )->get_gutenberg_package_version( 'test-package', '5.0.0' ) );
		$this->assertNotEmpty( $this->get_instance( [
			'plugins' => [ 'gutenberg/gutenberg.php' ],
		] )->get_gutenberg_package_version( 'wp-data', '5.0.0' ) );
	}

}
