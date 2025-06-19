<?php
/**
 * サイト全体の文字に関する設定や処理のクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Typography
 *
 * @package ystandard
 */
class Typography {

	/**
	 * パネル名
	 *
	 * @var string
	 */
	const PANEL_NAME = 'ys_site_typography';

	/**
	 * インスタンスを保持する変数
	 *
	 * @var Typography|null
	 */
	private static $instance = null;

	/**
	 * コンストラクタをプライベートにして外部からのインスタンス生成を防ぐ
	 */
	private function __construct() {
		// 初期化処理.
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_vars' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * クローンを禁止
	 */
	private function __clone() {}

	/**
	 * アンシリアライズを禁止
	 *
	 * @throws \Exception アンシリアライズ時に例外を投げる.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * インスタンスを取得する
	 *
	 * @return Typography
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * フォントCSS
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {
		$font_family = 'sans-serif';
		$font        = self::get_usable_fonts();

		// フォント.
		$option = Option::get_option( 'ys_design_font_type', '' );
		if ( ! empty( $option ) && isset( $font[ $option ] ) ) {
			$font_family = $font[ $option ]['family'];

			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var( '--ystd--font-family', $font_family )
			);
		}

		// 文字色.
		$site_text = Option::get_option( 'ys_color_site_text', '' );
		if ( ! empty( $site_text ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--text-color',
					$site_text
				)
			);
		}

		// グレー文字色.
		$site_gray = Option::get_option( 'ys_color_site_gray', '' );
		if ( ! empty( $site_gray ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--text-color--gray',
					$site_gray
				)
			);
		}

		// リンク色.
		$link = Option::get_option( 'ys_color_link', '' );
		if ( ! empty( $link ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--link--text-color',
					$link
				)
			);
		}

		// リンクホバー色.
		$link_hover = Option::get_option( 'ys_color_link_hover', '' );
		if ( ! empty( $link_hover ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--link--text-color--hover',
					$link_hover
				)
			);
		}

		return $css_vars;
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		// セクション追加.
		$customizer->add_section(
			[
				'section'     => 'ys_section_font',
				'title'       => __( '[ys]フォント・文字色', 'ystandard' ),
				'description' => __( 'サイト全体にフォント・文字色の設定', 'ystandard' ) . Admin::manual_link( 'manual/font' ),
				'priority'    => Customizer::get_priority( self::PANEL_NAME ),
			]
		);
		$customizer->add_section_label( __( 'フォント', 'ystandard' ) );
		// フォント種類.
		$customizer->add_radio(
			[
				'id'          => 'ys_design_font_type',
				'default'     => 'meihiragino',
				'label'       => __( 'フォントタイプ', 'ystandard' ),
				'description' => __( '文字のフォントを変更できます', 'ystandard' ),
				'choices'     => [
					'meihiragino' => $this->get_font_label( 'meihiragino' ),
					'yugo'        => $this->get_font_label( 'yugo' ),
					'serif'       => $this->get_font_label( 'serif' ),
				],
			]
		);
		$customizer->add_section_label( __( '文字色', 'ystandard' ) );
		// 文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_site_text',
				'default' => '',
				'label'   => __( '文字色', 'ystandard' ),
			]
		);
		/**
		 * グレー文字色
		 */
		$customizer->add_color(
			[
				'id'          => 'ys_color_site_gray',
				'default'     => '',
				'label'       => 'グレー文字色',
				'description' => '少し薄めの色で表示される部分の色設定',
			]
		);
		$customizer->add_section_label( __( 'リンク色', 'ystandard' ) );
		// リンク色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_link',
				'default' => '',
				'label'   => 'リンク色',
			]
		);
		// ホバー色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_link_hover',
				'default' => '',
				'label'   => 'リンク色(マウスホバー)',
			]
		);
	}

	/**
	 * フォント選択肢のラベルを取得
	 *
	 * @param string $type タイプ名.
	 *
	 * @return string
	 */
	private function get_font_label( $type ) {
		$fonts = self::get_usable_fonts();

		return $fonts[ $type ]['label'];
	}

	/**
	 * 選べるフォントのリスト取得
	 *
	 * @return array
	 */
	public static function get_usable_fonts() {
		return apply_filters(
			'ys_usable_fonts',
			[
				'meihiragino' => [
					'family' => '"Helvetica neue", Arial, "Hiragino Sans", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif',
					'label'  => 'メイリオ・ヒラギノ角ゴシック',
				],
				'yugo'        => [
					'family' => 'Avenir, "Segoe UI", YuGothic, "Yu Gothic Medium", sans-serif',
					'label'  => '游ゴシック',
				],
				'serif'       => [
					'family' => 'serif',
					'label'  => '明朝体',
				],
			]
		);
	}
}

Typography::get_instance();
