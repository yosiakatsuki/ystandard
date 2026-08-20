<?php
/**
 * ブロックエディター投稿設定.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\Post_Type;
use ystandard\utils\Taxonomy as Taxonomy_Utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Post_Settings
 *
 * @package ystandard
 */
class Block_Editor_Post_Settings {

	/**
	 * スクリプトハンドル.
	 */
	const SCRIPT_HANDLE = 'ys-post-settings';

	/**
	 * Block_Editor_Post_Settings constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * 投稿設定モーダル用スクリプトを読み込む.
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		// 投稿編集画面以外では投稿設定UIを読み込まない.
		if ( ! $screen || 'post' !== $screen->base || empty( $screen->post_type ) ) {
			return;
		}
		// 対応する通常投稿タイプかys-partsだけを対象にする.
		if ( Parts::POST_TYPE !== $screen->post_type && ! Post_Meta::is_supported_post_type( $screen->post_type ) ) {
			return;
		}

		$script_path = get_theme_file_path( '/js/block-editor/post-settings.js' );
		$asset_path  = get_theme_file_path( '/js/block-editor/post-settings.asset.php' );
		// ビルド成果物がない開発途中では壊れたscriptタグを出力しない.
		if ( ! is_readable( $script_path ) || ! is_readable( $asset_path ) ) {
			return;
		}

		$asset = require $asset_path;
		wp_enqueue_script(
			self::SCRIPT_HANDLE,
			get_theme_file_uri( '/js/block-editor/post-settings.js' ),
			$asset['dependencies'],
			$asset['version'],
			true
		);
		wp_set_script_translations(
			self::SCRIPT_HANDLE,
			'ystandard',
			get_theme_file_path( '/languages' )
		);

		$style_path = get_theme_file_path( '/js/block-editor/style-post-settings.css' );
		// SCSSのビルド成果物がある場合だけモーダル用スタイルを読み込む.
		if ( is_readable( $style_path ) ) {
			wp_enqueue_style(
				self::SCRIPT_HANDLE,
				get_theme_file_uri( '/js/block-editor/style-post-settings.css' ),
				[],
				$asset['version']
			);
			wp_style_add_data( self::SCRIPT_HANDLE, 'rtl', 'replace' );
		}

		$post    = get_post();
		$post_id = $post ? (int) $post->ID : 0;
		wp_add_inline_script(
			self::SCRIPT_HANDLE,
			'window.ystdPostSettings = ' . wp_json_encode(
				$this->get_config( $screen->post_type, $post_id ),
				JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
			) . ';',
			'before'
		);
	}

	/**
	 * 投稿設定モーダルへ渡す初期データを取得する.
	 *
	 * @param string $post_type 投稿タイプ.
	 * @param int    $post_id   投稿ID.
	 *
	 * @return array
	 */
	public function get_config( $post_type, $post_id = 0 ) {
		$config = [
			'apiVersion'             => 1,
			'postType'               => (string) $post_type,
			'postId'                 => (int) $post_id,
			'postStatus'             => (string) get_post_status( $post_id ),
			'hasStoredSettings'      => false,
			'settings'               => [],
			'fallbackSettings'       => [],
			'themeSettings'          => [],
			'availableStateSettings' => [],
		];
		// ys-partsはショートコード案内だけを表示し、投稿設定メタを扱わない.
		if ( Parts::POST_TYPE === $post_type ) {
			return $config;
		}

		$saved                            = Post_Meta::get_saved_settings( $post_id );
		$config['hasStoredSettings']      = Post_Meta::is_valid_settings( $saved );
		$config['settings']               = $saved;
		$config['fallbackSettings']       = $config['hasStoredSettings'] ? [] : Post_Meta::get_legacy_settings( $post_id );
		$config['themeSettings']          = self::get_theme_settings( $post_type );
		$config['availableStateSettings'] = Post_Meta::get_available_state_fields( $post_type );

		return $config;
	}

	/**
	 * 投稿タイプ別のテーマ設定を表示用に整形する.
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return array
	 */
	public static function get_theme_settings( $post_type ) {
		$fallback_post_type = Post_Type::get_fallback_post_type( $post_type );
		$toc_display        = Option::get_option( 'ys_toc_display_type', 'content' );
		$toc_enabled        = Toc::is_enabled_for_post_type( $post_type ) && 'none' !== $toc_display;
		$post_thumbnail     = apply_filters( "ys_show_{$post_type}_header_thumbnail", null );
		// 外部から表示値が指定されていない場合は投稿タイプ別設定または従来設定を使う.
		if ( is_null( $post_thumbnail ) ) {
			$thumbnail_setting = "ys_show_{$post_type}_header_thumbnail";
			$post_thumbnail    = Option::exists_option( $thumbnail_setting )
				? Option::get_option_by_bool( $thumbnail_setting, true )
				: Option::get_option_by_bool( "ys_show_{$fallback_post_type}_header_thumbnail", true );
		}
		$header_taxonomy = self::is_header_taxonomy_enabled( $post_type, $fallback_post_type );
		$footer_taxonomy = self::is_footer_taxonomy_enabled( $post_type, $fallback_post_type );
		$share_header    = apply_filters(
			"ys_{$post_type}_share_button_type_header",
			Option::get_option( "ys_{$post_type}_share_button_type_header", 'none' )
		);
		$share_footer    = apply_filters(
			"ys_{$post_type}_share_button_type_footer",
			Option::get_option( "ys_{$post_type}_share_button_type_footer", 'circle' )
		);
		$publish_date    = apply_filters( "ys_show_{$post_type}_publish_date", null );
		// 外部から投稿タイプ別の表示値が指定されていない場合はカスタマイザー設定を使う.
		if ( is_null( $publish_date ) ) {
			$publish_date = Option::get_option( "ys_show_{$fallback_post_type}_publish_date", 'both' );
		}
		$author = apply_filters( "ys_show_{$post_type}_author", null );
		// 外部から投稿タイプ別の表示値が指定されていない場合はカスタマイザー設定を使う.
		if ( is_null( $author ) ) {
			$author = Option::get_option_by_bool( "ys_show_{$fallback_post_type}_author", true );
		}
		$related = apply_filters( "ys_show_{$post_type}_related", null );
		// 外部から投稿タイプ別の表示値が指定されていない場合はカスタマイザー設定を使う.
		if ( is_null( $related ) ) {
			$related_setting = "ys_show_{$post_type}_related";
			$related         = Option::exists_option( $related_setting )
				? Option::get_option_by_bool( $related_setting, true )
				: Option::get_option_by_bool( "ys_show_{$fallback_post_type}_related", true );
		}
		$paging_default = ! is_post_type_hierarchical( $post_type );
		$paging         = apply_filters( "ys_show_{$post_type}_paging", null );
		// 外部から投稿タイプ別の表示値が指定されていない場合はカスタマイザー設定を使う.
		if ( is_null( $paging ) ) {
			$paging_setting = "ys_show_{$post_type}_paging";
			$paging         = Option::exists_option( $paging_setting )
				? Option::get_option_by_bool( $paging_setting, $paging_default )
				: Option::get_option_by_bool( "ys_show_{$fallback_post_type}_paging", $paging_default );
		}
		$meta_description = Option::get_option_by_bool( 'ys_option_create_meta_description', true );
		$toc_value        = self::get_boolean_setting_value( $toc_enabled );
		// 目次を表示する設定では、追従先が分かるよう表示位置も補足する.
		if ( $toc_enabled ) {
			$toc_value = sprintf(
				/* translators: %s: table of contents display type. */
				__( 'ON（表示位置：%s）', 'ystandard' ),
				self::get_toc_display_label( $toc_display )
			);
		}

		$settings  = [
			'post_thumbnail'       => self::theme_setting(
				self::get_theme_setting_description(
					__( 'アイキャッチ画像を表示する', 'ystandard' ),
					self::get_boolean_setting_value( Convert::to_bool( $post_thumbnail ) )
				)
			),
			'header_taxonomy'      => self::theme_setting(
				self::get_theme_setting_description(
					__( 'カテゴリー情報の表示設定', 'ystandard' ),
					self::get_boolean_setting_value( $header_taxonomy )
				)
			),
			'footer_taxonomy'      => self::theme_setting(
				self::get_theme_setting_description(
					__( 'カテゴリー・タグ情報の選択', 'ystandard' ),
					self::get_boolean_setting_value( $footer_taxonomy )
				)
			),
			'advertisement'        => self::theme_setting(
				__( '「-」を選択した場合、テーマ設定「[ys]広告」に登録されている広告コードを表示します。', 'ystandard' )
			),
			'toc'                  => self::theme_setting(
				self::get_theme_setting_description( __( '目次を自動で作成する', 'ystandard' ), $toc_value )
			),
			'share_buttons_header' => self::theme_setting(
				self::get_theme_setting_description(
					__( '本文上部のSNSシェアボタン表示タイプ', 'ystandard' ),
					self::get_share_type_label( $share_header )
				)
			),
			'share_buttons_footer' => self::theme_setting(
				self::get_theme_setting_description(
					__( '本文下部のSNSシェアボタン表示タイプ', 'ystandard' ),
					self::get_share_type_label( $share_footer )
				)
			),
			'publish_date'         => self::theme_setting(
				self::get_theme_setting_description(
					__( '投稿日・更新日の表示タイプ', 'ystandard' ),
					self::get_publish_date_label( $publish_date )
				)
			),
			'author'               => self::theme_setting(
				self::get_theme_setting_description(
					__( '著者情報', 'ystandard' ),
					self::get_boolean_setting_value( $author )
				)
			),
			'related_posts'        => self::theme_setting(
				self::get_theme_setting_description(
					__( '関連記事', 'ystandard' ),
					self::get_boolean_setting_value( $related )
				)
			),
			'paging'               => self::theme_setting(
				self::get_theme_setting_description(
					__( '次の記事・前の記事', 'ystandard' ),
					self::get_boolean_setting_value( $paging )
				)
			),
			'noindex'              => self::theme_setting(
				__( '「-」を選択した場合、noindexを出力しません。', 'ystandard' )
			),
			'meta_description'     => self::theme_setting(
				self::get_theme_setting_description(
					__( 'meta descriptionを出力する', 'ystandard' ),
					self::get_boolean_setting_value( $meta_description )
				)
			),
		];
		$available = array_flip( Post_Meta::get_available_state_fields( $post_type ) );

		return array_intersect_key( $settings, $available );
	}

	/**
	 * 本文上部タクソノミーのテーマ設定を取得する.
	 *
	 * @param string $post_type          投稿タイプ.
	 * @param string $fallback_post_type 代替投稿タイプ.
	 *
	 * @return bool
	 */
	private static function is_header_taxonomy_enabled( $post_type, $fallback_post_type ) {
		$setting = "ys_{$post_type}_header_taxonomy";
		// v5設定が保存されている場合は、タクソノミーの選択状態を表示状態として扱う.
		if ( Option::exists_option( $setting ) ) {
			$enabled = 'none' !== Option::get_option( $setting, 'none' );
		} else {
			// 新設定が未保存の場合は、v4までの表示設定を補足へ反映する.
			$enabled = Option::get_option_by_bool( "ys_show_{$fallback_post_type}_header_category", true );
		}
		$filter = apply_filters( "ys_show_{$post_type}_header_taxonomy", null );

		return is_null( $filter ) ? $enabled : Convert::to_bool( $filter );
	}

	/**
	 * 本文下部タクソノミーのテーマ設定を取得する.
	 *
	 * @param string $post_type          投稿タイプ.
	 * @param string $fallback_post_type 代替投稿タイプ.
	 *
	 * @return bool
	 */
	private static function is_footer_taxonomy_enabled( $post_type, $fallback_post_type ) {
		$post_type_taxonomies = Taxonomy_Utils::get_post_type_taxonomies( $post_type );
		$taxonomies           = $post_type_taxonomies ? array_keys( $post_type_taxonomies ) : [];
		$has_setting          = false;
		$enabled              = false;
		foreach ( $taxonomies as $taxonomy ) {
			$setting = "ys_{$post_type}_footer_taxonomy_{$taxonomy}";
			// 1件でも保存済みであれば、v5の投稿タイプ別設定一式を使用する.
			if ( Option::exists_option( $setting ) ) {
				$has_setting = true;
			}
			// 未保存の個別設定はカスタマイザーの既定値ONとして扱う.
			if ( Option::get_option_by_bool( $setting, true ) ) {
				$enabled = true;
			}
		}
		// v5設定が未保存の場合は、v4までの表示設定を補足へ反映する.
		if ( ! $has_setting ) {
			$enabled = Option::get_option_by_bool( "ys_show_{$fallback_post_type}_category", true );
		}
		$filter = apply_filters( "ys_is_active_{$post_type}_taxonomy", null );

		return is_null( $filter ) ? $enabled : Convert::to_bool( $filter );
	}

	/**
	 * 表示用テーマ設定を作成する.
	 *
	 * @param string $description 説明.
	 *
	 * @return array
	 */
	private static function theme_setting( $description ) {
		return [
			'description' => (string) $description,
		];
	}

	/**
	 * テーマ設定へ従う説明を作成する.
	 *
	 * @param string $setting_name  設定名.
	 * @param string $setting_value 設定値.
	 *
	 * @return string
	 */
	private static function get_theme_setting_description( $setting_name, $setting_value ) {
		return sprintf(
			/* translators: 1: theme setting name, 2: current theme setting value. */
			__( '「-」を選択した場合、テーマ設定「%1$s」の「%2$s」に従います。', 'ystandard' ),
			$setting_name,
			$setting_value
		);
	}

	/**
	 * 真偽値のテーマ設定を表示用の値へ変換する.
	 *
	 * @param bool $enabled 有効状態.
	 *
	 * @return string
	 */
	private static function get_boolean_setting_value( $enabled ) {
		return $enabled ? __( 'ON', 'ystandard' ) : __( 'OFF', 'ystandard' );
	}

	/**
	 * 目次表示位置のラベルを取得する.
	 *
	 * @param string $type 表示タイプ.
	 *
	 * @return string
	 */
	private static function get_toc_display_label( $type ) {
		$labels = [
			'content' => __( '本文内', 'ystandard' ),
			'widget'  => __( 'ウィジェット', 'ystandard' ),
			'none'    => __( '表示しない', 'ystandard' ),
		];

		return isset( $labels[ $type ] ) ? $labels[ $type ] : $type;
	}

	/**
	 * シェアボタン形式のラベルを取得する.
	 *
	 * @param string $type 表示タイプ.
	 *
	 * @return string
	 */
	private static function get_share_type_label( $type ) {
		$labels = Share_Button::get_share_button_type();
		// テーマ側の選択肢にない値は保存値をそのまま表示する.
		if ( ! isset( $labels[ $type ] ) ) {
			return $type;
		}

		return $labels[ $type ];
	}

	/**
	 * 投稿日・更新日の表示ラベルを取得する.
	 *
	 * @param mixed $type 表示タイプ.
	 *
	 * @return string
	 */
	private static function get_publish_date_label( $type ) {
		$labels = [
			'none'    => __( 'OFF', 'ystandard' ),
			'publish' => __( '投稿日', 'ystandard' ),
			'update'  => __( '更新日', 'ystandard' ),
			'both'    => __( '投稿日・更新日', 'ystandard' ),
		];
		$type   = false === $type ? 'none' : (string) $type;

		return isset( $labels[ $type ] ) ? $labels[ $type ] : $type;
	}
}

new Block_Editor_Post_Settings();
