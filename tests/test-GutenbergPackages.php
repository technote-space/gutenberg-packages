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

require_once dirname( __FILE__ ) . '/misc/GutenbergPackages.php';
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
	 * @throws ReflectionException
	 */
	public static function setUpBeforeClass() {
		static::reset();
	}

	/**
	 * @throws ReflectionException
	 */
	public static function tearDownAfterClass() {
		static::reset();
	}

	/**
	 * @throws ReflectionException
	 */
	private static function reset() {
		static::get_instance();
	}

	/**
	 * @param array $args
	 * @param null|bool $is_admin
	 * @param bool $delete_cache
	 *
	 * @return TestGutenbergPackages
	 * @throws ReflectionException
	 */
	private static function get_instance( $args = [], $is_admin = null, $delete_cache = true ) {
		$args['gutenberg_absolute_path']   = '/tmp/wordpress/wp-content/plugins/gutenberg/gutenberg.php';
		$args['gutenberg_package_version'] = function ( $package, $original ) {
			if ( 'wp-rich-text' === $package ) {
				return false;
			}

			if ( 'wp-hooks' === $package ) {
				return $original;
			}

			return wp_json_encode( [
				'version' => '1.2.3',
			] );
		};

		$instance = new TestGutenbergPackages( $args, new TestGutenbergHelper( $args ), $is_admin );
		if ( $delete_cache ) {
			delete_transient( $instance->get_gutenberg_helper()->get_cache_key() );
		}

		return $instance;
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_gutenberg_helper() {
		$this->assertInstanceOf( 'TestGutenbergHelper', static::get_instance()->get_gutenberg_helper() );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_is_block_editor() {
		set_current_screen( 'post-new' );
		$this->assertFalse( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		], false )->is_block_editor() );
		get_current_screen()->is_block_editor( true );
		$this->assertTrue( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		], true )->is_block_editor() );
		get_current_screen()->is_block_editor( false );
		$this->assertFalse( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		], true )->is_block_editor() );
		$this->assertFalse( static::get_instance( [
			'can_use_block_editor' => false,
			'is_gutenberg_active'  => false,
		], true )->is_block_editor() );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_editor_package_versions() {
		$this->assertEquals( [], static::get_instance( [ 'can_use_block_editor' => false ] )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor'            => true,
			'is_gutenberg_active'             => true,
			'gutenberg_packages_from_library' => false,
			'gutenberg_packages_from_api'     => false,
		] )->get_editor_package_versions() );
		$this->assertEmpty( static::get_instance( [
			'can_use_block_editor'            => true,
			'is_gutenberg_active'             => true,
			'gutenberg_packages_from_library' => false,
			'gutenberg_packages_from_api'     => false,
			'github_url'                      => 'http://example.com/404',
		], null )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor'            => true,
			'is_gutenberg_active'             => true,
			'gutenberg_packages_from_library' => false,
		], null )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => true,
		], null )->get_editor_package_versions() );

		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor'                  => true,
			'is_gutenberg_active'                   => false,
			'wp_core_package_versions_from_library' => false,
			'wp_core_package_versions_from_api'     => false,
		] )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor'                  => true,
			'is_gutenberg_active'                   => false,
			'wp_core_package_versions_from_library' => false,
			'wp_core_package_versions_from_api'     => false,
			'github_url'                            => 'http://example.com/404',
		] )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor'                  => true,
			'is_gutenberg_active'                   => false,
			'wp_core_package_versions_from_library' => false,
		] )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_versions() );

		$instance = static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => true,
		] );
		$this->assertNotEmpty( $instance->get_editor_package_versions() );
		$this->assertNotEmpty( $instance->get_editor_package_versions() );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_cache() {
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => true,
		] )->get_editor_package_versions() );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => true,
			'github_url'           => 'http://example.com/404',
		], null, false )->get_editor_package_versions() );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_is_support_editor_package() {
		global $wp_version;
		$this->assertTrue( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'wp-editor' ) );
		$this->assertTrue( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'editor' ) );
		$this->assertTrue( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'wp-components' ) );
		$this->assertEquals( version_compare( '5.2', $wp_version, '<=' ), static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'wp-block-editor' ) );
		$this->assertFalse( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'test-package' ) );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_editor_package_version() {
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'wp-editor' ) );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'editor' ) );
		$this->assertNotEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'wp-components' ) );
		$this->assertEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'test-package' ) );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_gutenberg_version() {
		$this->assertEmpty( static::get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_gutenberg_version() );
	}
}
