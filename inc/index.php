<?php
/**
 * /inc内の各ファイル読み込み.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

// ファイル読み込み前アクション.
do_action( 'ystd_inc_before_load' );

$exclude_dirs = [
	'utils',
	'config',
	'option',
	'template-function',
];

// 先に読み込む系.
require_once __DIR__ . '/utils/index.php';
require_once __DIR__ . '/config/index.php';
require_once __DIR__ . '/option/index.php';


// 各フォルダのindex.php読み込み.
$directories = scandir( __DIR__ );
foreach ( $directories as $directory ) {
	// ディレクトリかつindex.phpが存在するかチェック.
	if ( '.' === $directory || '..' === $directory || ! is_dir( __DIR__ . '/' . $directory ) ) {
		continue;
	}

	// $exclude_dirs に含まれるフォルダは除外.
	if ( in_array( $directory, $exclude_dirs, true ) ) {
		continue;
	}

	$index_file = __DIR__ . '/' . $directory . '/index.php';
	// 読み込みファイルのフィルター.
	$index_file = apply_filters( 'ystd_inc_load_file', $index_file, $directory );
	if ( file_exists( $index_file ) ) {
		require_once $index_file;
	}
}

// template-function は最後.
require_once __DIR__ . '/template-function/index.php';

// ファイル読み込み後アクション.
do_action( 'ystd_inc_after_load' );
