<?php
$plugins_dir = dirname( dirname( __FILE__ ) ) . '/.plugin';
$tmp         = getenv( 'TMPDIR' );
if ( empty( $tmp ) ) {
	$tmp = '/tmp';
}
$core = getenv( 'WP_CORE_DIR' );
if ( empty( $core ) ) {
	$core = $tmp . '/wordpress/';
}
$test_plugins_dir = $core . 'wp-content/plugins/';

if ( ! file_exists( "{$test_plugins_dir}/gutenberg" ) ) {
	if ( ! file_exists( $test_plugins_dir ) ) {
		mkdir( $test_plugins_dir, 0755, true );
	}
	symlink( "{$plugins_dir}/gutenberg", "{$test_plugins_dir}/gutenberg" );
}
