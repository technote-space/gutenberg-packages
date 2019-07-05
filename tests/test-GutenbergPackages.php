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

require_once dirname( __FILE__ ) . '/misc/GutenbergHelper.php';

/**
 * @noinspection PhpUndefinedClassInspection
 * GutenbergPackages test case.
 *
 * @mixin TestCase
 */
class GutenbergPackages extends WP_UnitTestCase {

	/**
	 * @SuppressWarnings(StaticAccess)
	 */
	public static function setUpBeforeClass() {
		static::reset();
	}

	public static function tearDownAfterClass() {
		static::reset();
	}

	private static function reset() {
		static::get_instance();
	}

	private static function get_instance( $can_use_block_editor = true, $is_gutenberg_active = false, $is_admin = null, $github_url = null, $delete_cache = true ) {
		$instance = new \Technote\GutenbergPackages( new TestGutenbergHelper( $can_use_block_editor, $is_gutenberg_active, $github_url ), $is_admin );
		if ( $delete_cache ) {
			delete_transient( $instance->get_gutenberg_helper()->get_cache_key() );
		}

		return $instance;
	}

	public function test_get_gutenberg_helper() {
		$this->assertInstanceOf( 'TestGutenbergHelper', static::get_instance()->get_gutenberg_helper() );
	}

	public function test_is_block_editor() {
		set_current_screen( 'post-new' );
		$this->assertFalse( static::get_instance( true, false, false )->is_block_editor() );
		get_current_screen()->is_block_editor( true );
		$this->assertTrue( static::get_instance( true, false, true )->is_block_editor() );
		get_current_screen()->is_block_editor( false );
		$this->assertFalse( static::get_instance( true, false, true )->is_block_editor() );
		$this->assertFalse( static::get_instance( false, false, true )->is_block_editor() );
	}

	public function test_get_editor_package_versions() {
		$this->assertEquals( [], static::get_instance( false )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( true, true )->get_editor_package_versions() );
		$this->assertEmpty( static::get_instance( true, true, null, 'http://example.com/404' )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( true, false )->get_editor_package_versions() );

		$instance = static::get_instance( true, true );
		$this->assertNotEmpty( $instance->get_editor_package_versions() );
		$this->assertNotEmpty( $instance->get_editor_package_versions() );
	}

	public function test_cache() {
		$this->assertNotEmpty( static::get_instance( true, true )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( true, true, null, 'http://example.com/404', false )->get_editor_package_versions() );
	}

	public function test_is_support_editor_package() {
		global $wp_version;
		$this->assertTrue( static::get_instance( true, false )->is_support_editor_package( 'wp-editor' ) );
		$this->assertTrue( static::get_instance( true, false )->is_support_editor_package( 'wp-components' ) );
		$this->assertEquals( version_compare( '5.2', $wp_version, '<=' ), static::get_instance( true, false )->is_support_editor_package( 'wp-block-editor' ) );
		$this->assertFalse( static::get_instance( true, false )->is_support_editor_package( 'test' ) );
	}

	public function test_get_editor_package_version() {
		$this->assertNotEmpty( static::get_instance( true, false )->get_editor_package_version( 'wp-editor' ) );
		$this->assertNotEmpty( static::get_instance( true, false )->get_editor_package_version( 'wp-components' ) );
		$this->assertEmpty( static::get_instance( true, false )->get_editor_package_version( 'test' ) );
	}

	public function test_get_gutenberg_version() {
		$this->assertEmpty( static::get_instance( true, false )->get_gutenberg_version() );
	}
}
