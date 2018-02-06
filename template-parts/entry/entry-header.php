<?php
	/**
	 * 日付、投稿者、カテゴリー
	 */
	get_template_part( 'template-parts/entry/entry-meta' );
	/**
	 * シェアボタン
	 */
	if( ys_is_active_entry_header_share() ) {
		get_template_part( 'template-parts/sns/share-button' );
	}
	/**
	 * 広告表示
	 */
	ys_the_ad_entry_header(); ?>