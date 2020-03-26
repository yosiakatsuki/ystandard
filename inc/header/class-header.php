<?php
/**
 * Header 関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Header
 *
 * @package ystandard
 */
class Header {

	/**
	 * Header constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'register_header_design' ] );
		add_action( 'customize_register', [ $this, 'register_logo_option' ] );
		add_filter( Enqueue_Styles::INLINE_CSS_HOOK, [ $this, 'add_inline_css' ] );
	}

	/**
	 * 固定ヘッダーか
	 *
	 * @return bool
	 */
	public static function is_header_fixed() {
		return Option::get_option_by_bool( 'ys_header_fixed', false );
	}

	/**
	 * インラインCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_inline_css( $css ) {

		$css .= $this->get_logo_css();

		return $css;
	}

	/**
	 * ロゴ関連のCSS取得
	 *
	 * @return string
	 */
	private function get_logo_css() {
		$css = '';
		/**
		 * ロゴ画像の幅設定
		 */
		if ( 0 < Option::get_option_by_int( 'ys_logo_width_sp', 0 ) ) {
			$css .= sprintf(
				'.header__title img{width:%spx;}',
				Option::get_option_by_int( 'ys_logo_width_sp', 0 )
			);
		}
		if ( 0 < Option::get_option_by_int( 'ys_logo_width_pc', 0 ) ) {
			$css .= Enqueue_Styles::add_media_query(
				sprintf(
					'.header__title img{width:%spx;}',
					Option::get_option_by_int( 'ys_logo_width_pc', 0 )
				),
				'md'
			);
		}

		return $css;
	}

	private function get_fixed_header_css() {

	}

	/**
	 * ロゴ関連の設定追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function register_logo_option( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_checkbox(
			[
				'id'          => 'ys_logo_hidden',
				'default'     => 0,
				'label'       => 'ロゴを非表示にする',
				'description' => 'サイトヘッダーにロゴ画像を表示しない場合はチェックをつけてください',
				'section'     => 'title_tagline',
				'priority'    => 9,
			]
		);
		/**
		 * 幅指定
		 */
		$customizer->add_number(
			[
				'id'          => 'ys_logo_width_pc',
				'default'     => 0,
				'label'       => 'ロゴの表示幅(PC・タブレット)',
				'description' => 'PC・タブレット表示のロゴ表示幅を指定できます。指定しない場合は0にしてください。',
				'section'     => 'title_tagline',
				'priority'    => 9,
				'input_attrs' => [
					'min'  => 0,
					'max'  => 1000,
					'size' => 20,
				],
			]
		);

		$customizer->add_number(
			[
				'id'          => 'ys_logo_width_sp',
				'default'     => 0,
				'label'       => 'ロゴの表示幅(スマホ)',
				'description' => 'スマートフォン表示のロゴ表示幅を指定できます。指定しない場合は0にしてください。',
				'section'     => 'title_tagline',
				'priority'    => 9,
				'input_attrs' => [
					'min'  => 0,
					'max'  => 1000,
					'size' => 20,
				],
			]
		);
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function register_header_design( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * セクション追加
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_customizer_section_header_design',
				'title'       => 'サイトヘッダー',
				'description' => 'サイトヘッダー部分のデザイン設定',
				'priority'    => 50,
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'ヘッダータイプ' );
		/**
		 * ヘッダータイプ
		 */
		$assets_url = Customizer::get_assets_dir_uri();
		$row1       = $assets_url . '/design/header/1row.png';
		$center     = $assets_url . '/design/header/center.png';
		$row2       = $assets_url . '/design/header/2row.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_design_header_type',
				'default'     => 'row1',
				'label'       => '表示タイプ',
				'description' => 'ヘッダーの表示タイプ',
				'section'     => 'ys_customizer_section_header_design',
				'choices'     => [
					'row1'   => sprintf( $img, $row1 ),
					'center' => sprintf( $img, $center ),
					'row2'   => sprintf( $img, $row2 ),
				],
			]
		);
		$customizer->add_section_label( 'ヘッダーカラー' );
		// ヘッダー背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_bg',
				'default' => '#ffffff',
				'label'   => '背景色',
			]
		);
		// サイトタイトル文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_font',
				'default' => '#222222',
				'label'   => '文字色',
			]
		);
		// サイト概要の文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_dscr_font',
				'default' => '#757575',
				'label'   => '概要文の文字色',
			]
		);
		/**
		 * ヘッダー固定表示
		 */
		$customizer->add_section_label( 'ヘッダー固定表示' );

		$customizer->add_checkbox(
			[
				'id'      => 'ys_header_fixed',
				'default' => 0,
				'label'   => 'ヘッダーを画面上部に固定する',
			]
		);
		/**
		 * ヘッダー固定表示
		 */
		$customizer->add_label(
			[
				'id'          => 'ys_header_fixed_height_label',
				'label'       => 'ヘッダー高さ',
				'description' => 'ヘッダーの固定表示をする場合、ヘッダー高さの指定が必要になります。<br><br>プレビュー画面左上に表示された「ヘッダー高さ」の数字を参考に以下の設定に入力してください。',

			]
		);
		/**
		 * ヘッダー高さ(PC)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_pc',
				'default' => 0,
				'label'   => '高さ(PC)',
			]
		);
		/**
		 * ヘッダー高さ(タブレット)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_tablet',
				'default' => 0,
				'label'   => '高さ(タブレット)',
			]
		);
		/**
		 * ヘッダー高さ(モバイル)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_mobile',
				'default' => 0,
				'label'   => '高さ(モバイル)',
			]
		);
	}

}

new Header();
