<?php
/**
 * アーカイブ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;
use ystandard\utils\Sanitize;
use ystandard\utils\Post;

defined( 'ABSPATH' ) || die();

/**
 * Class Archive
 *
 * @package ystandard
 */
class Archive {

	/**
	 * インスタンス
	 *
	 * @var Archive
	 */
	private static $instance;

	/**
	 * コンストラクタ
	 * privateにして外部からのインスタンス化を防ぐ.
	 */
	private function __construct() {
		add_filter( 'get_the_archive_title', [ $this, 'archive_title' ] );
		add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
		add_filter( 'ys_get_css_custom_properties_args_presets', [ $this, 'add_custom_properties' ] );
		add_filter( 'get_the_archive_description', [ $this, 'archive_description' ], 999 );
		add_action( 'ys_after_site_header', [ $this, 'home_post_thumbnail' ] );
	}

	/**
	 * インスタンス取得
	 *
	 * @return Archive
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * アーカイブタイトル
	 *
	 * @param string $title title.
	 *
	 * @return string
	 */
	public function archive_title( $title ) {
		/**
		 * 標準フォーマット
		 */
		$title_format = apply_filters( 'ys_archive_title_format', '%s' );
		/**
		 * ページング
		 */
		$paged = get_query_var( 'paged' );

		if ( is_category() ) {
			$title = sprintf( $title_format, single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( $title_format, single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( $title_format, get_the_author() );
		} elseif ( is_search() ) {
			/* translators: %s: Search Keywords. */
			$title_format = __( 'Search Results for "%s"', 'ystandard' );
			$title        = sprintf( $title_format, esc_html( get_search_query( false ) ) );
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( $title_format, post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$title = sprintf( '%s', single_term_title( '', false ) );
		} elseif ( is_home() ) {
			if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
				$title = get_the_title( get_option( 'page_for_posts' ) );
			} else {
				$title = '';
			}
		}

		return apply_filters( 'ys_get_the_archive_title', $title, $paged );
	}

	/**
	 * アーカイブURL
	 */
	public static function get_archive_url() {
		$url            = '';
		$queried_object = get_queried_object();
		if ( is_category() ) {
			$url = get_category_link( $queried_object->term_id );
		} elseif ( is_tag() ) {
			$url = get_tag_link( $queried_object->term_id );
		} elseif ( is_author() ) {
			$author_id = get_query_var( 'author' );
			$url       = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) );
		} elseif ( is_search() ) {
			$url = home_url( '/?s=' . urlencode( get_search_query( false ) ) );
			$url = get_post_type_archive_link( esc_url_raw( $url ) );
		} elseif ( is_post_type_archive() ) {
			$post_type = Post_Type::get_post_type();
			$url       = get_post_type_archive_link( $post_type );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( $queried_object->taxonomy );
			$url = get_term_link( $queried_object->term_id, $tax->name );
		}

		return apply_filters( 'ys_get_the_archive_url', $url );
	}

	/**
	 * アーカイブアイテムクラス作成
	 *
	 * @param string|string[] $class Class.
	 *
	 * @return array
	 */
	public static function get_archive_item_class( $class = '' ) {
		$classes = [];
		/**
		 * 共通でセットするクラス
		 */
		$classes[] = 'archive__item';
		$classes[] = 'is-' . self::get_archive_type();
		if ( ! empty( $class ) ) {
			$class     = is_array( $class ) ? implode( ' ', $class ) : $class;
			$classes[] = $class;
		}

		return apply_filters( 'get_archive_item_class', $classes );
	}


	/**
	 * アーカイブタイプ取得
	 *
	 * @return string
	 */
	public static function get_archive_type() {
		$post_type = Post_Type::get_post_type();

		return apply_filters(
			'ys_get_archive_type',
			Option::get_option( "ys_{$post_type}_archive_type", 'card' )
		);
	}

	/**
	 * アーカイブ画像の縦横比取得
	 *
	 * @return string
	 */
	public static function get_archive_image_ratio() {
		return apply_filters( 'ys_archive_image_ratio', 'is-16-9' );
	}

	/**
	 * アーカイブデフォルト画像
	 *
	 * @param string $class Class.
	 * @param string $icon_class Icon Class.
	 * @param string $thumbnail_size Thumbnail size.
	 *
	 * @return string
	 */
	public static function get_archive_default_image( $class = 'archive__no-img', $icon_class = 'archive__image', $thumbnail_size = 'full' ) {
		$image = '<div class="' . $class . '">' . Icon::get_icon( 'image', $icon_class ) . '</div>';

		$thumbnail_size = apply_filters( 'ys_get_archive_default_image_size', $thumbnail_size );

		return apply_filters( 'ys_get_archive_default_image', $image, $class, $icon_class, $thumbnail_size );
	}

	/**
	 * アーカイブメタ情報取得
	 */
	public static function the_archive_meta() {

		$date = self::get_archive_detail_date();
		$cat  = self::get_archive_detail_category();

		if ( empty( $date ) && empty( $cat ) ) {
			return;
		}
		printf(
			'<div class="archive__meta">%s%s</div>',
			$date,
			$cat
		);
	}

	/**
	 * 投稿抜粋
	 *
	 * @param int $length Length.
	 *
	 * @return string
	 */
	public static function the_archive_description( $length = 0 ) {
		$post_type = Post_Type::get_post_type();
		if ( ! Option::get_option_by_bool( "ys_show_{$post_type}_archive_description", true ) ) {
			return '';
		}
		if ( 0 === $length ) {
			$length = Option::get_option_by_int( "ys_{$post_type}_archive_excerpt_length", 80 );
		}
		$excerpt = Post::get_custom_excerpt( '…', $length );
		if ( empty( $excerpt ) ) {
			return '';
		}
		printf(
			'<p class="archive__excerpt">%s</p>',
			$excerpt
		);
	}

	/**
	 * 日付取得
	 *
	 * @param string|boolean $icon Icon.
	 *
	 * @return string
	 */
	public static function get_archive_detail_date( $icon = true ) {
		$post_type = Post_Type::get_post_type();
		if ( ! Option::get_option_by_bool( "ys_show_{$post_type}_archive_publish_date", true ) ) {
			return '';
		}

		$format    = '<div class="archive__date">%s<time class="updated" datetime="%s">%s</time></div>';
		$date_icon = Icon::get_icon( 'calendar' );
		if ( ! empty( $icon ) && is_string( $icon ) ) {
			$date_icon = $icon;
		}
		if ( empty( $icon ) ) {
			$date_icon = '';
		}
		$date_time   = get_the_date( 'Y-m-d' );
		$date_format = get_option( 'date_format' );
		$date_label  = get_the_date( $date_format );
		$date        = sprintf( $format, $date_icon, $date_time, $date_label );

		return apply_filters( 'ys_get_archive_detail_date', $date, $format, $date_icon, $date_format );

	}

	/**
	 * カテゴリー
	 *
	 * @param string|boolean $icon Icon.
	 *
	 * @return string
	 */
	public static function get_archive_detail_category( $icon = true ) {
		$post_type = Post_Type::get_post_type();
		if ( ! Option::get_option_by_bool( "ys_show_{$post_type}_archive_category", true ) ) {
			return '';
		}

		$taxonomies = self::get_archive_meta_taxonomy();
		if ( ! $taxonomies ) {
			return '';
		}
		if ( is_string( $taxonomies ) ) {
			$taxonomies = [ $taxonomies ];
		}
		$result       = [];
		$terms_length = apply_filters( "ys_get_{$post_type}_archive_category_terms_length", 1 );
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( false, $taxonomy );
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				return '';
			}
			$terms = array_slice( $terms, 0, $terms_length );
			foreach ( $terms as $term ) {
				$category_icon = Taxonomy::get_taxonomy_icon( $taxonomy );
				if ( ! empty( $icon ) && is_string( $icon ) ) {
					$category_icon = $icon;
				}
				if ( empty( $icon ) ) {
					$category_icon = '';
				}

				$result[] = sprintf(
					'<div class="archive__category %s">%s%s</div>',
					esc_attr( $taxonomy ) . '--' . esc_attr( $term->slug ),
					$category_icon,
					esc_html( $term->name )
				);
			}
		}

		return apply_filters(
			"ys_get_{$post_type}_archive_detail_category",
			implode( '', $result ),
			$terms
		);
	}

	/**
	 * アーカイブ用タクソノミー情報取得
	 *
	 * @return array|bool|string
	 */
	public static function get_archive_meta_taxonomy() {
		$taxonomy = false;

		if ( is_tax() ) {
			$taxonomy = get_query_var( 'taxonomy' );
		}
		if ( is_category() ) {
			$taxonomy = 'category';
		}
		if ( is_tag() ) {
			$taxonomy = 'post_tag';
		}

		$taxonomy = apply_filters( "ys_get_{$taxonomy}_archive_taxonomy", $taxonomy );
		if ( ! $taxonomy ) {
			$taxonomies = get_the_taxonomies();
			if ( ! $taxonomies ) {
				return false;
			}
			$taxonomy  = array_key_first( $taxonomies );
			$post_type = Post_Type::get_post_type();
			if ( 'post' === $post_type ) {
				$taxonomy = 'category';
			}
			$taxonomy = apply_filters( "ys_get_{$post_type}_archive_taxonomy", $taxonomy );
		}

		return $taxonomy;
	}

	/**
	 * カテゴリー
	 *
	 * @return string
	 */
	public static function get_archive_detail_read_more() {
		$post_type = Post_Type::get_post_type();
		$read_more = Option::get_option( "ys_{$post_type}_archive_read_more_text", '' );
		if ( ! trim( $read_more ) ) {
			return '';
		}

		$read_more = sprintf(
			'<div class="archive__read-more"><a href="%s">%s</a></div>',
			get_permalink( get_the_ID() ),
			apply_filters( 'ys_get_archive_detail_read_more_text', $read_more, get_the_ID() )
		);

		return apply_filters( 'ys_get_archive_detail_read_more', $read_more, get_the_ID() );
	}

	/**
	 * 説明 2ページ目以降削除
	 *
	 * @param string $description 説明文.
	 *
	 * @return string
	 */
	public function archive_description( $description ) {
		if ( get_query_var( 'paged' ) ) {
			$description = '';
		}

		return $description;
	}

	/**
	 * 一覧ページのアイキャッチ画像表示
	 */
	public function home_post_thumbnail() {
		if ( ! is_home() || 'page' !== get_option( 'show_on_front' ) ) {
			return;
		}
		$page = get_option( 'page_for_posts' );
		if ( ! $page ) {
			return;
		}
		$thumbnail = get_the_post_thumbnail(
			$page,
			'post-thumbnail',
			[
				'id'      => 'site-header-thumbnail__image',
				'class'   => 'site-header-thumbnail__image',
				'alt'     => get_the_title( $page ),
				'loading' => 'eager',
			]
		);
		ob_start();
		Template::get_template_part(
			'template-parts/parts/header-thumbnail',
			'',
			[ 'header_thumbnail' => $thumbnail ]
		);
		echo ob_get_clean();
	}


	/**
	 * CSSカスタムプロパティ追加
	 *
	 * @param array $css_properties CSS properties.
	 *
	 * @return array
	 */
	public function add_custom_properties( $css_properties ) {
		// 背景ありモードの場合.
		if ( Body::has_background() ) {
			// テキストエリアの余白を調整.
			// カードタイプ.
			$css_properties['--ystd--archive--card--item--text--padding'] = 'var(--ystd--archive--item--text--gap)';
			// リストタイプ.
			$css_properties['--ystd--archive--list--item--col--gap']      = '0';
			$css_properties['--ystd--archive--list--item--text--padding'] = 'var(--ystd--archive--gap)';
			$css_properties['--ystd--archive--list--item--image--height'] = '100%';
			// シンプル.
			$css_properties['--ystd--archive--simple--item--padding-x'] = 'var(--ystd--archive--gap)';
		}

		return $css_properties;
	}

}

Archive::get_instance();
