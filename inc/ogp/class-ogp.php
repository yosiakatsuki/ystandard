<?php
/**
 * OGP
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class OGP
 *
 * @package ystandard
 */
class OGP {

	/**
	 * 画像サイズ
	 */
	const ATTACHMENT_SIZE = 'post-thumbnail';

	/**
	 * OGPパラメーター
	 *
	 * @var array
	 */
	private $meta_param = [];

	/**
	 * OGP constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'ogp_meta' ] );
	}

	/**
	 * OGPタグ出力
	 */
	public function ogp_meta() {

		$this->set_ogp_param();
		$this->set_twitter_card_param();

		$ogp = '';
		foreach ( $this->meta_param as $key => $value ) {
			$ogp .= "<meta property=\"${key}\" content=\"${value}\" />" . PHP_EOL;
		}
		echo apply_filters( 'ys_get_the_ogp', $ogp );
	}

	/**
	 * OGPパラメーターの作成
	 */
	private function set_ogp_param() {
		if ( ! Option::get_option_by_bool( 'ys_ogp_enable', true ) ) {
			return;
		}
		/**
		 * デフォルト
		 */
		$this->set_param( 'og:site_name', get_bloginfo( 'name' ) );
		$this->set_param( 'og:locale', get_bloginfo( 'language' ) );
		$this->set_param( 'og:type', 'website' );
		$this->set_param( 'og:url', home_url( '/' ) );
		$this->set_param( 'og:title', get_bloginfo( 'name' ) );
		$this->set_param( 'og:description', Head::get_meta_description() );
		$this->set_param( 'og:image', $this->get_ogp_image() );
		$this->set_param( 'fb:app_id', Option::get_option( 'ys_ogp_fb_app_id', '' ) );
		/**
		 * 投稿・固定ページ系
		 */
		if ( is_singular() && ! Template::is_top_page() ) {
			$this->set_param( 'og:type', 'article' );
			$this->set_param( 'og:url', get_the_permalink() );
			$this->set_param( 'og:title', $this->get_singular_title() );
			$this->set_param( 'og:description', $this->get_singular_dscr() );
		}
		/**
		 * アーカイブ系
		 */
		if ( is_archive() && ! Template::is_top_page() ) {
			$this->set_param( 'og:url', Content::get_archive_url() );
			$this->set_param( 'og:title', $this->get_archive_title() );
			$this->set_param( 'og:description', $this->get_archive_dscr() );
		}
	}

	/**
	 * OGPパラメーターの作成
	 */
	private function set_twitter_card_param() {
		if ( ! Option::get_option_by_bool( 'ys_twittercard_enable', true ) ) {
			return;
		}
		/**
		 * デフォルト
		 */
		$this->set_param( 'twitter:card', Option::get_option( 'ys_twittercard_type', 'summary_large_image' ) );
		$this->set_param( 'twitter:site', Option::get_option( 'ys_twittercard_user', '' ) );
		$this->set_param( 'twitter:title', get_bloginfo( 'name' ) );
		$this->set_param( 'twitter:description', Head::get_meta_description() );
		$this->set_param( 'twitter:image', $this->get_ogp_image() );
		/**
		 * 投稿・固定ページ系
		 */
		if ( is_singular() && ! Template::is_top_page() ) {
			$this->set_param( 'twitter:title', $this->get_singular_title() );
			$this->set_param( 'twitter:description', $this->get_singular_dscr() );
		}
		/**
		 * アーカイブ系
		 */
		if ( is_archive() && ! Template::is_top_page() ) {
			$this->set_param( 'twitter:title', $this->get_archive_title() );
			$this->set_param( 'twitter:description', $this->get_archive_dscr() );
		}
	}

	/**
	 * OGP情報のセット
	 *
	 * @param string $key   Meta key.
	 * @param string $value Value.
	 */
	private function set_param( $key, $value ) {
		if ( empty( $value ) ) {
			return;
		}
		$this->meta_param[ $key ] = $value;
	}

	/**
	 * 投稿タイトル
	 *
	 * @return string
	 */
	private function get_singular_title() {
		return apply_filters( 'ys_ogp_title_singular', get_the_title() );
	}

	/**
	 * アーカイブタイトル
	 *
	 * @return string
	 */
	private function get_archive_title() {
		return apply_filters( 'ys_ogp_title_archive', get_the_archive_title() );
	}

	/**
	 * アーカイブ概要
	 *
	 * @return string
	 */
	private function get_archive_dscr() {
		return apply_filters( 'ys_ogp_description_archive', get_the_archive_description() );
	}

	/**
	 * 投稿抜粋
	 *
	 * @return string
	 */
	private function get_singular_dscr() {

		$dscr = Content::get_post_meta( 'ys_ogp_description' );
		if ( empty( $dscr ) ) {
			$dscr = Content::get_custom_excerpt_raw();
		}

		return apply_filters( 'ys_ogp_description_singular', $dscr );
	}

	/**
	 * OGP画像
	 *
	 * @return string
	 */
	private function get_ogp_image() {
		$image = '';
		if ( is_singular() && ! Template::is_top_page() ) {
			$image = get_the_post_thumbnail_url();
		}
		/**
		 * デフォルト画像
		 */
		if ( empty( $image ) ) {
			$image = Option::get_option( 'ys_ogp_default_image', '' );
		}
		/**
		 * ロゴ
		 */
		if ( empty( $image ) ) {
			$logo_id = Utility::get_custom_logo_id();
			if ( $logo_id ) {
				$image = wp_get_attachment_image_src( $logo_id, self::ATTACHMENT_SIZE );
			}
		}

		return apply_filters( 'ys_ogp_image', $image );
	}

}

new OGP();
