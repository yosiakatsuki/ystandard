<?php
/**
 * 投稿設定メタ.
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
 * Class Post_Meta
 *
 * @package ystandard
 */
class Post_Meta {

	/**
	 * 新しい投稿設定のメタキー.
	 */
	const META_KEY = '_ys_post_settings';

	/**
	 * 投稿設定の保存形式バージョン.
	 */
	const SETTINGS_VERSION = 1;

	/**
	 * Post_Meta constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_meta' ], 20 );
		add_filter( 'get_post_metadata', [ $this, 'filter_legacy_meta_value' ], 10, 5 );
	}

	/**
	 * 3状態設定の定義を取得する.
	 *
	 * @return array
	 */
	public static function get_state_fields() {
		return [
			'post_thumbnail'       => [
				'legacy_key'       => '',
				'legacy_state'     => '',
				'requires_support' => 'thumbnail',
			],
			'header_taxonomy'      => [
				'legacy_key'        => '',
				'legacy_state'      => '',
				'requires_taxonomy' => true,
			],
			'footer_taxonomy'      => [
				'legacy_key'        => '',
				'legacy_state'      => '',
				'requires_taxonomy' => true,
			],
			'advertisement'        => [
				'legacy_key'   => 'ys_hide_ad',
				'legacy_state' => 'off',
				'post_types'   => [],
			],
			'toc'                  => [
				'legacy_key'   => 'ys_hide_toc',
				'legacy_state' => 'off',
				'post_types'   => [],
			],
			'share_buttons_header' => [
				'legacy_key'   => 'ys_hide_share',
				'legacy_state' => 'off',
				'post_types'   => [],
			],
			'share_buttons_footer' => [
				'legacy_key'   => 'ys_hide_share',
				'legacy_state' => 'off',
				'post_types'   => [],
			],
			'publish_date'         => [
				'legacy_key'   => 'ys_hide_publish_date',
				'legacy_state' => 'off',
				'post_types'   => [],
			],
			'author'               => [
				'legacy_key'   => 'ys_hide_author',
				'legacy_state' => 'off',
				'post_types'   => [],
			],
			'related_posts'        => [
				'legacy_key'       => 'ys_hide_related',
				'legacy_state'     => 'off',
				'requires_archive' => true,
			],
			'paging'               => [
				'legacy_key'       => 'ys_hide_paging',
				'legacy_state'     => 'off',
				'requires_archive' => true,
			],
			'noindex'              => [
				'legacy_key'   => 'ys_noindex',
				'legacy_state' => 'on',
				'post_types'   => [],
			],
			'meta_description'     => [
				'legacy_key'   => 'ys_hide_meta_dscr',
				'legacy_state' => 'off',
				'post_types'   => [],
			],
		];
	}

	/**
	 * 文字列設定の定義を取得する.
	 *
	 * @return array
	 */
	public static function get_string_fields() {
		return [
			'ogp_title'       => [
				'legacy_key' => 'ys_ogp_title',
				'sanitize'   => 'sanitize_text_field',
			],
			'ogp_description' => [
				'legacy_key' => 'ys_ogp_description',
				'sanitize'   => [ __CLASS__, 'sanitize_ogp_description' ],
			],
		];
	}

	/**
	 * 投稿メタをREST APIへ登録する.
	 */
	public function register_post_meta() {
		foreach ( [ 'post', 'page' ] as $post_type ) {
			// コア投稿タイプではREST経由のメタ保存を確実に利用できるようにする.
			if ( ! post_type_supports( $post_type, 'custom-fields' ) ) {
				add_post_type_support( $post_type, 'custom-fields' );
			}
		}

		foreach ( self::get_supported_post_types() as $post_type ) {
			register_post_meta(
				$post_type,
				self::META_KEY,
				[
					'type'              => 'object',
					'single'            => true,
					'show_in_rest'      => [
						'schema' => self::get_rest_schema( $post_type ),
					],
					'sanitize_callback' => [ __CLASS__, 'sanitize_settings' ],
					'auth_callback'     => [ __CLASS__, 'can_edit_post_meta' ],
				]
			);
			add_action( "rest_after_insert_{$post_type}", [ $this, 'do_rest_save_actions' ], 10, 3 );
		}
	}

	/**
	 * 投稿設定を利用できる投稿タイプを取得する.
	 *
	 * @return array
	 */
	public static function get_supported_post_types() {
		$post_types = array_keys(
			Post_Type::get_post_types(
				[ 'show_in_rest' => true ],
				[ 'ys-parts' ]
			)
		);
		$post_types = apply_filters( 'ys_post_settings_post_types', $post_types );
		$result     = [];

		// フィルターから不正な型が返った場合は安全のため投稿設定を登録しない.
		if ( ! is_array( $post_types ) ) {
			return $result;
		}

		foreach ( array_unique( $post_types ) as $post_type ) {
			// 他者の投稿タイプへテーマからcustom-fieldsサポートを追加しない.
			if ( ! is_string( $post_type ) || ! self::is_supported_post_type( $post_type ) ) {
				continue;
			}
			$result[] = $post_type;
		}

		return $result;
	}

	/**
	 * 投稿タイプが投稿設定に対応しているか判定する.
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return bool
	 */
	public static function is_supported_post_type( $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		// RESTに公開されていない投稿タイプではエディターからメタを保存できない.
		if ( ! $post_type_object || ! $post_type_object->show_in_rest ) {
			return false;
		}
		// Classic Editorでは新しいモーダルを提供しない.
		if ( ! use_block_editor_for_post_type( $post_type ) ) {
			return false;
		}

		return post_type_supports( $post_type, 'custom-fields' );
	}

	/**
	 * 投稿タイプ用のRESTスキーマを取得する.
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return array
	 */
	public static function get_rest_schema( $post_type ) {
		$properties = [
			'version' => [
				'type' => 'integer',
				'enum' => [ self::SETTINGS_VERSION ],
			],
		];
		foreach ( self::get_state_fields() as $key => $field ) {
			// 投稿専用項目を固定ページやカスタム投稿タイプへ公開しない.
			if ( ! self::is_field_available_for_post_type( $field, $post_type ) ) {
				continue;
			}
			$properties[ $key ] = [
				'type' => 'string',
				'enum' => [ 'off', 'on' ],
			];
		}
		foreach ( array_keys( self::get_string_fields() ) as $key ) {
			$properties[ $key ] = [ 'type' => 'string' ];
		}

		return [
			'type'                 => 'object',
			'properties'           => $properties,
			'required'             => [ 'version' ],
			'additionalProperties' => false,
		];
	}

	/**
	 * フィールドを投稿タイプで利用できるか判定する.
	 *
	 * @param array  $field     フィールド定義.
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return bool
	 */
	public static function is_field_available_for_post_type( $field, $post_type ) {
		// 特定機能を必要とする項目は、投稿タイプがその機能を持つ場合だけ表示する.
		if ( ! empty( $field['requires_support'] ) && ! post_type_supports( $post_type, $field['requires_support'] ) ) {
			return false;
		}
		// タクソノミー表示設定は、公開タクソノミーを持つ投稿タイプだけで使用する.
		if ( ! empty( $field['requires_taxonomy'] ) && ! Taxonomy_Utils::get_post_type_taxonomies( $post_type ) ) {
			return false;
		}
		// 関連記事と前後記事は、投稿またはアーカイブを持つ投稿タイプだけで使用する.
		if ( ! empty( $field['requires_archive'] ) ) {
			$post_type_object = get_post_type_object( $post_type );

			return 'post' === $post_type || ( $post_type_object && $post_type_object->has_archive );
		}

		return empty( $field['post_types'] ) || in_array( $post_type, $field['post_types'], true );
	}

	/**
	 * 投稿タイプで利用できる3状態設定のキーを取得する.
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return array
	 */
	public static function get_available_state_fields( $post_type ) {
		$result = [];
		foreach ( self::get_state_fields() as $key => $field ) {
			// 投稿タイプで利用できる項目だけをエディターへ公開する.
			if ( self::is_field_available_for_post_type( $field, $post_type ) ) {
				$result[] = $key;
			}
		}

		return $result;
	}

	/**
	 * 新形式の投稿設定か判定する.
	 *
	 * @param mixed $settings 投稿設定.
	 *
	 * @return bool
	 */
	public static function is_valid_settings( $settings ) {
		return is_array( $settings )
			&& isset( $settings['version'] )
			&& self::SETTINGS_VERSION === (int) $settings['version'];
	}

	/**
	 * 投稿設定をサニタイズし、保存不要なプロパティを除去する.
	 *
	 * @param mixed $value 入力値.
	 *
	 * @return array
	 */
	public static function sanitize_settings( $value ) {
		// 対応バージョンを持たない値は旧設定フォールバックを妨げないよう保存しない.
		if ( ! self::is_valid_settings( $value ) ) {
			return [];
		}

		// 分割前のv5開発版で保存したシェアボタン設定は上下両方へ引き継ぐ.
		if ( isset( $value['share_buttons'] ) && in_array( $value['share_buttons'], [ 'off', 'on' ], true ) ) {
			$value['share_buttons_header'] = isset( $value['share_buttons_header'] ) ? $value['share_buttons_header'] : $value['share_buttons'];
			$value['share_buttons_footer'] = isset( $value['share_buttons_footer'] ) ? $value['share_buttons_footer'] : $value['share_buttons'];
		}

		$result = [ 'version' => self::SETTINGS_VERSION ];
		foreach ( array_keys( self::get_state_fields() ) as $key ) {
			// テーマ設定を使う項目はプロパティを持たず、明示上書きだけを保存する.
			if ( isset( $value[ $key ] ) && in_array( $value[ $key ], [ 'off', 'on' ], true ) ) {
				$result[ $key ] = $value[ $key ];
			}
		}
		foreach ( self::get_string_fields() as $key => $field ) {
			// 未入力のOGP設定は空文字を保存せず、オブジェクトを小さく保つ.
			if ( ! isset( $value[ $key ] ) || ! is_scalar( $value[ $key ] ) ) {
				continue;
			}
			$sanitized = call_user_func( $field['sanitize'], (string) $value[ $key ] );
			// サニタイズ後に空になった設定も保存対象から外す.
			if ( '' === $sanitized ) {
				continue;
			}
			$result[ $key ] = $sanitized;
		}

		return $result;
	}

	/**
	 * OGP descriptionをサニタイズする.
	 *
	 * @param mixed $value 入力値.
	 *
	 * @return string
	 */
	public static function sanitize_ogp_description( $value ) {
		$value = is_scalar( $value ) ? (string) $value : '';

		return sanitize_text_field( wp_strip_all_tags( $value, true ) );
	}

	/**
	 * 保存済みの新形式設定を取得する.
	 *
	 * @param int $post_id 投稿ID.
	 *
	 * @return array
	 */
	public static function get_saved_settings( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
		// 投稿を特定できない場面では空の設定を返す.
		if ( ! $post_id ) {
			return [];
		}
		$settings = get_post_meta( $post_id, self::META_KEY, true );

		return self::is_valid_settings( $settings ) ? self::sanitize_settings( $settings ) : [];
	}

	/**
	 * 旧投稿メタを新形式へ変換する.
	 *
	 * @param int $post_id 投稿ID.
	 *
	 * @return array
	 */
	public static function get_legacy_settings( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
		$result  = [ 'version' => self::SETTINGS_VERSION ];
		// 投稿を特定できない場面では変換対象がない.
		if ( ! $post_id ) {
			return $result;
		}
		$post_type = get_post_type( $post_id );

		foreach ( self::get_state_fields() as $key => $field ) {
			// 投稿タイプで利用しない旧設定は新形式へ持ち込まない.
			if ( ! self::is_field_available_for_post_type( $field, $post_type ) ) {
				continue;
			}
			// 旧メタキーを持たない新規項目は未設定のままテーマ設定へ従う.
			if ( empty( $field['legacy_key'] ) ) {
				continue;
			}
			// 旧フラグが有効な項目だけを新形式の明示状態へ変換する.
			if ( Convert::to_bool( get_post_meta( $post_id, $field['legacy_key'], true ) ) ) {
				$result[ $key ] = $field['legacy_state'];
			}
		}
		foreach ( self::get_string_fields() as $key => $field ) {
			$value = get_post_meta( $post_id, $field['legacy_key'], true );
			// 空の旧文字列は新形式へ持ち込まず、未入力として扱う.
			if ( ! is_scalar( $value ) ) {
				continue;
			}
			$value = call_user_func( $field['sanitize'], (string) $value );
			// サニタイズ後に内容が残る文字列だけを引き継ぐ.
			if ( '' !== $value ) {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * フロント表示で使用する投稿設定を取得する.
	 *
	 * @param int $post_id 投稿ID.
	 *
	 * @return array
	 */
	public static function get_settings( $post_id = 0 ) {
		$saved = self::get_saved_settings( $post_id );
		// 有効な新形式があれば、個別プロパティがなくても旧設定へ戻らない.
		if ( self::is_valid_settings( $saved ) ) {
			return $saved;
		}

		return self::get_legacy_settings( $post_id );
	}

	/**
	 * 投稿単位の3状態設定を取得する.
	 *
	 * @param string $key     設定キー.
	 * @param int    $post_id 投稿ID.
	 *
	 * @return string default、off、onのいずれか.
	 */
	public static function get_state( $key, $post_id = 0 ) {
		$settings = self::get_settings( $post_id );
		// 明示的な上書きだけを3状態設定として返す.
		if ( isset( $settings[ $key ] ) && in_array( $settings[ $key ], [ 'off', 'on' ], true ) ) {
			return $settings[ $key ];
		}

		return 'default';
	}

	/**
	 * 3状態設定から最終的な有効状態を取得する.
	 *
	 * @param string $key           設定キー.
	 * @param bool   $theme_default テーマ設定による状態.
	 * @param int    $post_id       投稿ID.
	 *
	 * @return bool
	 */
	public static function resolve_state( $key, $theme_default, $post_id = 0 ) {
		$state = self::get_state( $key, $post_id );
		// 投稿単位でOFFが指定されていればテーマ設定より優先する.
		if ( 'off' === $state ) {
			return false;
		}
		// 投稿単位でONが指定されていればテーマ設定より優先する.
		if ( 'on' === $state ) {
			return true;
		}

		return (bool) $theme_default;
	}

	/**
	 * 新形式または旧形式から文字列設定を取得する.
	 *
	 * @param string $key     設定キー.
	 * @param int    $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_string( $key, $post_id = 0 ) {
		$settings = self::get_settings( $post_id );
		// 未設定の文字列はテーマ標準の自動生成へ委ねる.
		if ( ! isset( $settings[ $key ] ) || ! is_string( $settings[ $key ] ) ) {
			return '';
		}

		return $settings[ $key ];
	}

	/**
	 * 新形式がある投稿では旧メタキーの読み取り結果を新形式へ合わせる.
	 *
	 * @param mixed  $value     事前に返された値.
	 * @param int    $object_id 投稿ID.
	 * @param string $meta_key  メタキー.
	 * @param bool   $single    単一値を返すか.
	 * @param string $meta_type メタタイプ.
	 *
	 * @return mixed
	 */
	public function filter_legacy_meta_value( $value, $object_id, $meta_key, $single, $meta_type ) {
		// 先行フィルターの値と投稿以外のメタ取得には干渉しない.
		if ( null !== $value || 'post' !== $meta_type ) {
			return $value;
		}
		$matched_key   = '';
		$matched_field = [];
		$matched_type  = '';
		foreach ( self::get_state_fields() as $key => $field ) {
			// 旧メタキーを持たない新規項目は互換フィルターの対象外にする.
			if ( empty( $field['legacy_key'] ) ) {
				continue;
			}
			// 取得対象と一致する旧フラグの定義だけを保持する.
			if ( $field['legacy_key'] === $meta_key ) {
				$matched_key   = $key;
				$matched_field = $field;
				$matched_type  = 'state';
				break;
			}
		}
		// 旧フラグでなければ旧OGP文字列との一致を確認する.
		if ( '' === $matched_key ) {
			foreach ( self::get_string_fields() as $key => $field ) {
				// 取得対象と一致する旧文字列の定義だけを保持する.
				if ( $field['legacy_key'] === $meta_key ) {
					$matched_key   = $key;
					$matched_field = $field;
					$matched_type  = 'string';
					break;
				}
			}
		}
		// 投稿設定と無関係なメタキーは通常の取得処理へ戻す.
		if ( '' === $matched_key ) {
			return $value;
		}
		$settings = self::get_saved_settings( $object_id );
		// 新形式がない投稿ではデータベース上の旧値をそのまま使用する.
		if ( ! self::is_valid_settings( $settings ) ) {
			return $value;
		}
		// 旧シェアボタン設定は上下ともOFFの場合だけ「すべて非表示」として返す.
		if ( 'ys_hide_share' === $meta_key ) {
			$is_hidden    = 'off' === self::get_state( 'share_buttons_header', $object_id )
				&& 'off' === self::get_state( 'share_buttons_footer', $object_id );
			$legacy_value = $is_hidden ? '1' : '';

			return $single ? $legacy_value : [ $legacy_value ];
		}
		// 旧フラグは新形式で同じ効果を持つ状態だけを1として返す.
		if ( 'state' === $matched_type ) {
			$legacy_value = isset( $settings[ $matched_key ] ) && $matched_field['legacy_state'] === $settings[ $matched_key ] ? '1' : '';
		} else {
			// 旧OGPキーは新形式の文字列を返し、未入力時は空として扱う.
			$legacy_value = isset( $settings[ $matched_key ] ) ? $settings[ $matched_key ] : '';
		}

		return $single ? $legacy_value : [ $legacy_value ];
	}

	/**
	 * 投稿メタを編集できるか判定する.
	 *
	 * @param bool   $allowed   許可状態.
	 * @param string $meta_key  メタキー.
	 * @param int    $object_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function can_edit_post_meta( $allowed, $meta_key, $object_id ) {
		return current_user_can( 'edit_post', $object_id );
	}

	/**
	 * REST保存後に既存の保存アクションを実行する.
	 *
	 * @param \WP_Post         $post     投稿オブジェクト.
	 * @param \WP_REST_Request $request  RESTリクエスト.
	 * @param bool             $creating 新規作成か.
	 */
	public function do_rest_save_actions( $post, $request, $creating ) {
		do_action( 'ys_save_post_meta_seo', $post->ID );
		do_action( 'ys_save_post_meta_sns', $post->ID );
		do_action( 'ys_save_post_meta_post', $post->ID );
	}
}

new Post_Meta();
