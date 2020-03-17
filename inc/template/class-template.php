<?php
/**
 * テンプレート関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Template_Function
 *
 * @package ystandard
 */
class Template {

	/**
	 * Template_Function constructor.
	 */
	public function __construct() {
		add_filter( 'the_excerpt_rss', [ $this, 'add_rss_thumbnail' ] );
		add_filter( 'the_content_feed', [ $this, 'add_rss_thumbnail' ] );
		add_action( 'pre_ping', [ $this, 'no_self_ping' ] );
	}

	/**
	 * RSSフィードにアイキャッチ画像を表示
	 *
	 * @param string $content content.
	 *
	 * @return string
	 */
	public function add_rss_thumbnail( $content ) {
		global $post;
		if ( Conditional_Tag::is_active_post_thumbnail( $post->ID ) ) {
			$content = get_the_post_thumbnail( $post->ID ) . $content;
		}

		return $content;
	}

	/**
	 * セルフピンバック対策
	 *
	 * @param array $links links.
	 *
	 * @return void
	 */
	public function no_self_ping( &$links ) {
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, home_url() ) ) {
				unset( $links[ $l ] );
			}
		}
	}

	/**
	 * ショートコードの作成と実行
	 *
	 * @param string $name    ショートコード名.
	 * @param array  $args    パラメーター.
	 * @param mixed  $content コンテンツ.
	 * @param bool   $echo    出力.
	 *
	 * @return string
	 */
	public static function do_shortcode( $name, $args = [], $content = null, $echo = true ) {
		$atts = [];
		/**
		 * パラメーター展開
		 */
		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( is_array( $value ) ) {
					$value = implode( ',', $value );
				}
				$atts[] = sprintf(
					'%s="%s"',
					$key,
					$value
				);
			}
		}
		$atts = empty( $atts ) ? '' : ' ' . implode( ' ', $atts );
		/**
		 * ショートコード作成
		 */
		if ( null === $content ) {
			// コンテンツなし.
			$shortcode = sprintf( '[%s%s]', $name, $atts );
		} else {
			// コンテンツあり.
			$shortcode = sprintf( '[%s%s]%s[/%s]', $name, $atts, $content, $name );
		}
		$result = do_shortcode( $shortcode );
		/**
		 * 表示 or 取得
		 */
		if ( $echo ) {
			echo $result;

			return '';
		} else {
			return $result;
		}
	}

	/**
	 * テンプレート読み込み拡張
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 * @param array  $args テンプレートに渡す変数.
	 */
	public static function get_template_part( $slug, $name = null, $args = [] ) {
		/**
		 * テンプレート上書き
		 */
		$slug = apply_filters( 'ys_get_template_part_slug', $slug, $name );
		$args = apply_filters( 'ys_get_template_part_args', $args, $slug, $name );
		do_action( "get_template_part_{$slug}", $slug, $name );

		$templates = [];
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}

		$templates[] = "{$slug}.php";

		do_action( 'get_template_part', $slug, $name, $templates );

		$located = locate_template( $templates );
		/**
		 * テーマ・プラグイン内のファイルのパスが指定されてきた場合そちらを優先
		 */
		if ( false !== strpos( $slug, ABSPATH ) && file_exists( $slug ) ) {
			$located = $slug;
		}
		/**
		 * テンプレート読み込み
		 */
		if ( ! empty( $located ) ) {
			global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

			if ( is_array( $wp_query->query_vars ) ) {
				// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
				extract( $wp_query->query_vars, EXTR_SKIP );
			}
			if ( is_array( $args ) ) {
				// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
				extract( $args, EXTR_SKIP );
			}

			if ( isset( $s ) ) {
				$s = esc_attr( $s );
			}
			require $located;
		}
	}
}

new Template();
