<?php

// Playground CLIのWordPressテスト環境.
if ( file_exists( '/wordpress/wp-load.php' ) ) {
	defined( 'ABSPATH' ) || define( 'ABSPATH', '/wordpress/' );
	defined( 'DB_NAME' ) || define( 'DB_NAME', 'wordpress' );
	defined( 'DB_USER' ) || define( 'DB_USER', 'root' );
	defined( 'DB_PASSWORD' ) || define( 'DB_PASSWORD', '' );
	defined( 'DB_HOST' ) || define( 'DB_HOST', 'localhost' );
	defined( 'DB_CHARSET' ) || define( 'DB_CHARSET', 'utf8' );
	defined( 'DB_COLLATE' ) || define( 'DB_COLLATE', '' );
}

defined( 'WP_TESTS_DOMAIN' ) || define( 'WP_TESTS_DOMAIN', 'example.org' );
defined( 'WP_TESTS_EMAIL' ) || define( 'WP_TESTS_EMAIL', 'admin@example.org' );
defined( 'WP_TESTS_TITLE' ) || define( 'WP_TESTS_TITLE', 'Test Blog' );

defined( 'WP_PHP_BINARY' ) || define( 'WP_PHP_BINARY', 'php' );
