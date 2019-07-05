<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

use /** @noinspection PhpUndefinedClassInspection */
	PHPUnit\Framework\TestCase;
use Technote\Helper;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// @codeCoverageIgnoreEnd

require_once dirname( __FILE__ ) . '/GutenbergPackages/GutenbergHelper.php';

/**
 * @noinspection PhpUndefinedClassInspection
 * GutenbergPackages test case.
 *
 * @mixin TestCase
 */
class GutenbergPackages extends WP_UnitTestCase {

	private function get_instance( $can_use_block_editor = true, $is_gutenberg_active = false, $is_admin = null, $github_url = null ) {
		return new \Technote\GutenbergPackages( new Helper(), new TestGutenbergHelper( $can_use_block_editor, $is_gutenberg_active, $github_url ), $is_admin );
	}

	public function test_is_block_editor() {
		set_current_screen( 'post-new' );
		$this->assertFalse( $this->get_instance( true, false, false )->is_block_editor() );
		get_current_screen()->is_block_editor( true );
		$this->assertTrue( $this->get_instance( true, false, true )->is_block_editor() );
		get_current_screen()->is_block_editor( false );
		$this->assertFalse( $this->get_instance( true, false, true )->is_block_editor() );
		$this->assertFalse( $this->get_instance( false, false, true )->is_block_editor() );
	}

	public function test_get_editor_package_versions() {
		$this->assertEquals( [], $this->get_instance( false )->get_editor_package_versions() );
		$this->assertNotEmpty( $this->get_instance( true, true )->get_editor_package_versions() );
		$this->assertEmpty( $this->get_instance( true, true, null, 'http://example.com/404' )->get_editor_package_versions() );
		$this->assertNotEmpty( $this->get_instance( true, false )->get_editor_package_versions() );

		$instance = $this->get_instance( true, true );
		$this->assertNotEmpty( $instance->get_editor_package_versions() );
		$this->assertNotEmpty( $instance->get_editor_package_versions() );
	}

	public function test_is_support_editor_package() {
		global $wp_version;
		$this->assertTrue( $this->get_instance( true, false )->is_support_editor_package( 'wp-editor' ) );
		$this->assertTrue( $this->get_instance( true, false )->is_support_editor_package( 'wp-components' ) );
		$this->assertEquals( version_compare( '5.2', $wp_version, '<=' ), $this->get_instance( true, false )->is_support_editor_package( 'wp-block-editor' ) );
		$this->assertFalse( $this->get_instance( true, false )->is_support_editor_package( 'test' ) );
	}

	public function test_get_editor_package_version() {
		$this->assertNotEmpty( $this->get_instance( true, false )->get_editor_package_version( 'wp-editor' ) );
		$this->assertNotEmpty( $this->get_instance( true, false )->get_editor_package_version( 'wp-components' ) );
		$this->assertEmpty( $this->get_instance( true, false )->get_editor_package_version( 'test' ) );
	}
}
