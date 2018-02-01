<?php
	/**
	 * 読み込むテンプレートをまとめた配列を取得
	 * 特にカスタマイズしていなければ、template-parts/singular/entry-footer-block の中のファイルを読み込む
	 */
	$templates = ys_get_entry_footer_template();
	foreach ( $templates as $template ){
		get_template_part( $template );
	} ?>