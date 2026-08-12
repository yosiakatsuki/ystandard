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
		add_filter( 'customize_value_ys_design_font_type', [ $this, 'convert_legacy_font_type' ] );
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
		$option = self::convert_legacy_font_type( Option::get_option( 'ys_design_font_type', '' ) );
		if ( ! empty( $option ) && isset( $font[ $option ] ) ) {
			$font_family = $font[ $option ]['family'];

			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var( '--ystd--font-family', $font_family )
			);

			$font_weight         = Option::get_option( 'ys_design_font_weight', '' );
			$font_weight_choices = self::get_font_weight_choices( $option, $font );
			if ( '' !== $font_weight && isset( $font_weight_choices[ $font_weight ] ) ) {
				$css_vars = array_merge(
					$css_vars,
					Enqueue_Utility::get_css_var( '--ystd--font-weight--normal', $font_weight )
				);
			}
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
				'default'     => 'font-library-ystd-gothic',
				'label'       => __( 'フォントタイプ', 'ystandard' ),
				'description' => __( '文字のフォントを変更できます', 'ystandard' ),
				'choices'     => self::get_font_choices(),
			]
		);
		$this->register_font_weight_controls( $wp_customize, $customizer );
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
	 * 選べるフォントのリスト取得
	 *
	 * @return array
	 */
	public static function get_usable_fonts() {
		return apply_filters(
			'ys_usable_fonts',
			self::get_font_library_fonts()
		);
	}

	/**
	 * フォントウエイト選択肢を取得
	 *
	 * @param string     $font_type フォント設定値.
	 * @param array|null $fonts     フォント一覧.
	 *
	 * @return array
	 */
	public static function get_font_weight_choices( $font_type, $fonts = null ) {
		$font_type = self::convert_legacy_font_type( $font_type );
		$fonts     = is_array( $fonts ) ? $fonts : self::get_usable_fonts();
		if (
			empty( $font_type ) ||
			empty( $fonts[ $font_type ] ) ||
			'custom' !== ( $fonts[ $font_type ]['origin'] ?? '' ) ||
			empty( $fonts[ $font_type ]['weights'] ) ||
			in_array( 400, $fonts[ $font_type ]['weights'], true )
		) {
			return [];
		}

		$choices = [ '' => __( '指定なし（400）', 'ystandard' ) ];
		foreach ( $fonts[ $font_type ]['weights'] as $font_weight ) {
			$choices[ $font_weight ] = (string) $font_weight;
		}

		return $choices;
	}

	/**
	 * フォントウエイト設定の表示判定
	 *
	 * @param \WP_Customize_Control|null $control カスタマイザーコントロール.
	 *
	 * @return bool
	 */
	public function is_active_font_weight_control( $control = null ) {
		if ( ! $control instanceof \WP_Customize_Control ) {
			return false;
		}

		$font_type = $control->input_attrs['data-font-type'] ?? '';
		if ( empty( $font_type ) ) {
			return false;
		}

		$selected_font_type = $this->get_selected_font_type( $control->manager );

		return $selected_font_type === $font_type;
	}

	/**
	 * フォントウエイト設定をサニタイズ
	 *
	 * @param string                $font_weight フォントウエイト.
	 * @param \WP_Customize_Setting $setting     カスタマイザー設定.
	 *
	 * @return string
	 */
	public function sanitize_font_weight( $font_weight, $setting ) {
		$font_weight = sanitize_key( $font_weight );
		$font_type   = $this->get_selected_font_type( $setting->manager );
		$choices     = self::get_font_weight_choices( $font_type );

		return isset( $choices[ $font_weight ] ) ? $font_weight : $setting->default;
	}

	/**
	 * フォントウエイト設定を追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 * @param Customize_Control     $customizer   カスタマイザーコントロール.
	 */
	private function register_font_weight_controls( $wp_customize, $customizer ) {
		$setting_id   = 'ys_design_font_weight';
		$setting_args = [
			'id'                => $setting_id,
			'setting_type'      => 'option',
			'transport'         => 'refresh',
			'default'           => Option::get_default( $setting_id, '' ),
			'sanitize_callback' => [ $this, 'sanitize_font_weight' ],
		];
		$wp_customize->add_setting(
			$setting_id,
			Customize_Control::get_setting_args( $setting_args, $setting_id )
		);

		$fonts = self::get_usable_fonts();
		foreach ( array_keys( $fonts ) as $font_type ) {
			$choices = self::get_font_weight_choices( $font_type, $fonts );
			if ( empty( $choices ) ) {
				continue;
			}

			$control_id   = $setting_id . '__' . sanitize_key( $font_type );
			$control_args = [
				'id'              => $setting_id,
				'control_type'    => 'select',
				'active_callback' => [ $this, 'is_active_font_weight_control' ],
				'priority'        => 10,
				'section'         => 'ys_section_font',
				'label'           => __( '標準フォントウエイト', 'ystandard' ),
				'description'     => __( '選択したフォントにはウエイト400がないため、本文の標準ウエイトを選択できます。', 'ystandard' ),
				'choices'         => $choices,
				'input_attrs'     => [ 'data-font-type' => $font_type ],
			];
			$wp_customize->add_control(
				$control_id,
				Customize_Control::get_control_args(
					$control_args,
					$control_id,
					[ 'settings' => $setting_id ]
				)
			);
		}

		$customizer->do_action_after_add_setting( $setting_id, $setting_args );
	}

	/**
	 * カスタマイザーで選択中のフォントを取得
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 *
	 * @return string
	 */
	private function get_selected_font_type( $wp_customize ) {
		$setting = $wp_customize->get_setting( 'ys_design_font_type' );
		if ( ! $setting ) {
			return '';
		}

		$post_values = $wp_customize->unsanitized_post_values();
		$font_type   = array_key_exists( $setting->id, $post_values )
			? sanitize_key( $post_values[ $setting->id ] )
			: $setting->value();

		return self::convert_legacy_font_type( $font_type );
	}

	/**
	 * カスタマイザーに表示するフォント選択肢を取得
	 *
	 * @return array
	 */
	private static function get_font_choices() {
		$choices = [];
		$fonts   = self::get_usable_fonts();
		foreach ( $fonts as $key => $font ) {
			if ( empty( $font['label'] ) ) {
				continue;
			}
			$choices[ $key ] = $font['label'];
		}

		return $choices;
	}

	/**
	 * Font Libraryで選択可能なフォントを取得
	 *
	 * @return array
	 */
	private static function get_font_library_fonts() {
		if ( ! function_exists( 'wp_get_global_settings' ) ) {
			return [];
		}

		$font_families = wp_get_global_settings( [ 'typography', 'fontFamilies' ] );
		if ( empty( $font_families ) || ! is_array( $font_families ) ) {
			return [];
		}

		$result = [];
		foreach ( self::normalize_font_families( $font_families ) as $font_data ) {
			$font_family = $font_data['font_family'];
			if (
				! is_array( $font_family ) ||
				empty( $font_family['slug'] ) ||
				empty( $font_family['fontFamily'] )
			) {
				continue;
			}

			$key = 'font-library-' . sanitize_key( $font_family['slug'] );
			if ( 'font-library-' === $key || isset( $result[ $key ] ) ) {
				continue;
			}

			$result[ $key ] = [
				'family'  => sanitize_text_field( $font_family['fontFamily'] ),
				'label'   => self::get_font_library_font_label( $font_family ),
				'origin'  => $font_data['origin'],
				'weights' => self::get_font_weights( $font_family ),
			];
		}

		return $result;
	}

	/**
	 * Font Libraryのフォント一覧を1次元配列に整形
	 *
	 * @param array $font_families フォント一覧.
	 *
	 * @return array
	 */
	private static function normalize_font_families( $font_families ) {
		if ( isset( $font_families['slug'] ) ) {
			return [
				[
					'origin'      => '',
					'font_family' => $font_families,
				],
			];
		}

		$result = [];
		foreach ( [ 'custom', 'theme', 'default' ] as $origin ) {
			if ( empty( $font_families[ $origin ] ) || ! is_array( $font_families[ $origin ] ) ) {
				continue;
			}
			foreach ( $font_families[ $origin ] as $font_family ) {
				$result[] = [
					'origin'      => $origin,
					'font_family' => $font_family,
				];
			}
		}

		if ( empty( $result ) && isset( $font_families[0] ) ) {
			foreach ( $font_families as $font_family ) {
				$result[] = [
					'origin'      => '',
					'font_family' => $font_family,
				];
			}
		}

		return $result;
	}

	/**
	 * フォントで利用可能なウエイトを取得
	 *
	 * @param array $font_family フォント情報.
	 *
	 * @return array
	 */
	private static function get_font_weights( $font_family ) {
		if ( empty( $font_family['fontFace'] ) || ! is_array( $font_family['fontFace'] ) ) {
			return [];
		}

		$weights = [];
		foreach ( $font_family['fontFace'] as $font_face ) {
			if ( ! is_array( $font_face ) ) {
				continue;
			}
			$font_style = strtolower( $font_face['fontStyle'] ?? 'normal' );
			if ( 'normal' !== $font_style ) {
				continue;
			}

			$font_weight = $font_face['fontWeight'] ?? 400;
			$weights     = array_merge( $weights, self::parse_font_weight( $font_weight ) );
		}

		$weights = array_values( array_unique( $weights ) );
		sort( $weights, SORT_NUMERIC );

		return $weights;
	}

	/**
	 * FontWeightを数値の一覧に変換
	 *
	 * @param string|int|array $font_weight フォントウエイト.
	 *
	 * @return array
	 */
	private static function parse_font_weight( $font_weight ) {
		if ( is_array( $font_weight ) ) {
			$result = [];
			foreach ( $font_weight as $weight ) {
				$result = array_merge( $result, self::parse_font_weight( $weight ) );
			}

			return $result;
		}

		$font_weight = strtolower( trim( (string) $font_weight ) );
		if ( 'normal' === $font_weight ) {
			return [ 400 ];
		}
		if ( 'bold' === $font_weight ) {
			return [ 700 ];
		}

		preg_match_all( '/\d{1,4}/', $font_weight, $matches );
		$weights = array_values(
			array_filter(
				array_map( 'intval', $matches[0] ),
				static function ( $weight ) {
					return 1 <= $weight && 1000 >= $weight;
				}
			)
		);
		if ( 2 !== count( $weights ) || $weights[0] >= $weights[1] ) {
			return $weights;
		}

		$range = [ $weights[0] ];
		for ( $weight = (int) ceil( $weights[0] / 100 ) * 100; $weight <= $weights[1]; $weight += 100 ) {
			$range[] = $weight;
		}
		$range[] = $weights[1];

		return array_values( array_unique( $range ) );
	}

	/**
	 * 旧フォント設定値をtheme.jsonのフォントキーに変換
	 *
	 * @param string $font_type フォント設定値.
	 *
	 * @return string
	 */
	public static function convert_legacy_font_type( $font_type ) {
		$legacy_font_types = [
			'meihiragino' => 'font-library-ystd-gothic',
			'yugo'        => 'font-library-ystd-yu-gothic',
			'serif'       => 'font-library-ystd-serif',
		];

		return $legacy_font_types[ $font_type ] ?? $font_type;
	}

	/**
	 * Font Libraryのフォント名を取得
	 *
	 * @param array $font_family フォント情報.
	 *
	 * @return string
	 */
	private static function get_font_library_font_label( $font_family ) {
		if ( ! empty( $font_family['name'] ) ) {
			return sanitize_text_field( $font_family['name'] );
		}

		return sanitize_text_field( $font_family['slug'] );
	}
}

Typography::get_instance();
