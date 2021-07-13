<?php
/**
 * テンプレート関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

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
		add_filter( 'theme_templates', [ $this, 'custom_template' ], 10, 4 );
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
	 * 固定ページで設定されたフロントページか
	 *
	 * @return bool
	 */
	public static function is_single_front_page() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			return is_front_page();
		}

		return false;
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
		$post_type = Content::get_post_type();
		$is_wide   = apply_filters( "ys_${post_type}_is_wide", null );
		if ( ! is_null( $is_wide ) ) {
			return Utility::to_bool( $is_wide );
		}
		if ( self::is_one_column() ) {
			/**
			 * フル幅にするテンプレート
			 */
			$templates = apply_filters(
				'ys_is_wide_templates',
				[
					'page-template/template-one-column-wide.php',
					'page-template/template-blank-wide.php',
				]
			);
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

		$filter = apply_filters( 'ys_is_one_column', null );
		if ( ! is_null( $filter ) ) {
			return $filter;
		}
		/**
		 * ワンカラムテンプレート
		 */
		$template = apply_filters(
			'ys_is_one_column_templates',
			[
				'page-template/template-one-column.php',
				'page-template/template-one-column-wide.php',
				'page-template/template-blank.php',
				'page-template/template-blank-wide.php',
			]
		);
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
		$post_type = Content::get_post_type();
		// 投稿タイプ一覧.
		$post_type_archive = apply_filters( "ys_${post_type}_archive_one_column", null );
		if ( is_post_type_archive( $post_type ) && ! is_null( $post_type_archive ) ) {
			return Utility::to_bool( $post_type_archive );
		}
		// タクソノミー一覧.
		if ( is_tax() ) {
			$taxonomy    = get_query_var( 'taxonomy' );
			$tax_archive = apply_filters( "ys_${taxonomy}_taxonomy_archive_one_column", null );
			if ( is_tax( $taxonomy ) && ! is_null( $tax_archive ) ) {
				return Utility::to_bool( $tax_archive );
			}
		}
		// カテゴリー一覧.
		if ( is_category() ) {
			$tax_archive = apply_filters( 'ys_category_taxonomy_archive_one_column', null );
			if ( ! is_null( $tax_archive ) ) {
				return Utility::to_bool( $tax_archive );
			}
		}
		// タグ一覧.
		if ( is_tag() ) {
			$tax_archive = apply_filters( 'ys_post_tag_taxonomy_archive_one_column', null );
			if ( ! is_null( $tax_archive ) ) {
				return Utility::to_bool( $tax_archive );
			}
		}
		// その他一覧.
		if ( is_home() || is_archive() || is_search() || is_404() ) {
			if ( '1col' === Option::get_option( 'ys_archive_layout', '2col' ) ) {
				return true;
			}
		}

		/**
		 * 投稿タイプ別
		 */
		$one_col = apply_filters( "ys_${post_type}_one_column", null );
		if ( is_singular( $post_type ) && ! is_null( $one_col ) ) {
			return Utility::to_bool( $one_col );
		}
		$filter = apply_filters( "ys_${post_type}_layout", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$type     = Option::get_option( "ys_${fallback}_layout", '2col' );
		} else {
			$type = $filter;
		}

		if ( is_singular( $post_type ) && '1col' === $type ) {
			return true;
		}
		/**
		 * [ys]パーツ
		 */
		if ( Parts::POST_TYPE === Content::get_post_type() ) {
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
		if ( self::is_single_front_page() ) {
			$result = false;
		}
		if ( self::is_no_title_template() ) {
			$result = false;
		}
		if ( is_singular() ) {
			$post_type = Content::get_post_type();
			$result    = apply_filters(
				"ys_is_active_post_header_${post_type}",
				$result
			);
		}

		return apply_filters( 'ys_is_active_post_header', $result );
	}

	/**
	 * 投稿フッターを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_post_footer() {
		$result = true;
		if ( is_singular() ) {
			$post_type = Content::get_post_type();
			$result    = apply_filters(
				"ys_is_active_post_footer_${post_type}",
				$result
			);
		}

		return apply_filters( 'ys_is_active_post_footer', $result );
	}

	/**
	 * タイトルなしテンプレートか
	 *
	 * @return bool
	 */
	public static function is_no_title_template() {

		$post_type = Content::get_post_type();
		$result    = apply_filters( "ys_${post_type}_no_title", null );
		if ( ! is_null( $result ) ) {
			return Utility::to_bool( $result );
		}

		$template = apply_filters(
			'ys_is_no_title_templates',
			[
				'page-template/template-blank.php',
				'page-template/template-blank-wide.php',
			]
		);

		return is_page_template( $template );
	}

	/**
	 * カスタムテンプレート選択追加
	 *
	 * @param string[]      $post_templates Array of template header names keyed by the template file name.
	 * @param \WP_Theme     $theme          The theme object.
	 * @param \WP_Post|null $post           The post being edited, provided for context, or null.
	 * @param string        $post_type      Post type to get the templates for.
	 *
	 * @return string[];
	 */
	public function custom_template( $post_templates, $theme, $post, $post_type ) {

		if ( Parts::POST_TYPE === $post_type ) {
			return $post_templates;
		}

		$post_type = get_post_type_object( $post_type );
		if ( is_null( $post_type ) ) {
			return $post_templates;
		}

		if ( ! $post_type->public ) {
			return $post_templates;
		}

		$templates = [
			'page-template/template-blank-wide.php'      => '投稿ヘッダーなし 1カラム(ワイド)',
			'page-template/template-blank.php'           => '投稿ヘッダーなし 1カラム',
			'page-template/template-one-column-wide.php' => '1カラム(ワイド)',
			'page-template/template-one-column.php'      => '1カラム',
		];
		foreach ( $templates as $file => $name ) {
			if ( ! array_key_exists( $file, $post_templates ) ) {
				$post_templates[ $file ] = $name;
			}
		}

		return $post_templates;
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

		// 投稿タイプ.
		$custom_post_type = Content::get_post_type();
		$custom_post_type = empty( $custom_post_type ) ? '' : $custom_post_type;
		// タクソノミー.
		$taxonomy   = '';
		$post_types = [];
		if ( is_category() ) {
			$taxonomy = 'category';
		}
		if ( is_tag() ) {
			$taxonomy = 'tag';
		}
		if ( is_tax() ) {
			$taxonomy         = get_query_var( 'taxonomy' );
			$taxonomy_objects = get_taxonomy( $taxonomy );
			$post_types       = $taxonomy_objects->object_type;
			$post_types       = array_diff( $post_types, [ $custom_post_type ] );
		}

		$templates = [];
		if ( ! is_string( $name ) ) {
			$name = '';
		}
		if ( '' !== $name ) {
			if ( '' !== $taxonomy ) {
				$templates[] = "{$slug}-{$name}-${taxonomy}.php";
				if ( ! empty( $post_types ) ) {
					foreach ( $post_types as $type ) {
						$templates[] = "{$slug}-{$name}-${type}.php";
					}
				}
			}
			if ( '' !== $custom_post_type ) {
				$templates[] = "{$slug}-{$name}-${custom_post_type}.php";
			}
			$templates[] = "{$slug}-{$name}.php";
		}

		if ( '' !== $taxonomy ) {
			$templates[] = "{$slug}-${taxonomy}.php";
			if ( ! empty( $post_types ) ) {
				foreach ( $post_types as $type ) {
					$templates[] = "{$slug}-${type}.php";
				}
			}
		}
		if ( '' !== $custom_post_type ) {
			$templates[] = "{$slug}-${custom_post_type}.php";
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
