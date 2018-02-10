<?php
/**
 * AMP footer関連
 */
/**
 * AMPフォーマットフッター処理
 */
function ys_amp_footer() {
	do_action( 'ys_amp_footer' );
}

/**
 * フッターで処理する内容を登録
 */
add_action( 'ys_amp_footer', 'ys_the_json_ld' );