<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use Technote\Tests\Misc\TestGutenbergHelper;
use Technote\Tests\Misc\TestGutenbergPackages;
use /** @noinspection PhpUndefinedClassInspection */
	WP_UnitTestCase;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * @noinspection PhpUndefinedClassInspection
 * GutenbergPackages test case.
 *
 * @mixin TestCase
 */
class GutenbergPackages extends WP_UnitTestCase {

	/**
	 * @param  array  $args
	 * @param  null|bool  $is_admin
	 *
	 * @return TestGutenbergPackages
	 * @throws ReflectionException
	 */
	private function get_instance( $args = [], $is_admin = null ) {
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

		return new TestGutenbergPackages( $args, new TestGutenbergHelper( $args ), $is_admin );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_gutenberg_helper() {
		$this->assertInstanceOf( '\Technote\Tests\Misc\TestGutenbergHelper', $this->get_instance()->get_gutenberg_helper() );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_is_block_editor() {
		set_current_screen( 'post-new' );
		$this->assertFalse(
			$this->get_instance(
				[
					'can_use_block_editor' => true,
					'is_gutenberg_active'  => false,
				],
				false
			)->is_block_editor()
		);
		get_current_screen()->is_block_editor( true );
		$this->assertTrue(
			$this->get_instance(
				[
					'can_use_block_editor' => true,
					'is_gutenberg_active'  => false,
				],
				true
			)->is_block_editor()
		);
		get_current_screen()->is_block_editor( false );
		$this->assertFalse(
			$this->get_instance(
				[
					'can_use_block_editor' => true,
					'is_gutenberg_active'  => false,
				],
				true
			)->is_block_editor()
		);
		$this->assertFalse(
			$this->get_instance(
				[
					'can_use_block_editor' => false,
					'is_gutenberg_active'  => false,
				],
				true
			)->is_block_editor()
		);
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_editor_package_versions() {
		$this->assertEquals( [], $this->get_instance( [ 'can_use_block_editor' => false ] )->get_editor_package_versions() );
		$this->assertNotEmpty(
			$this->get_instance(
				[
					'can_use_block_editor'            => true,
					'is_gutenberg_active'             => true,
					'gutenberg_packages_from_library' => false,
					'gutenberg_packages_from_api'     => false,
				]
			)->get_editor_package_versions()
		);
		$this->assertEmpty(
			$this->get_instance(
				[
					'can_use_block_editor'            => true,
					'is_gutenberg_active'             => true,
					'gutenberg_packages_from_library' => false,
					'gutenberg_packages_from_api'     => false,
					'github_url'                      => 'http://example.com/404',
				],
				null
			)->get_editor_package_versions()
		);
		$this->assertNotEmpty(
			$this->get_instance(
				[
					'can_use_block_editor'            => true,
					'is_gutenberg_active'             => true,
					'gutenberg_packages_from_library' => false,
				],
				null
			)->get_editor_package_versions()
		);
		$this->assertNotEmpty(
			$this->get_instance(
				[
					'can_use_block_editor' => true,
					'is_gutenberg_active'  => true,
				],
				null
			)->get_editor_package_versions()
		);

		$this->assertNotEmpty( $this->get_instance( [
			'can_use_block_editor'                  => true,
			'is_gutenberg_active'                   => false,
			'wp_core_package_versions_from_library' => false,
			'wp_core_package_versions_from_api'     => false,
		] )->get_editor_package_versions() );
		$this->assertNotEmpty( $this->get_instance( [
			'can_use_block_editor'                  => true,
			'is_gutenberg_active'                   => false,
			'wp_core_package_versions_from_library' => false,
			'wp_core_package_versions_from_api'     => false,
			'github_url'                            => 'http://example.com/404',
		] )->get_editor_package_versions() );
		$this->assertNotEmpty( $this->get_instance( [
			'can_use_block_editor'                  => true,
			'is_gutenberg_active'                   => false,
			'wp_core_package_versions_from_library' => false,
		] )->get_editor_package_versions() );
		$this->assertNotEmpty( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_versions() );

		$instance = $this->get_instance( [
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
		$instance = $this->get_instance( [
			'cache_exists'                    => false,
			'can_use_block_editor'            => true,
			'is_gutenberg_active'             => true,
			'gutenberg_packages_from_library' => false,
			'gutenberg_packages_from_api'     => false,
			'github_url'                      => 'http://example.com/404',
			'cache_key'                       => 'abc',
		] );
		/** @var TestGutenbergHelper $helper */
		$helper = $instance->get_gutenberg_helper();

		$this->assertEmpty( $instance->get_gutenberg_package_versions() );

		$helper->reset_args( [
			'cache_exists'         => false,
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => true,
			'cache_key'            => 'abc',
		] );
		$this->assertEmpty( $instance->get_gutenberg_package_versions() );

		$helper->reset_args( [
			'cache_exists'         => false,
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => true,
			'cache_key'            => 'xyz',
		] );
		$this->assertNotEmpty( $instance->get_gutenberg_package_versions() );

		$helper->reset_args( [
			'cache_exists'                    => false,
			'can_use_block_editor'            => true,
			'is_gutenberg_active'             => true,
			'gutenberg_packages_from_library' => false,
			'gutenberg_packages_from_api'     => false,
			'github_url'                      => 'http://example.com/404',
			'cache_key'                       => 'xyz',
		] );
		$this->assertNotEmpty( $instance->get_gutenberg_package_versions() );

		$helper->reset_args( [
			'cache_exists'                    => false,
			'can_use_block_editor'            => true,
			'is_gutenberg_active'             => true,
			'gutenberg_packages_from_library' => false,
			'gutenberg_packages_from_api'     => false,
			'github_url'                      => 'http://example.com/404',
			'cache_key'                       => 'abc',
		] );
		$this->assertEmpty( $instance->get_gutenberg_package_versions() );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_is_support_editor_package() {
		global $wp_version;
		$this->assertTrue( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'wp-editor' ) );
		$this->assertTrue( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'editor' ) );
		$this->assertTrue( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'wp-components' ) );
		$this->assertEquals(
			version_compare( '5.2', $wp_version, '<=' ),
			$this->get_instance( [
				'can_use_block_editor' => true,
				'is_gutenberg_active'  => false,
			] )->is_support_editor_package( 'wp-block-editor' )
		);
		$this->assertFalse( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->is_support_editor_package( 'test-package' ) );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_editor_package_version() {
		$this->assertNotEmpty( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'wp-editor' ) );
		$this->assertNotEmpty( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'editor' ) );
		$this->assertNotEmpty( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'wp-components' ) );
		$this->assertEmpty( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_editor_package_version( 'test-package' ) );
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_filter_packages() {
		$this->assertEquals(
			[
				'wp-editor',
				'wp-components',
				'wp-data',
			],
			$this->get_instance( [
				'can_use_block_editor' => true,
				'is_gutenberg_active'  => true,
			] )->filter_packages( [
				'editor',
				'wp-editor',
				'test-package',
				'components',
				'wp-data',
				'wp-data',
			] )
		);
		$this->assertEquals(
			[
				'wp-editor',
				'wp-components',
				'wp-data',
				'lodash',
			],
			$this->get_instance( [
				'can_use_block_editor' => true,
				'is_gutenberg_active'  => true,
			] )->filter_packages(
				[
					'editor',
					'wp-editor',
					'test-package',
					'components',
					'wp-data',
					'wp-data',
				],
				[
					'lodash',
				]
			)
		);
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_fill_package_versions() {
		$instance = $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => true,
		] );
		$versions = $instance->get_editor_package_versions();
		$this->assertEquals(
			[
				'wp-editor'     => $versions['wp-editor'],
				'wp-components' => $versions['wp-components'],
				'wp-data'       => $versions['wp-data'],
			],
			$instance->fill_package_versions( [
				'editor',
				'wp-editor',
				'test-package',
				'components',
				'wp-data',
				'wp-data',
			] )
		);
	}

	/**
	 * @throws ReflectionException
	 */
	public function test_get_gutenberg_version() {
		$this->assertEmpty( $this->get_instance( [
			'can_use_block_editor' => true,
			'is_gutenberg_active'  => false,
		] )->get_gutenberg_version() );
	}

}
