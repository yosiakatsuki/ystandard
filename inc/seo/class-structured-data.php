<?php
/**
 * 構造化データ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Structured_Data
 *
 * @package ystandard
 */
class Structured_Data {

	/**
	 * Default.
	 */
	const DEFAULT_DATA = [
		'@context' => 'https://schema.org',
	];

	/**
	 * Structured_Data constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'wp_footer', [ $this, 'article' ] );
		add_action( 'wp_footer', [ $this, 'website' ] );
		add_action( 'wp_footer', [ $this, 'organization' ] );
	}

	/**
	 * Organization.
	 */
	public function organization() {
		$data          = self::DEFAULT_DATA;
		$data['@type'] = 'Organization';
		$data['url']   = home_url( '/' );
		if ( has_custom_logo() ) {
			$logo_id = Utility::get_custom_logo_id();
			$logo    = wp_get_attachment_image_src( $logo_id, 'full' );
			if ( $logo ) {
				$data['logo'] = [
					'@type'  => 'ImageObject',
					'url'    => $logo[0],
					'width'  => $logo[1],
					'height' => $logo[2],
				];
			}
		}

		Utility::json_ld( $data );
	}

	/**
	 * Website.
	 */
	public function website() {
		$data                  = self::DEFAULT_DATA;
		$data['@type']         = 'Website';
		$data['url']           = home_url( '/' );
		$data['name']          = get_bloginfo( 'name', 'display' );
		$data['alternateName'] = get_bloginfo( 'name', 'display' );
		if ( Template::is_top_page() ) {
			$data['potentialAction'] = [
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			];
		}

		Utility::json_ld( $data );
	}

	/**
	 * Article
	 */
	public function article() {

		if ( is_front_page() || is_404() ) {
			return;
		}
		/**
		 * Post Objects.
		 *
		 * @global array $posts { @type \WP_Post }
		 */
		global $posts;
		$article = [];
		foreach ( $posts as $post ) {
			/**
			 * データ準備
			 */
			$data    = self::DEFAULT_DATA;
			$url     = esc_url_raw( get_the_permalink( $post->ID ) );
			$title   = esc_attr( get_the_title( $post->ID ) );
			$excerpt = esc_attr( Content::get_custom_excerpt( '', 0, $post->ID ) );
			$content = esc_attr( Utility::get_plain_text( $post->post_content ) );
			/**
			 * 構造化データ作成
			 */
			$data['@type']            = 'Article';
			$data['mainEntityOfPage'] = [
				'@type' => 'WebPage',
				'@id'   => $url,
			];
			$data['name']             = $title;
			$data['headline']         = mb_substr( $title, 0, 110 );
			$data['description']      = $excerpt;
			$data['url']              = $url;
			$data['articleBody']      = $content;
			$data['author']           = [
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', $post->post_author ),
			];
			$data['datePublished']    = get_the_date( DATE_ATOM, $post->ID );
			$data['dateModified']     = get_post_modified_time( DATE_ATOM, false, $post->ID );
			// カテゴリー.
			$category = get_the_category();
			if ( $category ) {
				if ( 1 < count( $category ) ) {
					$section = [];
					foreach ( $category as $item ) {
						$section[] = $item->name;
					}
					$data['articleSection'] = $section;
				} else {
					$data['articleSection'] = esc_attr( $category[0]->name );
				}
			}
			/**
			 * くっつける
			 */
			$article[] = array_merge(
				$data,
				$this->get_image_object(),
				$this->get_publisher()
			);
		}

		Utility::json_ld( $article );
	}


	/**
	 * ImageObject 作成
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array
	 */
	private function get_image_object( $post_id = 0 ) {
		$data  = [];
		$image = Utility::get_post_thumbnail_src( $post_id );
		if ( $image ) {
			$data['image'] = [
				'@type'  => 'ImageObject',
				'url'    => $image[0],
				'width'  => $image[1],
				'height' => $image[2],
			];
		}

		return $data;
	}

	/**
	 * Publisher.
	 *
	 * @return array
	 */
	private function get_publisher() {
		$data = [];
		$name = Option::get_option( 'ys_option_structured_data_publisher_name', '' );
		if ( '' === $name ) {
			$name = get_bloginfo( 'name', 'display' );
		}
		$data['publisher'] = [
			'@type' => 'Organization',
			'name'  => $name,
		];
		$publisher_img     = $this->get_publisher_image();
		if ( $publisher_img ) {
			$data['publisher']['logo'] = [
				'@type'  => 'ImageObject',
				'url'    => $publisher_img[0],
				'width'  => $publisher_img[1],
				'height' => $publisher_img[2],
			];
		}

		return $data;
	}

	/**
	 * Publisher Image.
	 *
	 * @return array
	 */
	private function get_publisher_image() {
		// デフォルト画像.
		$image = [
			get_template_directory_uri() . '/assets/images/publisher-logo/default-publisher-logo.png',
			600,
			60,
		];
		/**
		 * ロゴ設定の取得
		 */
		$logo_id = Utility::get_custom_logo_id();
		if ( $logo_id ) {
			$image = wp_get_attachment_image_src( $logo_id, 'full' );
		}
		// パブリッシャー画像の取得.
		$image_url = Option::get_option( 'ys_option_structured_data_publisher_image', '' );
		if ( $image_url ) {
			$image_id = attachment_url_to_postid( $image_url );
			if ( $image_id ) {
				$image = wp_get_attachment_image_src( $image_id, 'full' );
			}
		}

		return $image;
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => 'ys_structured_data',
				'title'       => '構造化データ',
				'priority'    => 100,
				'description' => Admin::manual_link( 'manual/structured-data' ),
				'panel'       => SEO::PANEL_NAME,
			]
		);
		/**
		 * Publisher画像
		 */
		$customizer->add_image(
			[
				'id'          => 'ys_option_structured_data_publisher_image',
				'transport'   => 'postMessage',
				'label'       => 'Publisher Logo',
				'description' => '構造化データのPublisherに使用する画像です。サイトロゴのような画像を設定すると良いかと思います。 推奨サイズ:横600px以下,縦60px以下',
			]
		);
		/**
		 * Publisher名
		 */
		$customizer->add_text(
			[
				'id'          => 'ys_option_structured_data_publisher_name',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Publisher Name',
				'description' => '構造化データのPublisherに使用する名前です。空白の場合はサイトタイトルを使用します',
			]
		);
	}
}

new Structured_Data();
