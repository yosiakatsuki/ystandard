<?php
/**
 * ボタンの初期スタイル設定
 *
 * @package ystandard
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * ボタンのカスタマイザー設定とCSS変数を登録する.
 */
class Button_Style {
	const SECTION_NAME = 'ys_button_style';

	/**
	 * フックを登録する.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_vars' ] );
	}

	/**
	 * 設定項目と入力の種類、出力するCSS変数を取得する.
	 *
	 * CSS変数名はdocs/design-system.mdの命名規則に合わせ、`--ystd--button--`以降を指定する.
	 *
	 * @return array<string, array{0: string, 1: string, 2: string[]}>
	 */
	private static function get_fields() {
		return [
			'font_size'        => [ __( '文字サイズ', 'ystandard' ), 'length', [ 'typography--font-size' ] ],
			'text_color'       => [ __( '文字色', 'ystandard' ), 'color', [ 'color--text' ] ],
			'background_color' => [ __( '背景色', 'ystandard' ), 'color', [ 'color--background' ] ],
			'padding_y'        => [ __( '上下余白', 'ystandard' ), 'length', [ 'spacing--padding--y' ] ],
			'padding_x'        => [ __( '左右余白', 'ystandard' ), 'length', [ 'spacing--padding--x' ] ],
			'border_width'     => [ __( '枠線太さ', 'ystandard' ), 'length', [ 'border--width' ] ],
			'border_color'     => [ __( '枠線色', 'ystandard' ), 'color', [ 'border--color' ] ],
			'border_radius'    => [ __( '角丸', 'ystandard' ), 'length', [ 'border--radius' ] ],
			'min_width'        => [ __( '最小幅', 'ystandard' ), 'length', [ 'dimensions--min-width' ] ],
			'min_height'       => [ __( '最小高さ', 'ystandard' ), 'length', [ 'dimensions--min-height' ] ],
		];
	}

	/**
	 * 状態ごとの設定項目を取得する.
	 *
	 * @param string $state 設定の状態.
	 * @return array
	 */
	private static function get_state_fields( $state ) {
		$fields = self::get_fields();
		// ホバー時に大きさが変わると周囲のレイアウトがずれるため、色だけを設定対象にする.
		if ( 'hover' === $state ) {
			return array_intersect_key( $fields, array_flip( [ 'text_color', 'background_color', 'border_color' ] ) );
		}
		return $fields;
	}

	/**
	 * スタイル項目の保存キーを取得する.
	 *
	 * @param string $state 設定の状態.
	 * @param string $field 設定項目のキー.
	 * @return string
	 */
	private static function get_option_name( $state, $field ) {
		return 'ys_button_' . $state . '_' . $field;
	}

	/**
	 * カスタマイザーに設定を登録する.
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => self::SECTION_NAME,
				'title'       => '[ys]' . __( 'ボタンスタイル', 'ystandard' ),
				'description' => __( 'ボタンブロックやフォームの送信ボタンなどの初期スタイルを設定します。', 'ystandard' ),
			]
		);

		$styles = [
			'normal' => __( '通常スタイル', 'ystandard' ),
			'hover'  => __( 'ホバースタイル', 'ystandard' ),
		];
		foreach ( $styles as $state => $title ) {
			$customizer->add_section_label(
				$title,
				[
					'id'          => 'ys_button_' . $state . '_section_label',
					'description' => 'hover' === $state
						? __( '未設定の項目には、通常スタイルの色を少し透過させた色を使用します。', 'ystandard' )
						: __( '未設定の項目には、テーマの初期値を使用します。', 'ystandard' ),
				]
			);
			foreach ( self::get_state_fields( $state ) as $field => [ $label, $type ] ) {
				$args = [
					'id'    => self::get_option_name( $state, $field ),
					'label' => $label,
				];
				// 色は既存のパレットを使い、透明度も設定できるようにする.
				if ( 'color' === $type ) {
					$customizer->add_color( $args );
					continue;
				}
				$customizer->add_text(
					$args + [
						'description'       => __( '単位付きで入力できます。数値のみの場合はpxになります。', 'ystandard' ),
						'sanitize_callback' => [ self::class, 'normalize_length' ],
					]
				);
			}
		}

		$customizer->add_section_label( __( 'アニメーション', 'ystandard' ), [ 'id' => 'ys_button_animation_section_label' ] );
		$customizer->add_text(
			[
				'id'                => 'ys_button_animation_properties',
				'label'             => __( '適用範囲', 'ystandard' ),
				'description'       => __( 'CSSプロパティ名をカンマで区切って入力します。未入力時はボタンごとの初期値を使用します。', 'ystandard' ),
				'placeholder'       => 'all',
				'sanitize_callback' => [ self::class, 'sanitize_transition_properties' ],
			]
		);
		$customizer->add_number(
			[
				'id'                => 'ys_button_animation_duration',
				'label'             => __( 'アニメーション速さ (ms)', 'ystandard' ),
				'placeholder'       => '300',
				'input_attrs'       => [
					'min'  => '0',
					'step' => 'any',
				],
				'sanitize_callback' => [ self::class, 'sanitize_number' ],
			]
		);

		$styles  = self::get_animation_styles();
		$choices = [ '' => __( 'テーマの初期値', 'ystandard' ) ] + array_column( $styles, 'label', 'slug' );
		$customizer->add_select(
			[
				'id'      => 'ys_button_animation_style',
				'label'   => __( 'アニメーションスタイル', 'ystandard' ),
				'choices' => $choices,
			]
		);
	}

	/**
	 * 長さを検証し、単位のない値はpxとして扱う.
	 *
	 * @param mixed $value 入力値.
	 * @return string
	 */
	public static function normalize_length( $value ) {
		$value = Layout::normalize_css_value( $value );
		// ボタンの文字サイズ・余白・枠線・最小サイズには負の長さを使わない.
		return preg_match( '/\A-(?:[0-9]|\.[0-9])/', $value ) ? '' : $value;
	}

	/**
	 * 選択可能なイージングの定義を取得する.
	 *
	 * @return array<string, array{slug: string, label: string, value: string}>
	 */
	public static function get_animation_styles() {
		$defaults = [
			[
				'slug'  => 'ease',
				'label' => __( 'イーズ (ease)', 'ystandard' ),
				'value' => 'ease',
			],
			[
				'slug'  => 'linear',
				'label' => __( '一定 (linear)', 'ystandard' ),
				'value' => 'linear',
			],
			[
				'slug'  => 'ease-in',
				'label' => __( '徐々に速く (ease-in)', 'ystandard' ),
				'value' => 'ease-in',
			],
			[
				'slug'  => 'ease-out',
				'label' => __( '徐々に遅く (ease-out)', 'ystandard' ),
				'value' => 'ease-out',
			],
			[
				'slug'  => 'ease-in-out',
				'label' => __( '始めと終わりが緩やか (ease-in-out)', 'ystandard' ),
				'value' => 'ease-in-out',
			],
		];

		/**
		 * ボタンのアニメーションスタイルの選択肢を変更する.
		 *
		 * @param array $styles slug（保存値）、label（表示名）、value（CSSイージング値）を持つ定義の配列.
		 */
		$styles = apply_filters( 'ys_button_animation_styles', $defaults );
		// 配列以外が返された場合も、テーマの既定の選択肢は利用できるようにする.
		if ( ! is_array( $styles ) ) {
			$styles = $defaults;
		}

		$result = [];
		foreach ( $styles as $style ) {
			// 欠けた定義やCSS宣言に混入し得る値は選択肢に含めない.
			if ( ! is_array( $style ) || ! isset( $style['slug'], $style['label'], $style['value'] )
				|| ! is_string( $style['slug'] ) || '' === $style['slug'] || sanitize_key( $style['slug'] ) !== $style['slug']
				|| ! is_string( $style['label'] ) || '' === trim( $style['label'] )
				|| ! is_string( $style['value'] ) || ! preg_match(
					'/\A(?:ease|linear|ease-in|ease-out|ease-in-out|step-start|step-end|(?:cubic-bezier|linear|steps)\([0-9a-z+.,%\s-]+\))\z/i',
					trim( $style['value'] )
				)
			) {
				continue;
			}
			$result[ $style['slug'] ] = [
				'slug'  => $style['slug'],
				'label' => $style['label'],
				'value' => trim( $style['value'] ),
			];
		}

		return $result;
	}

	/**
	 * 単位を付ける前の数値を検証する.
	 *
	 * @param mixed $value 入力値.
	 * @return string
	 */
	public static function sanitize_number( $value ) {
		// 他の型や指数表記はCSSに出力する数値として扱わない.
		if ( ! is_string( $value ) && ! is_int( $value ) && ! is_float( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );
		return preg_match( '/\A(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+)\z/', $value ) ? $value : '';
	}

	/**
	 * トランジション対象をCSSのプロパティ名リストに正規化する.
	 *
	 * @param mixed $value 入力値.
	 * @return string
	 */
	public static function sanitize_transition_properties( $value ) {
		// プロパティ名以外のCSS構文を設定から出力しない.
		if ( ! is_string( $value ) || ! preg_match( '/\A\s*(?:--[a-z0-9_-]+|-?[a-z][a-z0-9-]*)(?:\s*,\s*(?:--[a-z0-9_-]+|-?[a-z][a-z0-9-]*))*\s*\z/i', $value ) ) {
			return '';
		}
		$value = trim( $value );
		// noneは単独で指定した場合にだけ使用できる.
		if ( 'none' === strtolower( $value ) ) {
			return 'none';
		}
		$properties = array_map( 'trim', explode( ',', $value ) );
		// CSS全体の継承を設定するキーワードは対象プロパティとして扱わない.
		if ( array_intersect( array_map( 'strtolower', $properties ), [ 'none', 'initial', 'inherit', 'unset', 'revert', 'revert-layer', 'default' ] ) ) {
			return '';
		}
		return implode( ', ', $properties );
	}

	/**
	 * 設定済みのスタイルをCSS値へ変換する.
	 *
	 * @param string $state 設定の状態.
	 * @return array<string, string> CSS変数名（`--ystd--`以降）と値.
	 */
	private static function get_style_values( $state ) {
		$prefix = 'hover' === $state ? 'button--hover--' : 'button--';
		$values = [];
		foreach ( self::get_state_fields( $state ) as $field => [ $label, $type, $properties ] ) {
			$value = Option::get_option( self::get_option_name( $state, $field ), '' );
			$value = 'color' === $type ? Customize_Control::sanitize_color( $value ) : self::normalize_length( $value );
			// 空欄はテーマの初期値を使い、0は明示的な上書きとして扱う.
			if ( null === $value || '' === $value ) {
				continue;
			}
			foreach ( $properties as $property ) {
				$values[ $prefix . $property ] = $value;
			}
		}
		return $values;
	}

	/**
	 * 変更されたボタン設定をCSSカスタムプロパティとして追加する.
	 *
	 * ホバー時の未設定項目は、CSS側で通常時の変数から初期値を作るため出力しない.
	 *
	 * @param array $css_vars CSSカスタムプロパティ.
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {
		$css_vars = array_merge( $css_vars, self::get_style_values( 'normal' ), self::get_style_values( 'hover' ) );

		$properties = self::sanitize_transition_properties( Option::get_option( 'ys_button_animation_properties', '' ) );
		// 未入力時は、テーマの初期値を使う.
		if ( '' !== $properties ) {
			$css_vars['button--transition--property'] = $properties;
		}

		$duration = self::sanitize_number( Option::get_option( 'ys_button_animation_duration', '' ) );
		// 0msはアニメーションを即時に切り替える設定として反映する.
		if ( '' !== $duration ) {
			$css_vars['button--transition--duration'] = $duration . 'ms';
		}

		$style = Option::get_option( 'ys_button_animation_style', '' );
		// イージングが選択された場合にだけ拡張された定義を取得する.
		if ( ! is_string( $style ) || '' === $style ) {
			return $css_vars;
		}
		$styles = self::get_animation_styles();
		// 拡張機能が外れた場合は、対応するCSS値がなくなるのでテーマの初期値を使う.
		if ( isset( $styles[ $style ] ) ) {
			$css_vars['button--transition--timing-function'] = $styles[ $style ]['value'];
		}

		return $css_vars;
	}
}

new Button_Style();
