<?php
/**
 * ショートコード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

$shortcode_dir = get_template_directory() . '/inc/shortcode/';

/**
 * 汎用テキスト
 */
require_once $shortcode_dir . 'shortcode-text.php';

/**
 * 投稿者表示ショートコード
 */
require_once $shortcode_dir . 'shortcode-author.php';
/**
 * 投稿者一覧表示ショートコード
 */
require_once $shortcode_dir . 'shortcode-author-list.php';
/**
 * 広告表示ショートコード
 */
require_once $shortcode_dir . 'shortcode-ad.php';
/**
 * ランキングショートコード
 */
require_once $shortcode_dir . 'shortcode-post-ranking.php';
/**
 * タクソノミー絞り込み記事一覧ショートコード
 */
require_once $shortcode_dir . 'shortcode-taxonomy-posts.php';
