<?php
/**
 * Embed クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Embed
 *
 * @package ystandard
 */
class Embed {

	/**
	 * アクション・フィルターの登録
	 */
	public function register() {
		add_action( 'enqueue_embed_scripts', [ $this, 'enqueue_embed_styles' ] );

		if ( self::is_disable_embed() ) {
			add_filter( 'embed_template', [ $this, 'embed_template' ] );
			remove_action( 'embed_content_meta', 'print_embed_comments_button' );
			remove_action( 'embed_content_meta', 'print_embed_sharing_button' );
			remove_action( 'embed_footer', 'print_embed_sharing_dialog' );
			add_filter(
				Admin::BLOCK_EDITOR_ASSETS_HOOK,
				function ( $css ) {
					$css .= '.block-editor__container .wp-embedded-content {height:auto;}';

					return $css;
				}
			);
		} else {
			$this->embed_content_filters();
		}
	}

	/**
	 * Embedが無効か
	 *
	 * @return bool
	 */
	public static function is_disable_embed() {
		return Option::get_option_by_bool( 'ys_option_disable_wp_oembed', true );
	}

	/**
	 * Embed用CSS
	 */
	public function enqueue_embed_styles() {
		if ( self::is_disable_embed() ) {
			wp_enqueue_style(
				'ys-embed',
				get_template_directory_uri() . '/css/embed.css',
				[],
				Utility::get_ystandard_version()
			);
		}
	}

	/**
	 * Embed用テンプレートパスの変更
	 *
	 * @param string $template Template Path.
	 *
	 * @return string
	 */
	public function embed_template( $template ) {

		return get_template_directory() . '/template-parts/parts/embed.php';
	}

	/**
	 * Embedコンテンツ取得.
	 *
	 * @return string
	 */
	public function get_embed_content() {

		$this->before_embed_filters();
		$url     = get_permalink();
		$content = do_shortcode( '[ys_blog_card url="' . $url . '"]' );
		$this->after_embed_filters();

		return $content;
	}

	/**
	 * Embedコンテンツ処理前のフィルター
	 */
	private function before_embed_filters() {
		add_filter( 'post_thumbnail_html', [ $this, 'cancel_rocket_lazy_load' ] );
	}

	/**
	 * Embedコンテンツ処理後のフィルター
	 */
	private function after_embed_filters() {
		remove_filter( 'post_thumbnail_html', [ $this, 'cancel_rocket_lazy_load' ] );
	}

	/**
	 * Embedコンテンツで実行するフィルタ
	 */
	public function embed_content_filters() {
		// Rocket Lazy Loadのキャンセル.
		add_filter(
			'wp_get_attachment_image_attributes',
			function ( $attr ) {
				if ( is_embed() ) {
					$attr['data-no-lazy'] = '1';
				}

				return $attr;
			}
		);
	}

	/**
	 * Rocket Lazy Loadのキャンセル
	 *
	 * @param string $html image html.
	 *
	 * @return string
	 */
	public function cancel_rocket_lazy_load( $html ) {

		if ( false === strpos( $html, 'data-no-lazy' ) ) {
			$html = str_replace( '<img ', '<img data-no-lazy="1" ', $html );
		}

		return $html;
	}
}

$class_embed = new Embed();
$class_embed->register();
