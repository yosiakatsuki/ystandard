<?php
/**
 * サイドバー関連.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

/**
 * Class Sidebar
 */
class Sidebar {
	/**
	 * サイドバー設定ラベル名.
	 */
	private const LAYOUT_LABEL_NAME = 'ys_layout_sidebar_section_label';

	/**
	 * サイドバー幅設定名.
	 */
	private const WIDTH_OPTION_NAME = 'ys_sidebar_width';

	/**
	 * カラム間隔設定名.
	 */
	private const GAP_OPTION_NAME = 'ys_sidebar_gap';

	/**
	 * 旧モバイルサイドバー設定.
	 */
	private const LEGACY_HIDE_MOBILE_OPTION = 'ys_hide_sidebar_mobile';

	/**
	 * インスタンス
	 *
	 * @var Sidebar
	 */
	private static $instance;

	/**
	 * インスタンス取得
	 *
	 * @return Sidebar
	 */
	public static function get_instance(): Sidebar {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * コンストラクタ.
	 */
	private function __construct() {
		add_filter( 'ys_sidebar_class', [ $this, 'sidebar_class' ] );
	}

	/**
	 * レイアウト設定のフックを登録.
	 *
	 * @return void
	 */
	public static function register_layout_settings(): void {
		add_action( 'customize_register', [ self::class, 'customize_register' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ self::class, 'add_layout_css_vars' ] );
	}

	/**
	 * サイドバーのレイアウト設定を追加.
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 * @return void
	 */
	public static function customize_register( $wp_customize ): void {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section_label(
			__( 'サイドバー', 'ystandard' ),
			[
				'id'          => self::LAYOUT_LABEL_NAME,
				'section'     => Layout::SECTION_NAME,
				'description' => __( '2カラムレイアウトのサイドバーに関する設定', 'ystandard' ),
			]
		);

		$customizer->add_text(
			[
				'id'                => self::WIDTH_OPTION_NAME,
				'section'           => Layout::SECTION_NAME,
				'label'             => __( '2カラムのサイドバー幅', 'ystandard' ),
				'description'       => __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
				'sanitize_callback' => [ Layout::class, 'sanitize_css_value' ],
			]
		);

		$customizer->add_text(
			[
				'id'                => self::GAP_OPTION_NAME,
				'section'           => Layout::SECTION_NAME,
				'label'             => __( 'メインコンテンツとサイドバーの間隔', 'ystandard' ),
				'description'       => __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
				'sanitize_callback' => [ Layout::class, 'sanitize_css_value' ],
			]
		);
	}

	/**
	 * サイドバーのレイアウト用CSSカスタムプロパティを追加.
	 *
	 * @param array $css_vars CSSカスタムプロパティ.
	 * @return array
	 */
	public static function add_layout_css_vars( array $css_vars ): array {
		$width = Layout::normalize_css_value( Option::get_option( self::WIDTH_OPTION_NAME, '' ) );

		// 未設定時はSCSSの可変幅を維持し、保存値がある場合だけ上書きする.
		if ( '' !== $width ) {
			$css_vars['--ystd--sidebar--2col--size'] = $width;
		}

		$gap = Layout::normalize_css_value( Option::get_option( self::GAP_OPTION_NAME, '' ) );

		// 0も有効な設定として扱い、未設定の場合だけテーマ標準の間隔を維持する.
		if ( '' !== $gap ) {
			$css_vars['--ystd--sidebar--2col--gap'] = $gap;
		}

		return $css_vars;
	}

	/**
	 * モバイルでサイドバーを非表示にするか.
	 *
	 * @return bool
	 */
	public static function is_hidden_on_mobile(): bool {
		$setting_name = self::get_mobile_setting_name();

		// 投稿タイプ別設定が保存されている場合は、旧共通設定より優先する.
		if ( $setting_name && Option::exists_option( $setting_name ) ) {
			return Option::get_option_by_bool( $setting_name, false );
		}

		// 新設定が未保存の場合は、v4までの共通設定を引き継ぐ.
		return Option::get_option_by_bool( self::LEGACY_HIDE_MOBILE_OPTION, false );
	}

	/**
	 * 現在のページに対応する設定名を取得.
	 *
	 * @return string
	 */
	private static function get_mobile_setting_name(): string {
		// 個別ページでは投稿タイプ別の詳細ページ設定を使用する.
		if ( is_singular() ) {
			$post_type = Post_Type::get_post_type();

			return $post_type ? "ys_hide_{$post_type}_sidebar_mobile" : '';
		}

		// 個別ページ以外では、一覧ページに対応する投稿タイプ別設定を使用する.
		$post_type = self::get_archive_post_type();

		return $post_type ? "ys_hide_{$post_type}_archive_sidebar_mobile" : '';
	}

	/**
	 * 現在の一覧ページに対応する投稿タイプを取得.
	 *
	 * @return string
	 */
	private static function get_archive_post_type(): string {
		// WordPress標準の投稿一覧として扱われるページは、投稿の設定にまとめる.
		if ( is_home() || is_category() || is_tag() || is_date() || is_author() || is_search() ) {
			return 'post';
		}

		// カスタム投稿タイプアーカイブは、クエリ対象の投稿タイプを使用する.
		if ( is_post_type_archive() ) {
			$queried_object = get_queried_object();

			// WP_Post_Typeが取得できる場合は、その情報を正として扱う.
			if ( $queried_object instanceof \WP_Post_Type ) {
				return $queried_object->name;
			}

			$post_type = get_query_var( 'post_type' );

			// オブジェクトを取得できない場合は、クエリ変数から投稿タイプを補完する.
			if ( is_string( $post_type ) ) {
				return $post_type;
			}

			// 対象が1種類に限定される場合だけ、配列形式のクエリ変数を使用する.
			if ( is_array( $post_type ) && 1 === count( $post_type ) ) {
				return (string) reset( $post_type );
			}
		}

		// タクソノミーアーカイブは、紐づく投稿タイプが一意な場合だけ設定対象にする.
		if ( is_tax() ) {
			$term = get_queried_object();

			// タームを特定できなければ、対応する投稿タイプも判定できない.
			if ( ! $term instanceof \WP_Term ) {
				return '';
			}

			$taxonomy = get_taxonomy( $term->taxonomy );

			// 複数投稿タイプで共有するタクソノミーは、適用先を決められないため除外する.
			if ( $taxonomy && 1 === count( $taxonomy->object_type ) ) {
				return (string) reset( $taxonomy->object_type );
			}
		}

		// 対応する投稿タイプを一意に決められないページでは新設定を使用しない.
		return '';
	}

	/**
	 * サイドバークラス
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function sidebar_class( array $classes ): array {
		// 投稿本文エリア背景色設定.
		$content_background = Post_Content::get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$classes[] = 'has-content-background';
		}

		return $classes;
	}

}
