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
	 * フロントページのテンプレート情報を取得
	 *
	 * @return string
	 */
	public static function get_front_page_template() {
		$type = get_option( 'show_on_front' );
		if ( 'page' === $type ) {
			$template      = 'page';
			$page_template = get_page_template_slug();

			if ( $page_template ) {
				if ( file_exists( get_stylesheet_directory() . $page_template ) || file_exists( $page_template ) ) {
					$template = str_replace( '.php', '', $page_template );
				}
			}
		} else {
			$template = 'home';
		}

		return $template;
	}

	/**
	 * TOPページか
	 *
	 * @return bool
	 */
	public static function is_top_page() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			if ( is_front_page() ) {
				return true;
			}
		} else {
			if ( is_home() && ! is_paged() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * フル幅テンプレートか
	 *
	 * @return bool
	 */
	public static function is_wide() {
		if ( self::is_one_column() ) {
			/**
			 * フル幅にするテンプレート
			 */
			$templates = [
				'page-template/template-one-column-wide.php',
				'page-template/template-blank-wide.php',
			];
			if ( is_page_template( $templates ) || 'wide' === Option::get_option( 'ys_design_one_col_content_type', 'normal' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 1カラム表示か
	 *
	 * @return bool
	 */
	public static function is_one_column() {
		/**
		 * ワンカラムテンプレート
		 */
		$template = [
			'page-template/template-one-column.php',
			'page-template/template-one-column-wide.php',
			'page-template/template-blank.php',
			'page-template/template-blank-wide.php',
		];
		if ( is_page_template( $template ) ) {
			return true;
		}
		/**
		 * サイドバーが無ければ1カラム
		 */
		if ( ! is_active_sidebar( 'sidebar-widget' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
			return true;
		}
		/**
		 * 一覧系
		 */
		if ( is_home() || is_archive() || is_search() || is_404() ) {
			if ( '1col' === Option::get_option( 'ys_archive_layout', '2col' ) ) {
				return true;
			}
		}
		/**
		 * 固定ページ
		 */
		if ( ( is_page() && ! is_front_page() ) && '1col' === Option::get_option( 'ys_page_layout', '2col' ) ) {
			return true;
		}
		/**
		 * 投稿
		 */
		if ( ( is_singular() && ! is_page() ) && '1col' === Option::get_option( 'ys_post_layout', '2col' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * 投稿ヘッダーを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_post_header() {
		$result = true;
		if ( self::is_no_title_template() ) {
			$result = false;
		}

		return apply_filters( 'ys_is_active_post_header', $result );
	}

	/**
	 * タイトルなしテンプレートか
	 *
	 * @return bool
	 */
	public static function is_no_title_template() {
		$template = [
			'page-template/template-blank.php',
			'page-template/template-blank-wide.php',
		];

		return is_page_template( $template );
	}

	/**
	 * モバイル判定
	 *
	 * @return bool
	 */
	public static function is_mobile() {

		$ua = [
			'^(?!.*iPad).*iPhone',
			'iPod',
			'Android.*Mobile',
			'Mobile.*Firefox',
			'Windows.*Phone',
			'blackberry',
			'dream',
			'CUPCAKE',
			'webOS',
			'incognito',
			'webmate',
		];

		$ua = apply_filters( 'ys_is_mobile_ua_list', $ua );

		return Utility::check_user_agent( $ua );
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
		$slug = apply_filters( 'ys_get_template_part_slug', $slug, $name, $args );
		$name = apply_filters( 'ys_get_template_part_name', $name, $slug, $args );
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

	/**
	 * テーマ内のファイルURLを取得する
	 *
	 * @param string $file テーマディレクトリからのファイルパス.
	 *
	 * @return string
	 */
	public static function get_theme_file_uri( $file ) {
		/**
		 * 4.7以下の場合 親テーマのファイルを返す
		 */
		if ( version_compare( get_bloginfo( 'version' ), '4.7-alpha', '<' ) ) {
			return get_template_directory_uri() . $file;
		}

		return get_theme_file_uri( $file );
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
		if ( Content::is_active_post_thumbnail( $post->ID ) ) {
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
}

new Template();
