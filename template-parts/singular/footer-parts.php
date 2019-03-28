<?php
/**
 * 記事フッター部分テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * SNSシェアボタン
 */
if ( ys_is_active_sns_share_on_footer() ) {
	ys_the_sns_share_button();
}