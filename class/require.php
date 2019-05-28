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
require_once $class_dir . 'class-ys-enqueue.php';
require_once $class_dir . 'class-ys-cache.php';
require_once $class_dir . 'class-ys-walker-global-nav-menu.php';
/**
 * ウィジェットクラス
 */
$class_dir = get_template_directory() . '/class/widgets/';
require_once $class_dir . 'class-ys-widget-utility.php';
require_once $class_dir . 'class-ys-widget-base.php';
require_once $class_dir . 'class-ys-widget-post-list.php';
require_once $class_dir . 'class-ys-widget-advertisement.php';
require_once $class_dir . 'class-ys-ranking-widget.php';
require_once $class_dir . 'class-ys-taxonomy-posts-widget.php';
require_once $class_dir . 'class-ys-widget-custom-html.php';
require_once $class_dir . 'class-ys-widget-text.php';
require_once $class_dir . 'class-ys-widget-author-box.php';
require_once $class_dir . 'class-ys-widget-share-button.php';
/**
 * ショートコードクラス
 */
$class_dir = get_template_directory() . '/class/shortcode/';
require_once $class_dir . 'class-ys-shortcode-base.php';
require_once $class_dir . 'class-ys-shortcode-text.php';
require_once $class_dir . 'class-ys-shortcode-share-button.php';
require_once $class_dir . 'class-ys-shortcode-advertisement.php';
require_once $class_dir . 'class-ys-shortcode-author-box.php';
require_once $class_dir . 'class-ys-shortcode-get-posts.php';
require_once $class_dir . 'class-ys-shortcode-post-ranking.php';
require_once $class_dir . 'class-ys-shortcode-recent-posts.php';

/**
 * テーマカスタマイザー
 */
$class_dir = get_template_directory() . '/class/customizer/';
require_once $class_dir . '/class-ys-customize-image-label-radio-control.php';
require_once $class_dir . '/class-ys-customizer.php';