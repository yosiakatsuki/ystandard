<?php
/**
 * そのう消える予定の関数群
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事下ウィジェットを表示するか
 *
 * @deprecated ys_is_active_after_content_widgetを使う.
 */
function ys_is_active_entry_footer_widget() {
	return ys_is_active_after_content_widget();
}