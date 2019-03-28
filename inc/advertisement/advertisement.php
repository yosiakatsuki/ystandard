<?php
/**
 * 広告
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_ad_block_html' ) ) {
	/**
	 * 広告コードのhtml整形
	 *
	 * @param string  $ad    広告.
	 * @param string  $key   広告作成キー.
	 * @param boolean $label ラベルの表示有無.
	 *
	 * @return string
	 */
	function ys_get_ad_block_html( $ad, $key = '', $label = true ) {
		$html = '';
		$ad   = apply_filters( 'ys_advertisement_content', $ad, $key );
		$ad   = ys_fix_ad_previw_error( $ad );
		if ( '' !== $ad && ! is_feed() ) {
			/**
			 * ラベルの設定
			 */
			$label_text = '';
			if ( $label ) {
				$label_text = 'スポンサーリンク';
			}
			$label_text = apply_filters( 'ys_ad_label_text', $label_text, $key );
			if ( '' !== $label_text ) {
				$label_text = sprintf( '<div class="ad__label">%s</div>', $label_text );
			}
			/**
			 * HTMLの作成
			 */
			$html = sprintf(
				'<aside class="ad__container">
					%s
					<div class="ad__content">%s</div>
				</aside>',
				$label_text,
				$ad
			);
		}

		return apply_filters( 'ys_get_ad_block_html', $html );
	}
}

/**
 * 広告設定の取得
 *
 * @param string  $key_pc  PC広告の設定キー.
 * @param string  $key_sp  SP広告の設定キー.
 * @param string  $key_amp AMP広告の設定キー.
 * @param string  $filter  フィルターフック.
 * @param boolean $label   ラベルの表示有無.
 *
 * @return string
 */
function ys_get_ad( $key_pc, $key_sp = '', $key_amp = '', $filter = '', $label = true ) {
	$key = $key_pc;
	if ( ys_is_mobile() && '' !== $key_sp ) {
		$key = $key_sp;
	}
	if ( ys_is_amp() && '' !== $key_amp ) {
		$key = $key_amp;
	}
	$ad = ys_get_ad_block_html( ys_get_option( $key ), $key, $label );
	if ( $filter ) {
		$ad = apply_filters( $filter, $ad, $key, $label );
	}

	return $ad;
}

/**
 * タイトル上広告取得
 *
 * @return string
 */
function ys_get_ad_before_entry_title() {
	return ys_get_ad(
		'ys_advertisement_before_title',
		'ys_advertisement_before_title_sp',
		'ys_amp_advertisement_before_title',
		'ys_get_ad_before_entry_title',
		false
	);
}

/**
 * タイトル上広告の出力
 */
function ys_the_ad_before_entry_title() {
	if ( ys_is_active_advertisement() ) {
		echo ys_get_ad_before_entry_title();
	}
}

add_action( 'ys_before_entry_title', 'ys_the_ad_before_entry_title' );
/**
 * タイトル下広告取得
 *
 * @return string
 */
function ys_get_ad_after_entry_title() {
	return ys_get_ad(
		'ys_advertisement_after_title',
		'ys_advertisement_after_title_sp',
		'ys_amp_advertisement_after_title',
		'ys_get_ad_after_entry_title',
		false
	);
}

/**
 * タイトル下広告の出力
 */
function ys_the_ad_after_entry_title() {
	if ( ys_is_active_advertisement() ) {
		echo ys_get_ad_after_entry_title();
	}
}

add_action( 'ys_after_entry_title', 'ys_the_ad_after_entry_title' );


if ( ! function_exists( 'ys_get_ad_entry_header' ) ) {
	/**
	 * 記事上広告の取得
	 */
	function ys_get_ad_entry_header() {
		return ys_get_ad(
			'ys_advertisement_before_content',
			'ys_advertisement_before_content_sp',
			'ys_amp_advertisement_before_content',
			'ys_get_ad_entry_header'
		);
	}
}
/**
 * 記事上部広告の出力
 */
function ys_the_ad_entry_header() {
	if ( ys_is_active_advertisement() ) {
		echo ys_get_ad_entry_header();
	}
}

if ( ! function_exists( 'ys_get_ad_more_tag' ) ) {
	/**
	 * Moreタグ広告の取得
	 */
	function ys_get_ad_more_tag() {
		return ys_get_ad(
			'ys_advertisement_replace_more',
			'ys_advertisement_replace_more_sp',
			'ys_amp_advertisement_replace_more',
			'ys_get_ad_more_tag'
		);
	}
}
/**
 * Moreタグ広告の出力
 */
function ys_the_ad_more_tag() {
	if ( ys_is_active_advertisement() ) {
		echo ys_get_ad_more_tag();
	}
}

if ( ! function_exists( 'ys_get_ad_entry_footer' ) ) {
	/**
	 * 記事下広告の取得
	 */
	function ys_get_ad_entry_footer() {

		$key_left  = 'ys_advertisement_under_content_left';
		$key_right = 'ys_advertisement_under_content_right';

		if ( ys_is_mobile() ) {
			$key_left  = 'ys_advertisement_under_content_sp';
			$key_right = '';
		}
		if ( ys_is_amp() ) {
			$key_left  = 'ys_amp_advertisement_under_content';
			$key_right = '';
		}

		$ad       = '';
		$ad_left  = ys_get_option( $key_left );
		$ad_right = '';
		if ( '' !== $key_right ) {
			$ad_right = ys_get_option( $key_right );
		}
		if ( '' !== $ad_left && '' !== $ad_right ) {
			$ad = sprintf(
				'<div class="ad__double row">
					<div class="ad__left col__2--tb">%s</div>
					<div class="ad__right col__2--tb">%s</div>
				</div>',
				$ad_left,
				$ad_right
			);
		} else {
			if ( '' !== $ad_right ) {
				$ad = $ad_right;
			}
			if ( '' !== $ad_left ) {
				$ad = $ad_left;
			}
		}

		return apply_filters( 'ys_get_ad_entry_footer', ys_get_ad_block_html( $ad ) );
	}
}
/**
 * 記事下広告の出力
 */
function ys_the_ad_entry_footer() {
	if ( ys_is_active_advertisement() ) {
		echo ys_get_ad_entry_footer();
	}
}

/**
 * インフィード広告
 */
function ys_get_ad_infeed() {
	if ( ys_is_mobile() ) {
		$ad = ys_get_option( 'ys_advertisement_infeed_sp' );
	} else {
		$ad = ys_get_option( 'ys_advertisement_infeed_pc' );
	}
	$ad = ys_fix_ad_previw_error( $ad );

	return apply_filters( 'ys_get_ad_infeed', $ad );
}

/**
 * インフィード広告の表示
 */
function ys_the_ad_infeed() {
	echo ys_get_ad_infeed();
}

/**
 * インフィード広告の表示
 * ys_get_template_ad_infeed
 *
 */
function ys_get_template_infeed_ad() {
	if ( ys_is_mobile() ) {
		$step  = ys_get_option( 'ys_advertisement_infeed_sp_step' );
		$limit = ys_get_option( 'ys_advertisement_infeed_sp_limit' );
	} else {
		$step  = ys_get_option( 'ys_advertisement_infeed_pc_step' );
		$limit = ys_get_option( 'ys_advertisement_infeed_pc_limit' );
	}
	global $wp_query;
	$num = $wp_query->current_post + 1;
	if ( 0 == ( $num % $step ) && $limit >= ( $num / $step ) ) {
		if ( '' !== ys_get_ad_infeed() ) {
			get_template_part( 'template-parts/archive/infeed' );
		}
	}
}

/**
 * インフィード広告のプレビュー画面でのエラー対処
 *
 * @param  string $ad 広告コード.
 */
function ys_fix_ad_previw_error( $ad ) {
	if ( ! is_customize_preview() ) {
		return apply_filters( 'ys_fix_ad_infeed_error', $ad );
	}
	/**
	 * Google Adsense コード貼り付けでのエラー対処
	 */
	$adsense_script = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
	if ( false !== strpos( $ad, $adsense_script ) ) {
		/**
		 * プレビューでのエラー対策
		 */
		$ad = str_replace( $adsense_script, '', $ad );
		wp_enqueue_script(
			'google-ads',
			'//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
			array(),
			false,
			true
		);
	}

	return apply_filters( 'ys_fix_ad_infeed_error', $ad );
}
