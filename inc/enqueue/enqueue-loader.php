<?php
/**
 * Enqueue関連PHPファイルの読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

$enqueue_dir = get_template_directory() . '/inc/enqueue';

require_once $enqueue_dir . '/enqueue-function.php';
require_once $enqueue_dir . '/enqueue-admin.php';
require_once $enqueue_dir . '/enqueue.php';
require_once $enqueue_dir . '/enqueue-optimize.php';
require_once $enqueue_dir . '/enqueue-customizer.php';
require_once $enqueue_dir . '/enqueue-customizer-color.php';
require_once $enqueue_dir . '/enqueue-customizer-custom-header.php';