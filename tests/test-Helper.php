<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote\Tests;

use PHPUnit\Framework\TestCase;
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
 * Helper test case.
 *
 * @mixin TestCase
 */
class Helper extends WP_UnitTestCase {

	private function get_instance( $is_multisite = null, $cache_expiration = null ) {
		return new TestHelper( [
			'is_multisite'     => $is_multisite,
			'cache_expiration' => $cache_expiration,
		] );
	}

	public function test_get_collection() {
		$this->assertInstanceOf( '\Technote\Collection', $this->get_instance()->get_collection( [] ) );
	}

	public function test_dirlist() {
		$count = 0;
		foreach ( $this->get_instance()->dirlist( dirname( __DIR__ ) ) as $item ) {
			$this->assertTrue( is_dir( $item ) );
			$count++;
		}
		$this->assertNotEmpty( $count );
	}

	public function test_starts_with() {
		$this->assertTrue( $this->get_instance()->starts_with( 'abc', 'a' ) );
		$this->assertTrue( $this->get_instance()->starts_with( 'abc', 'abc' ) );
		$this->assertFalse( $this->get_instance()->starts_with( 'abc', '' ) );
		$this->assertFalse( $this->get_instance()->starts_with( 'abc', 'c' ) );
		$this->assertFalse( $this->get_instance()->starts_with( 'abc', 'xyz' ) );
	}

	public function test_get_wp_version() {
		global $wp_version;
		$this->assertEquals( $wp_version, $this->get_instance()->get_wp_version() );
	}

	public function test_compare_wp_version() {
		global $wp_version;
		$tmp = $wp_version;

		$wp_version = '4.9'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertTrue( $this->get_instance()->compare_wp_version( '5.0', '<' ) );

		$wp_version = '5.0'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertTrue( $this->get_instance()->compare_wp_version( '5.0', '>=' ) );

		$wp_version = '5.1'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertTrue( $this->get_instance()->compare_wp_version( '5.0', '>' ) );

		$wp_version = '4.9'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertFalse( $this->get_instance()->compare_wp_version( '5.0', '>=' ) );

		$wp_version = '5.0'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertFalse( $this->get_instance()->compare_wp_version( '5.0', '<' ) );

		$wp_version = '5.1'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$this->assertFalse( $this->get_instance()->compare_wp_version( '5.0', '<=' ) );

		$wp_version = $tmp; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	public function test_is_plugin_active() {
		$tmp1 = get_option( 'active_plugins', [] );
		$tmp2 = get_site_option( 'active_sitewide_plugins', [] );
		update_option( 'active_plugins', [
			'a/a.php',
			'b/b.php',
			'c/c.php',
		] );
		update_site_option( 'active_sitewide_plugins', [
			'b/b.php' => 'b/b.php',
			'd/d.php' => 'd/d.php',
		] );

		$this->assertTrue( $this->get_instance()->is_plugin_active( 'a/a.php' ) );
		$this->assertTrue( $this->get_instance()->is_plugin_active( 'b/b.php' ) );
		$this->assertTrue( $this->get_instance( true )->is_plugin_active( 'd/d.php' ) );
		$this->assertTrue( $this->get_instance( true, 100 )->is_plugin_active( 'd/d.php' ) );
		$this->assertFalse( $this->get_instance( false )->is_plugin_active( 'd/d.php' ) );
		$this->assertFalse( $this->get_instance( false, 100 )->is_plugin_active( 'd/d.php' ) );
		$this->assertFalse( $this->get_instance()->is_plugin_active( 'd/d' ) );
		$this->assertFalse( $this->get_instance()->is_plugin_active( '' ) );

		update_option( 'active_plugins', $tmp1 );
		update_site_option( 'active_sitewide_plugins', $tmp2 );
	}

	public function test_get_active_plugins() {
		$tmp1 = get_option( 'active_plugins', [] );
		$tmp2 = get_site_option( 'active_sitewide_plugins', [] );
		update_option( 'active_plugins', [
			'a/a.php',
			'b/b.php',
			'c/c.php',
		] );
		update_site_option( 'active_sitewide_plugins', [
			'b/b.php' => 'b/b.php',
			'd/d.php' => 'd/d.php',
		] );

		$this->assertEquals( [
			'a/a.php',
			'b/b.php',
			'c/c.php',
			'd/d.php',
		], $this->get_instance( true )->get_active_plugins() );

		$this->assertEquals( [
			'a/a.php',
			'b/b.php',
			'c/c.php',
		], $this->get_instance( false )->get_active_plugins() );

		update_option( 'active_plugins', $tmp1 );
		update_site_option( 'active_sitewide_plugins', $tmp2 );
	}

	public function test_get_release_tag() {
		$this->assertEquals( '5.0', $this->get_instance()->get_release_tag( '5.0.1' ) );
		$this->assertEquals( '5.1', $this->get_instance()->get_release_tag( 'v5.1' ) );
		$this->assertEquals( '6.0', $this->get_instance()->get_release_tag( '6.0.0' ) );
		$this->assertEquals( false, $this->get_instance()->get_release_tag( '123' ) );
		$this->assertEquals( false, $this->get_instance()->get_release_tag( '' ) );
	}

	public function test_get_remote() {
		$this->assertNotEmpty( $this->get_instance()->get_remote( 'https://api.wp-framework.dev/api/v1/gutenberg/tags.json' ) );
		$this->assertFalse( $this->get_instance()->get_remote( 'http://example.com/404' ) );
	}
}
