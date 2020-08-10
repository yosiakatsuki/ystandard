<?php
/**
 * XML Site map
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Sitemap
 *
 * @package ystandard
 */
class Sitemap {

	/**
	 * パネル名
	 */
	const PANEL_NAME = 'ys_wp_sitemap';

	/**
	 * Sitemap constructor.
	 */
	public function __construct() {
		if ( ! function_exists( 'wp_sitemaps_get_server' ) ) {
			return;
		}
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'wp_sitemaps_enabled', [ $this, '_wp_sitemaps_enabled' ] );
		add_filter( 'wp_sitemaps_add_provider', [ $this, 'enable_sitemap_author' ], 10, 2 );
		add_filter( 'wp_sitemaps_post_types', [ $this, '_wp_sitemaps_post_types' ] );
		add_filter( 'wp_sitemaps_taxonomies', [ $this, '_wp_sitemaps_taxonomies' ] );
	}

	/**
	 * XMLサイトマップ機能の有効・無効
	 *
	 * @param bool $is_enabled Enable.
	 *
	 * @return mixed
	 */
	public function _wp_sitemaps_enabled( $is_enabled ) {
		return Option::get_option_by_bool( 'ys_sitemap_enable', false );
	}

	/**
	 * 投稿タイプ別XMLサイトマップ機能の有効・無効
	 *
	 * @param array $post_types 投稿タイプ.
	 *
	 * @return mixed
	 */
	public function _wp_sitemaps_post_types( $post_types ) {
		foreach ( $post_types as $type => $item ) {
			if ( ! Option::get_option_by_bool( "ys_sitemap_enable_post_type_${type}", true ) ) {
				unset( $post_types[ $type ] );
			}
		}
		unset( $post_types['ys-parts'] );

		return $post_types;
	}

	/**
	 * タクソノミー別XMLサイトマップ機能の有効・無効
	 *
	 * @param array $taxonomies タクソノミー.
	 *
	 * @return mixed
	 */
	public function _wp_sitemaps_taxonomies( $taxonomies ) {
		foreach ( $taxonomies as $type => $item ) {
			if ( ! Option::get_option_by_bool( "ys_sitemap_enable_taxonomy_${type}", true ) ) {
				unset( $taxonomies[ $type ] );
			}
		}

		return $taxonomies;
	}

	/**
	 * 投稿者別サイトマップの有効・無効
	 *
	 * @param \WP_Sitemaps_Provider $provider Provider.
	 * @param string                $name     Name.
	 */
	public function enable_sitemap_author( $provider, $name ) {
		if ( 'users' === $name && ! Option::get_option_by_bool( 'ys_sitemap_enable_author', false ) ) {
			return false;
		}

		return $provider;
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * サイトマップ機能の有効・無効
		 */
		$customizer->add_section(
			[
				'section'     => self::PANEL_NAME,
				'title'       => '[ys]XMLサイトマップ',
				'description' => Admin::manual_link( 'wp-xml-sitemap' ) . 'WordPress標準のXMLサイトマップ生成機能に関する設定',
			]
		);
		$customizer->add_section_label( 'XMLサイトマップ生成機能の有効化' );
		$customizer->add_checkbox(
			[
				'id'        => 'ys_sitemap_enable',
				'default'   => 0,
				'transport' => 'postMessage',
				'label'     => 'XMLサイトマップ生成機能を有効にする',
			]
		);
		$customizer->add_section_label( '投稿タイプ別設定' );
		$post_types = Utility::get_post_types( [], [ 'ys-parts' ] );

		foreach ( $post_types as $name => $label ) {
			$customizer->add_checkbox(
				[
					'id'        => 'ys_sitemap_enable_post_type_' . $name,
					'label'     => "$label サイトマップを作成する",
					'default'   => 1,
					'transport' => 'postMessage',
				]
			);
		}

		$customizer->add_section_label( 'カテゴリー・タグ別設定' );
		$taxonomies = Utility::get_taxonomies();
		foreach ( $taxonomies as $name => $label ) {
			$customizer->add_checkbox(
				[
					'id'        => 'ys_sitemap_enable_taxonomy_' . $name,
					'label'     => "$label サイトマップを作成する",
					'default'   => 1,
					'transport' => 'postMessage',
				]
			);
		}

		$customizer->add_section_label( '投稿者別設定' );
		$customizer->add_checkbox(
			[
				'id'        => 'ys_sitemap_enable_author',
				'label'     => '投稿者別サイトマップを作成する',
				'default'   => 0,
				'transport' => 'postMessage',
			]
		);

	}
}

new Sitemap();
