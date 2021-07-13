<?php
/**
 * HTML HEADタグ内の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Head
 *
 * @package ystandard
 */
class Head {

	/**
	 * Head constructor.
	 */
	public function __construct() {

		add_action( 'wp_head', [ $this, 'pingback_url' ] );
		add_action( 'wp_head', [ $this, 'add_preload' ], 2 );
		add_action( 'wp_head', [ $this, 'add_apple_touch_icon' ] );
		add_action( 'wp_head', [ $this, 'canonical_tag' ] );
		add_action( 'wp_head', [ $this, 'rel_link' ] );
		add_filter( 'site_icon_meta_tags', [ $this, 'site_icon_meta_tags' ] );
	}

	/**
	 * <head>タグにつける属性取得
	 */
	public static function get_head_attr() {
		$attr = [];
		if ( is_singular() ) {
			$attr[] = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"';
		} else {
			$attr[] = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#"';
		}
		$attr = apply_filters( 'ys_get_head_attr', $attr );

		return implode( ' ', $attr );
	}

	/**
	 * Next,Prevタグ出力
	 */
	public function rel_link() {
		if ( is_single() || is_page() ) {
			/**
			 * 固定ページ・投稿ページ
			 */
			global $post, $page;
			$pagecnt = substr_count( $post->post_content, '<!--nextpage-->' ) + 1;

			if ( $pagecnt > 1 ) {
				/**
				 * Prev
				 */
				if ( $page > 1 ) {
					printf( '<link rel="prev" href="%s" />' . PHP_EOL, $this->get_link_page( $page - 1 ) );
				}
				/**
				 * Next
				 */
				if ( $page < $pagecnt ) {
					$page = 0 === $page ? 1 : $page;
					printf( '<link rel="next" href="%s" />' . PHP_EOL, $this->get_link_page( $page + 1 ) );
				}
			}
		} else {
			/**
			 * アーカイブ
			 */
			global $wp_query;
			/**
			 * MAXページ数と現在ページ数を取得
			 */
			$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
			$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
			if ( $current > 1 ) {
				printf( '<link rel="prev" href="%s" />' . PHP_EOL, get_pagenum_link( $current - 1 ) );
			}
			if ( $current < $total ) {
				printf( '<link rel="next" href="%s" />' . PHP_EOL, get_pagenum_link( $current + 1 ) );
			}
		}
	}

	/**
	 * Prev,Next用URL取得
	 *
	 * @param int $i ページ番号.
	 *
	 * @return string
	 */
	function get_link_page( $i ) {
		global $wp_rewrite;
		$post = get_post();
		if ( 1 === $i ) {
			$url = get_permalink();
		} else {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ], true ) ) {
				$url = add_query_arg( 'page', $i, get_permalink() );
			} elseif ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		return $url;
	}

	/**
	 * Canonicalタグ
	 */
	public function canonical_tag() {
		$canonical = $this->get_canonical_url();
		if ( '' !== $canonical ) {
			printf( '<link rel="canonical" href="%s">' . PHP_EOL, $canonical );
		}
	}

	/**
	 * Canonical url 取得
	 *
	 * @return string
	 */
	private function get_canonical_url() {
		$canonical = '';
		if ( is_front_page() ) {
			$canonical = home_url();
		} else {
			$paged     = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
			$canonical = get_pagenum_link( $paged );
		}

		return apply_filters( 'ys_get_the_canonical_url', $canonical );
	}


	/**
	 * サイトアイコン
	 *
	 * @param mixed $meta_tags meta tag.
	 *
	 * @return array
	 */
	public function site_icon_meta_tags( $meta_tags ) {
		$meta_tags = [
			sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( get_site_icon_url( 32 ) ) ),
			sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( get_site_icon_url( 192 ) ) ),
		];

		return $meta_tags;
	}

	/**
	 * Apple Touch Icon追加
	 */
	public function add_apple_touch_icon() {
		if ( ! $this->get_apple_touch_icon_url( 512, '' ) && ! is_customize_preview() ) {
			return;
		}
		printf(
			'<link rel="apple-touch-icon-precomposed" href="%s" />' . PHP_EOL,
			esc_url( $this->get_apple_touch_icon_url( 180 ) )
		);
		printf(
			'<meta name="msapplication-TileImage" content="%s" />' . PHP_EOL,
			esc_url( $this->get_apple_touch_icon_url( 270 ) )
		);
	}

	/**
	 * Apple touch icon用URLを取得
	 *
	 * @param integer $size    サイズ.
	 * @param string  $url     ロゴURL.
	 * @param integer $blog_id ブログID.
	 *
	 * @return string
	 */
	private function get_apple_touch_icon_url( $size = 512, $url = '', $blog_id = 0 ) {

		if ( is_multisite() && get_current_blog_id() !== (int) $blog_id ) {
			switch_to_blog( $blog_id );
		}

		$site_icon_id = get_option( 'ys_apple_touch_icon' );
		if ( $site_icon_id ) {
			if ( $size >= 512 ) {
				$size_data = 'full';
			} else {
				$size_data = [ $size, $size ];
			}
			$url = wp_get_attachment_image_url( $site_icon_id, $size_data );
		}
		if ( is_multisite() && ms_is_switched() ) {
			restore_current_blog();
		}

		return $url;
	}

	/**
	 * Preloadタグを追加
	 */
	public function add_preload() {
		/**
		 * Preloadタグを追加するリソースのセット
		 */
		$preload = apply_filters( 'ys_the_preload_list', [] );
		if ( ! is_array( $preload ) || empty( $preload ) ) {
			return;
		}
		/**
		 * Preloadタグ展開
		 */
		foreach ( $preload as $key => $value ) {
			printf(
				'<link id="%s" rel="preload" as="%s" type="%s" href="%s" crossorigin />' . PHP_EOL,
				$key,
				$value['as'],
				$value['type'],
				$value['url']
			);
		}
	}

	/**
	 * ピンバックURLの出力
	 */
	public function pingback_url() {
		if ( is_singular() && pings_open( get_queried_object() ) ) {
			echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . PHP_EOL;
		}
	}
}

new Head();
