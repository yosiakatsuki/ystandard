<?php
/**
 * [ys]パーツ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\utils\Conditional_Tags;
use ystandard\utils\Convert;

defined( 'ABSPATH' ) || die();

/**
 * Class Parts
 *
 * @package ystandard
 */
class Parts {

	/**
	 * 投稿タイプ
	 */
	const POST_TYPE = 'ys-parts';

	/**
	 * ショートコード
	 */
	const SHORTCODE = 'ys_parts';

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR = [
		'parts_id'          => 0,
		'use_entry_content' => '',
	];

	/**
	 * Constructor.
	 */
	public function __construct() {

		if ( ! shortcode_exists( self::SHORTCODE ) ) {
			add_shortcode( self::SHORTCODE, [ $this, 'do_shortcode' ] );
		}
		// コンテンツ展開のためのフィルターセット.
		$this->set_parts_content_filter();

		add_action( 'init', [ $this, 'register_post_type' ], 9 );
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $attributes Attributes.
	 *
	 * @return string
	 */
	public function do_shortcode( $attributes ) {

		$attributes = shortcode_atts( self::SHORTCODE_ATTR, $attributes );
		/**
		 * ラッパー
		 */
		$wrap = '%s';
		if ( Convert::to_bool( $attributes['use_entry_content'] ) ) {
			$wrap = '<div class="entry-content entry__content">%s</div>';
		}
		/**
		 * コンテンツ作成
		 */
		$parts_id = $attributes['parts_id'];
		if ( is_numeric( $parts_id ) && 0 < $parts_id ) {
			$parts_id = (int) $parts_id;
		} else {
			return $this->shortcode_editor_error( _x( 'パーツIDの指定が不正です。', 'ys-parts', 'ystandard' ) );
		}
		$post = get_post( $parts_id );
		if ( ! $post ) {
			return $this->shortcode_editor_error( _x( 'パーツが見つかりません。', 'ys-parts', 'ystandard' ) );
		}

		// パーツが公開されいない場合に警告.
		if ( 'publish' !== $post->post_status ) {
			$post_title = esc_html( $post->post_title );
			$message    = _x(
				"※パーツが公開されていません。下書きやゴミ箱に入っていないか確認してください。(パーツ名「{$post_title}」)",
				'ys-parts',
				'ystandard'
			);

			return $this->shortcode_editor_error( $message );
		}
		$content = $this->get_parts_content( $post->post_content, $post->ID );

		return sprintf( $wrap, $content );
	}

	/**
	 * パーツのコンテンツ部分取得
	 *
	 * @param string $content Content.
	 * @param int $parts_id Parts ID.
	 *
	 * @return string
	 */
	private function get_parts_content( $content, $parts_id ) {

		do_action( 'ys_parts_content_before', $content, $parts_id );
		// 内容の展開.
		$content = str_replace(
			']]>',
			']]&gt;',
			apply_filters( 'ys_parts_the_content', $content, $parts_id )
		);
		do_action( 'ys_parts_content_after', $content, $parts_id );

		return $content;
	}

	/**
	 * パーツコンテンツのフィルターセット
	 */
	private function set_parts_content_filter() {
		add_filter( 'ys_parts_the_content', 'do_blocks', 9 );
		add_filter( 'ys_parts_the_content', 'wptexturize' );
		add_filter( 'ys_parts_the_content', 'convert_smilies', 20 );
		add_filter( 'ys_parts_the_content', 'shortcode_unautop' );
		add_filter( 'ys_parts_the_content', 'prepend_attachment' );
		add_filter( 'ys_parts_the_content', 'wp_filter_content_tags' );
		add_filter( 'ys_parts_the_content', 'wp_replace_insecure_home_url' );
		add_filter( 'ys_parts_the_content', 'do_shortcode', 11 );
	}

	/**
	 * ショートコードエラー時の編集画面内表示.
	 * Toolboxのパーツブロック使用時に表示される.
	 *
	 * @param string $content 警告本文
	 * @param string $front_content フロント表示内容
	 *
	 * @return string
	 */
	private function shortcode_editor_error( $content, $front_content = '' ) {
		// ブロックエディター内での展開.
		if ( $this->is_block_editor() ) {
			return "<div class='text-fz-xs text-gray-800'>{$content}</div>";
		}

		return $front_content;
	}


	/**
	 * ブロックエディター内で動作しているか判断.
	 *
	 * @return bool
	 */
	private static function is_block_editor() {
		return Conditional_Tags::is_block_editor();
	}

	/**
	 * 投稿タイプの登録
	 */
	public function register_post_type() {
		$labels = [
			'name'               => _x( '[ys]パーツ', 'register_parts', 'ystandard' ),
			'singular_name'      => _x( '[ys]パーツ', 'register_parts', 'ystandard' ),
			'add_new'            => _x( '新規パーツを追加', 'register_parts', 'ystandard' ),
			'add_new_item'       => _x( 'パーツを追加', 'register_parts', 'ystandard' ),
			'edit_item'          => _x( 'パーツを編集', 'register_parts', 'ystandard' ),
			'new_item'           => _x( 'パーツを新規作成', 'register_parts', 'ystandard' ),
			'view_item'          => _x( 'パーツを表示', 'register_parts', 'ystandard' ),
			'search_items'       => _x( 'パーツを検索', 'register_parts', 'ystandard' ),
			'not_found'          => _x( 'パーツが見つかりませんでした', 'register_parts', 'ystandard' ),
			'not_found_in_trash' => _x( 'ゴミ箱にパーツはありません', 'register_parts', 'ystandard' ),
			'item_updated'       => _x( 'パーツを更新しました', 'register_parts', 'ystandard' ),
		];
		register_post_type(
			self::POST_TYPE,
			[
				'labels'                => $labels,
				'public'                => false,
				'exclude_from_search'   => true,
				'publicly_queryable'    => false,
				'show_ui'               => true,
				'show_in_nav_menus'     => false,
				'show_in_menu'          => true,
				'menu_icon'             => Icons_Admin::get_admin_menu_icon(),
				'menu_position'         => 20,
				'description'           => 'サイト内で使用する定型文を登録し、ショートコードで使用できます。',
				'has_archive'           => false,
				'hierarchical'          => true,
				'show_in_rest'          => true,
				'capability_type'       => 'page',
				'rest_base'             => self::POST_TYPE,
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports'              => [
					'title',
					'editor',
					'revisions',
				],
			]
		);
	}
}

new Parts();
