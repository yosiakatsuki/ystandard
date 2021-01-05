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
		add_action( 'customize_register', [ $this, 'customize_register' ] );
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
		$this->set_param( 'og:site_name', get_bloginfo( 'name', 'display' ) );
		$this->set_param( 'og:locale', get_bloginfo( 'language' ) );
		$this->set_param( 'og:type', 'website' );
		$this->set_param( 'og:url', home_url( '/' ) );
		$this->set_param( 'og:title', get_bloginfo( 'name', 'display' ) );
		$this->set_param( 'og:description', Meta_Description::get_meta_description() );
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
			$this->set_param( 'og:url', Archive::get_archive_url() );
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
		$this->set_param( 'twitter:title', get_bloginfo( 'name', 'display' ) );
		$this->set_param( 'twitter:description', Meta_Description::get_meta_description() );
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
		$title = Content::get_post_meta( 'ys_ogp_title' );
		if ( empty( $title ) ) {
			$title = get_the_title();
		}

		$title = apply_filters( 'ys_ogp_title_singular', $title );

		return Utility::get_plain_text( $title );
	}

	/**
	 * アーカイブタイトル
	 *
	 * @return string
	 */
	private function get_archive_title() {
		$title = apply_filters( 'ys_ogp_title_archive', get_the_archive_title() );

		return Utility::get_plain_text( $title );
	}

	/**
	 * アーカイブ概要
	 *
	 * @return string
	 */
	private function get_archive_dscr() {
		$dscr = apply_filters( 'ys_ogp_description_archive', get_the_archive_description() );

		return Utility::get_plain_text( $dscr );
	}

	/**
	 * 投稿抜粋
	 *
	 * @return string
	 */
	private function get_singular_dscr() {

		$dscr = Content::get_post_meta( 'ys_ogp_description' );
		if ( empty( $dscr ) ) {
			$dscr = Content::get_custom_excerpt( '' );
		}

		$dscr = apply_filters( 'ys_ogp_description_singular', $dscr );

		return Utility::get_plain_text( $dscr );
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
				$image_src = wp_get_attachment_image_src( $logo_id, self::ATTACHMENT_SIZE );
				if ( $image_src ) {
					$image = $image_src[0];
				}
			}
		}

		return apply_filters( 'ys_ogp_image', $image );
	}


	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => 'ys_ogp',
				'title'       => 'OGP',
				'priority'    => 1,
				'panel'       => SNS::PANEL_NAME,
				'description' => Admin::manual_link( 'ogp-meta' ),
			]
		);

		// metaタグを出力する.
		$customizer->add_label(
			[
				'id'    => 'ys_ogp_enable_label',
				'label' => 'OGP metaタグ',
			]
		);
		$customizer->add_checkbox(
			[
				'id'        => 'ys_ogp_enable',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'OGPのmetaタグを出力する',
			]
		);
		// Facebook app_id.
		$customizer->add_text(
			[
				'id'          => 'ys_ogp_fb_app_id',
				'default'     => '',
				'transport'   => 'postMessage',
				'description' => '',
				'label'       => 'Facebook app_id',
				'input_attrs' => [
					'placeholder' => '000000000000000',
					'maxlength'   => 15,
				],
			]
		);
		// OGPデフォルト画像.
		$customizer->add_image(
			[
				'id'          => 'ys_ogp_default_image',
				'transport'   => 'postMessage',
				'label'       => 'OGPデフォルト画像',
				'description' => 'トップページ・アーカイブページ・投稿にアイキャッチ画像が無かった場合のデフォルト画像を指定して下さい。<br>おすすめサイズ：横1200px - 縦630px',
			]
		);
		/**
		 * Twitter Cards
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_twitter_cards',
				'title'       => 'Twitterカード',
				'priority'    => 2,
				'description' => Admin::manual_link( 'twitter-card' ),
				'panel'       => SNS::PANEL_NAME,
			]
		);
		// Twitterカードのmetaタグを出力する.
		$customizer->add_label(
			[
				'id'    => 'ys_twittercard_enable_label',
				'label' => 'Twitterカードmetaタグ',
			]
		);
		$customizer->add_checkbox(
			[
				'id'        => 'ys_twittercard_enable',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'Twitterカードのmetaタグを出力する',
			]
		);
		// ユーザー名.
		$customizer->add_text(
			[
				'id'          => 'ys_twittercard_user',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Twitterカードのユーザー名',
				'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」',
				'input_attrs' => [
					'placeholder' => 'username',
				],
			]
		);
		// カードタイプ.
		$customizer->add_radio(
			[
				'id'        => 'ys_twittercard_type',
				'default'   => 'summary_large_image',
				'transport' => 'postMessage',
				'label'     => 'カードタイプ',
				'choices'   => [
					'summary_large_image' => 'Summary Card with Large Image',
					'summary'             => 'Summary Card',
				],
			]
		);

	}
}

new OGP();
