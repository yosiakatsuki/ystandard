<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Ystandard
 */

define( 'DIR_TEST_DATA', __DIR__ . '/data' );
define(
	'WP_TESTS_PHPUNIT_POLYFILLS_PATH',
	__DIR__ . '/../vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php'
);

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	throw new Exception( "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

function _register_theme() {

	$theme_dir     = dirname( __DIR__ );
	$current_theme = basename( $theme_dir );

	register_theme_directory( dirname( $theme_dir ) );
	search_theme_directories();

	add_filter( 'pre_option_template', function () use ( $current_theme ) {
		return $current_theme;
	} );
	add_filter( 'pre_option_stylesheet', function () use ( $current_theme ) {
		return $current_theme;
	} );
}

tests_add_filter( 'muplugins_loaded', '_register_theme' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
