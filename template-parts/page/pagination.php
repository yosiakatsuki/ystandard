<?php
/**
 * 固定ページ用ページネーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * 実態は下記テンプレート
 * template-parts/singular/pagination.php
 * 固定ページだけカスタマイズする場合はこのテンプレートを子テーマにコピーして書き換えてください。
 */
ys_get_template_part( 'template-parts/singular/pagination' );
