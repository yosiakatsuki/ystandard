<?php
/**
 * Utility
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * ユーティリティークラス
 */
class Utility {

	/**
	 * ページのタイトル部分のみを取得
	 *
	 * @return string
	 */
	public static function get_page_title() {
		$sep       = apply_filters( 'document_title_separator', '-' );
		$sep       = wptexturize( $sep );
		$sep       = convert_chars( $sep );
		$sep       = esc_html( $sep );
		$sep       = capital_P_dangit( $sep );
		$title     = wp_get_document_title();
		$new_title = explode( $sep, $title );
		if ( ! empty( $new_title ) && 1 < count( $new_title ) ) {
			array_pop( $new_title );
			$title = implode( $sep, $new_title );
		}

		return $title;
	}

	/**
	 * ページURL取得
	 *
	 * @return string
	 */
	public static function get_page_url() {
		$protocol = 'https://';
		if ( ! is_ssl() ) {
			$protocol = 'http://';
		}

		return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/**
	 * アイキャッチ画像の画像オブジェクト
	 *
	 * @param int    $post_id int Post ID.
	 * @param string $size    Size.
	 * @param bool   $icon    Icon.
	 *
	 * @return array|false
	 */
	public static function get_post_thumbnail_src( $post_id = 0, $size = 'full', $icon = false ) {
		$post_id       = 0 === $post_id ? get_the_ID() : $post_id;
		$attachment_id = get_post_thumbnail_id( $post_id );

		return wp_get_attachment_image_src( $attachment_id, $size, $icon );
	}

	/**
	 * カスタムロゴIDを取得
	 *
	 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
	 *
	 * @return int
	 */
	public static function get_custom_logo_id( $blog_id = 0 ) {
		$switched_blog = false;

		if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
			switch_to_blog( $blog_id );
			$switched_blog = true;
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( $switched_blog ) {
			restore_current_blog();
		}

		return $custom_logo_id;
	}


	/**
	 * JSON-LD出力
	 *
	 * @param array $data Data.
	 */
	public static function json_ld( $data = [] ) {
		if ( ! is_array( $data ) || empty( $data ) ) {
			return;
		}
		echo '<script type="application/ld+json">' . json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . PHP_EOL;
	}

	/**
	 * テーマバージョン取得
	 *
	 * @param boolean $parent 親テーマ情報かどうか.
	 *
	 * @return string
	 */
	public static function get_theme_version( $parent = false ) {
		/**
		 * 子テーマ情報
		 */
		$theme = wp_get_theme();
		if ( $parent && get_template() !== get_stylesheet() ) {
			/**
			 * 親テーマ情報
			 */
			$theme = wp_get_theme( get_template() );
		}

		return $theme->get( 'Version' );
	}

	/**
	 * テーマ本体のバージョン取得
	 *
	 * @return string
	 */
	public static function get_ystandard_version() {

		return self::get_theme_version( true );
	}

	/**
	 * ファイル内容の取得
	 *
	 * @param string $file ファイルパス.
	 *
	 * @return string
	 */
	public static function file_get_contents( $file ) {
		$content = false;
		if ( self::init_filesystem() ) {
			/**
			 * WP_Filesystem
			 *
			 * @global \WP_Filesystem_Direct $wp_filesystem ;
			 */
			global $wp_filesystem;
			$content = $wp_filesystem->get_contents( $file );
		}

		return $content;
	}

	/**
	 * ファイルシステムの初期化
	 *
	 * @return bool|null
	 */
	public static function init_filesystem() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}

		return WP_Filesystem();
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
	 * HTML・改行・ショートコードなしのテキストを取得
	 *
	 * @param string $content content.
	 *
	 * @return string
	 */
	public static function get_plain_text( $content ) {
		// ショートコード削除.
		$content = strip_shortcodes( $content );
		// HTMLタグ削除.
		$content = wp_strip_all_tags( $content, true );
		// HTMLタグらしき文字の変換.
		$content = htmlspecialchars( $content, ENT_QUOTES );

		return $content;
	}

	/**
	 * Boolに変換
	 *
	 * @param mixed $value 変換する値.
	 *
	 * @return bool
	 */
	public static function to_bool( $value ) {
		if ( true === $value || 'true' === $value || 1 === $value || '1' === $value ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [false]として判定できるか
	 *
	 * @param mixed $value 変換する値.
	 *
	 * @return bool
	 */
	public static function is_false( $value ) {
		if ( 'false' === $value || false === $value || 0 === $value || '0' === $value ) {
			return true;
		}

		return false;
	}

	/**
	 * ユーザーエージェントのチェック
	 *
	 * @param array $ua 対象ユーザーエージェントのリスト.
	 *
	 * @return boolean
	 */
	public static function check_user_agent( $ua ) {
		if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return false;
		}
		$pattern = '/' . implode( '|', $ua ) . '/i';

		return preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
	}

	/**
	 * 投稿タイプ取得
	 *
	 * @param array $args    args.
	 * @param bool  $exclude 除外.
	 *
	 * @return array
	 */
	public static function get_post_types( $args = [], $exclude = true ) {
		$args = array_merge(
			[ 'public' => true ],
			$args
		);

		$types = get_post_types( $args );

		if ( is_array( $exclude ) ) {
			$exclude[] = 'attachment';
			foreach ( $exclude as $item ) {
				unset( $types[ $item ] );
			}
		}

		if ( true === $exclude ) {
			unset( $types['attachment'] );
		}

		foreach ( $types as $key => $value ) {
			$post_type = get_post_type_object( $key );
			if ( $post_type ) {
				$types[ $key ] = $post_type->labels->singular_name;
			}
		}

		return $types;
	}

	/**
	 * タクソノミー取得
	 *
	 * @param array $args    args.
	 * @param bool  $exclude 除外.
	 *
	 * @return array
	 */
	public static function get_taxonomies( $args = [], $exclude = true ) {
		$args       = array_merge(
			[ 'public' => true ],
			$args
		);
		$taxonomies = get_taxonomies( $args );

		if ( is_array( $exclude ) ) {
			$exclude[] = 'post_format';
			foreach ( $exclude as $item ) {
				unset( $taxonomies[ $item ] );
			}
		}

		if ( true === $exclude ) {
			unset( $taxonomies['post_format'] );
		}

		foreach ( $taxonomies as $key => $value ) {
			$taxonomy = get_taxonomy( $key );
			if ( $taxonomy ) {
				$taxonomies[ $key ] = $taxonomy->label;
			}
		}

		return $taxonomies;
	}

	/**
	 * メタ情報として表示するタクソノミー取得
	 *
	 * @return bool|string
	 */
	public static function get_meta_taxonomy() {
		$taxonomies = get_the_taxonomies();
		if ( ! $taxonomies ) {
			return false;
		}

		$taxonomy = array_key_first( $taxonomies );

		if ( 'post' === get_post_type( get_the_ID() ) ) {
			$taxonomy = 'category';
		}

		return $taxonomy;
	}

	/**
	 * ターム用アイコン
	 *
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return string
	 */
	public static function get_taxonomy_icon( $taxonomy ) {
		$icon_name = 'folder';
		if ( ! is_taxonomy_hierarchical( $taxonomy ) ) {
			$icon_name = 'tag';
		}

		$icon_name = apply_filters( "ys_get_taxonomy_icon_name_${taxonomy}", $icon_name );

		return apply_filters( 'ys_get_taxonomy_icon', Icon::get_icon( $icon_name ) );
	}


	/**
	 * 投稿本文を取得
	 *
	 * @param bool $do_filter フィルターをかけるか.
	 *
	 * @return string
	 */
	public static function get_post_content( $do_filter = true ) {
		/**
		 * Post.
		 *
		 * @global \WP_Post
		 */
		global $post;
		$content = $post->post_content;
		if ( $do_filter ) {
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
		}

		return $content;
	}

	/**
	 * カラーコードをrgbに変換
	 *
	 * @param string $color カラーコード.
	 *
	 * @return array
	 */
	public static function hex_2_rgb( $color ) {
		return [
			hexdec( substr( $color, 1, 2 ) ),
			hexdec( substr( $color, 3, 2 ) ),
			hexdec( substr( $color, 5, 2 ) ),
		];
	}
}
