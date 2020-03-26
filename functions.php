<?php
/**
 * テーマ内で使用する関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 関数群を読み込み
 */
require_once __DIR__ . '/inc/class-ys-loader.php';


add_filter( 'ys_use_ystdb_card', '__return_false' );
