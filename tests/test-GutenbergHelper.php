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
		$this->assertEquals(
			[],
			$this->get_instance( [
				'plugins' => [ 'gutenberg/gutenberg.php' ],
			] )->get_gutenberg_packages( '0.0.0' )
		);
		$this->assertEquals(
			[
				0  => 'wp-a11y',
				1  => 'wp-annotations',
				2  => 'wp-api-fetch',
				3  => 'wp-autop',
				4  => 'wp-blob',
				5  => 'wp-block-library',
				6  => 'wp-block-serialization-default-parser',
				7  => 'wp-block-serialization-spec-parser',
				8  => 'wp-blocks',
				9  => 'wp-components',
				10 => 'wp-compose',
				11 => 'wp-core-data',
				12 => 'wp-data',
				13 => 'wp-date',
				14 => 'wp-deprecated',
				15 => 'wp-dom',
				16 => 'wp-dom-ready',
				17 => 'wp-editor',
				18 => 'wp-edit-post',
				19 => 'wp-element',
				20 => 'wp-escape-html',
				21 => 'wp-format-library',
				22 => 'wp-hooks',
				23 => 'wp-html-entities',
				24 => 'wp-i18n',
				25 => 'wp-is-shallow-equal',
				26 => 'wp-keycodes',
				27 => 'wp-list-reusable-blocks',
				28 => 'wp-notices',
				29 => 'wp-nux',
				30 => 'wp-plugins',
				31 => 'wp-priority-queue',
				32 => 'wp-redux-routine',
				33 => 'wp-rich-text',
				34 => 'wp-shortcode',
				35 => 'wp-token-list',
				36 => 'wp-url',
				37 => 'wp-viewport',
				38 => 'wp-wordcount',
			],
			$this->get_instance( [
				'plugins' => [ 'gutenberg/gutenberg.php' ],
			] )->get_gutenberg_packages( '5.0.0' )
		);
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
