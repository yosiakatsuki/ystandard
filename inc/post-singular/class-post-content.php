<?php
/**
 * 投稿・固定ページ・投稿タイプ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\Post;
use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Content
 *
 * 投稿・固定ページ・投稿タイプ関連
 * カスタマイザー設定は inc/post-types/class-post-type-customizer.php
 */
class Post_Content {

	/**
	 * インスタンス
	 *
	 * @var Post_Content
	 */
	private static $instance;

	/**
	 * インスタンス取得
	 *
	 * @return Post_Content
	 */
	public static function get_instance(): Post_Content {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Post_Content constructor.
	 */
	private function __construct() {
		add_action( 'wp', [ $this, 'set_singular_action_hook' ] );
		add_filter( 'post_class', [ $this, 'post_class' ] );
		add_filter( 'get_the_excerpt', [ $this, 'get_the_excerpt' ], 10, 2 );
		add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 999 );
		add_filter( 'document_title_separator', [ $this, 'title_separator' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_custom_property' ] );
	}

	/**
	 * コンテンツ関連のアクション登録
	 */
	public function set_singular_action_hook(): void {
		// 記事上・記事下のアクションセット.
		do_action( 'ys_set_singular_content' );
	}

	/**
	 * 本文用のカスタムプロパティ追加
	 *
	 * @param array $vars CSSカスタムプロパティ設定.
	 *
	 * @return array
	 */
	public function add_custom_property( array $vars ): array {
		// 投稿本文エリア背景色設定.
		$content_background = $this->get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$vars['--ystd--content--background'] = $content_background;
		}

		return $vars;
	}

	/**
	 * Post Classを操作する
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function post_class( $classes ) {
		// 構造化データ「hentry」の削除.
		if ( apply_filters( 'ystd_remove_hentry', true ) ) {
			$classes = array_diff( $classes, [ 'hentry' ] );
		}
		// 投稿本文エリア背景色設定.
		$content_background = $this->get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$classes[] = 'has-content-background';
		}

		return $classes;
	}


	/**
	 * 投稿本文エリア背景色設定取得.
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return string
	 */
	public static function get_content_background_color( string $post_type ): string {
		// 設定存在チェック、設定がなければ代替の投稿タイプを取得.
		if ( Option::exists_option( "ys_{$post_type}_use_content_bg" ) ) {
			$post_type = Post_Type::get_fallback_post_type( $post_type );
		}

		$use_bg = Option::get_option_by_bool( "ys_{$post_type}_use_content_bg" );
		$color  = Option::get_option( "ys_{$post_type}_content_bg", '' );
		// 背景色を使わない設定であればcolorを空白にする.
		if ( ! $use_bg ) {
			$color = '';
		}

		return apply_filters( 'ys_get_content_background_color', $color, $post_type );
	}

	/**
	 * 投稿日・更新日データ取得
	 *
	 * @return array|bool
	 */
	public static function get_post_date_data() {

		$text_format     = get_option( 'date_format' );
		$datetime_format = 'Y-m-d';
		$result          = [];
		/**
		 * 設定取得
		 */
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_show_{$post_type}_publish_date", null );
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$option   = Option::get_option( "ys_show_{$fallback}_publish_date", 'both' );
		} else {
			$option = $filter;
		}

		if ( 'none' === $option || false === $option ) {
			return false;
		}
		if ( Convert::to_bool( Post_Type::get_post_meta( 'ys_hide_publish_date' ) ) ) {
			return false;
		}
		// 更新日取得.
		if ( 'publish' !== $option ) {
			if ( get_the_time( 'Ymd' ) < get_the_modified_time( 'Ymd' ) ) {
				$icon     = 'update' === $option ? 'calendar' : 'rotate-cw';
				$result[] = [
					'text'     => get_the_modified_time( $text_format ),
					'datetime' => get_the_modified_time( $datetime_format ),
					'time'     => true,
					'icon'     => Icon::get_icon( $icon ),
				];
			}
		}
		// 投稿日取得.
		if ( 'update' !== $option || empty( $result ) ) {
			$time     = empty( $result ) ? true : false;
			$result[] = [
				'text'     => get_the_time( $text_format ),
				'datetime' => get_the_time( $datetime_format ),
				'time'     => $time,
				'icon'     => Icon::get_icon( 'calendar' ),
			];
		}

		return array_reverse( $result );
	}

	/**
	 * 投稿タイトル
	 *
	 * @return void
	 */
	public static function singular_title() {
		ob_start();
		Template::get_template_part( 'template-parts/parts/post-title' );
		echo ob_get_clean();
	}

	/**
	 * 投稿メタ情報
	 */
	public static function singular_meta() {
		$date = '';
		// 投稿日・更新日.
		$post_date = self::get_post_date_data();
		if ( ! empty( $post_date ) ) {
			ob_start();
			Template::get_template_part(
				'template-parts/parts/post-date',
				'',
				[ 'post_date' => $post_date ]
			);
			$date = ob_get_clean();
		}
		// カテゴリー.
		$cat = Post_Header::get_post_header_category();

		$header_meta = sprintf(
			'<div class="singular-header__meta">%s%s</div>',
			$date,
			$cat
		);

		echo apply_filters( 'ys_singular_header_meta', $header_meta );
	}

	/**
	 * Hook:get_the_excerpt
	 *
	 * @param string $content excerpt.
	 * @param \WP_Post $post Post.
	 */
	public function get_the_excerpt( $content, $post ) {
		return Post::get_custom_excerpt( ' …', 0, $post->ID );
	}

	/**
	 * 投稿抜粋文字数
	 *
	 * @param int $length 抜粋文字数.
	 *
	 * @return int
	 */
	public function excerpt_length( $length = null ) {

		return Option::get_option_by_int( 'ys_option_excerpt_length', 80 );
	}

	/**
	 * ブログ名区切り文字設定
	 *
	 * @param string $sep 区切り文字.
	 *
	 * @return string
	 */
	public function title_separator( $sep ) {
		$sep_option = Option::get_option( 'ys_title_separate', '' );
		if ( '' !== $sep_option ) {
			$sep = $sep_option;
		}

		return $sep;
	}
}

Post_Content::get_instance();
