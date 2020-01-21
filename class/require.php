<?php
/**
 * クラス読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
$class_dir = get_template_directory() . '/class/';
require_once $class_dir . 'class-ys-cache.php';

/**
 * ウィジェットクラス
 */
$class_dir = get_template_directory() . '/class/widgets/';
require_once $class_dir . 'class-ys-widget-utility.php';
require_once $class_dir . 'class-ys-widget-base.php';
require_once $class_dir . 'class-ys-widget-get-posts.php';
require_once $class_dir . 'class-ys-widget-advertisement.php';
require_once $class_dir . 'class-ys-widget-post-ranking.php';
require_once $class_dir . 'class-ys-widget-recent-posts.php';
require_once $class_dir . 'class-ys-widget-custom-html.php';
require_once $class_dir . 'class-ys-widget-text.php';
require_once $class_dir . 'class-ys-widget-author-box.php';
require_once $class_dir . 'class-ys-widget-share-button.php';
require_once $class_dir . 'class-ys-widget-post-taxonomy.php';
/**
 * ショートコードクラス
 */
$class_dir = get_template_directory() . '/class/shortcode/';
require_once $class_dir . 'class-ys-shortcode-base.php';
require_once $class_dir . 'class-ys-shortcode-text.php';
require_once $class_dir . 'class-ys-shortcode-share-button.php';
require_once $class_dir . 'class-ys-shortcode-author-box.php';
require_once $class_dir . 'class-ys-shortcode-author-list.php';
require_once $class_dir . 'class-ys-shortcode-get-posts.php';
require_once $class_dir . 'class-ys-shortcode-post-ranking.php';
require_once $class_dir . 'class-ys-shortcode-recent-posts.php';
require_once $class_dir . 'class-ys-shortcode-post-paging.php';
require_once $class_dir . 'class-ys-shortcode-post-taxonomy.php';
require_once $class_dir . 'class-ys-shortcode-follow-box.php';

/**
 * ワーカー
 */
$class_dir = get_template_directory() . '/class/walker/';
require_once $class_dir . 'class-ys-walker-global-nav-menu.php';
require_once $class_dir . 'class-ys-walker-mobile-footer-menu.php';