<?php
/**
 * AMP関連ファイルのロード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

$dir = get_template_directory() . '/inc/amp';

require_once $dir . '/amp-convert.php';
require_once $dir . '/amp-filter.php';
require_once $dir . '/amp-head.php';
require_once $dir . '/amp-footer.php';
require_once $dir . '/amp-google-analytics.php';