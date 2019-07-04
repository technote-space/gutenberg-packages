<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

use PHPUnit\Framework\TestCase;
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

		return json_encode( [
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
		$this->assertTrue( $this->get_instance( true, false, true )->is_block_editor() );
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
