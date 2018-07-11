<?php
/**
 * ウィジェット基本クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマ内で作成するウィジェットのベース
 */
class YS_Widget_Base extends WP_Widget {
	/**
	 * ユーティリティ
	 *
	 * @var YS_Widget_Utility
	 */
	protected $ys_widget_utility;

	/**
	 * YS_Widget_Base constructor.
	 *
	 * @param string $id_base         ID.
	 * @param string $name            Name.
	 * @param array  $widget_options  Widget Options.
	 * @param array  $control_options Control Options.
	 */
	public function __construct( $id_base, $name, $widget_options = array(), $control_options = array() ) {
		parent::__construct( $id_base, $name, $widget_options, $control_options );
		$this->ys_widget_utility = new YS_Widget_Utility();
	}

	/**
	 * テーマ内で使える画像サイズ取得
	 */
	protected function get_image_sizes() {
		return $this->ys_widget_utility->get_image_sizes();
	}

	/**
	 * 保存用選択値の作成
	 *
	 * @param object $taxonomy タクソノミーオブジェクト.
	 * @param object $term     タームオブジェクト.
	 *
	 * @return string
	 */
	protected function get_select_taxonomy_value( $taxonomy, $term ) {
		return $this->ys_widget_utility->get_select_taxonomy_value( $taxonomy, $term );
	}

	/**
	 * 選択値をタクソノミーとタームに分割
	 *
	 * @param string $value 選択値.
	 *
	 * @return array
	 */
	protected function get_selected_taxonomy( $value ) {
		return $this->ys_widget_utility->get_selected_taxonomy( $value );
	}

	/**
	 * タクソノミー選択用Select出力
	 *
	 * @param WP_Widget $widget            ウィジェットオブジェクト.
	 * @param string    $selected_taxonomy 選択中ターム.
	 * @param array     $args              パラメータ.
	 */
	protected function the_taxonomies_select_html( $widget, $selected_taxonomy, $args = array() ) {
		$this->ys_widget_utility->the_taxonomies_select_html( $widget, $selected_taxonomy, $args );
	}
	/**
	 * 掲載期間中判断
	 *
	 * @param array $instance instance.
	 * @return bool
	 */
	protected function is_during_the_period( $instance ) {
		return YS_Widget_Utility::is_during_the_period( $instance );
	}
	/**
	 * 特定タクソノミーのみ表示判断
	 *
	 * @param array $instance instance.
	 * @return bool
	 */
	protected function has_term( $instance ) {
		return $this->ys_widget_utility->has_term( $instance );
	}

	/**
	 * チェックボックスのサニタイズ
	 *
	 * @param [type] $value チェックボックスのvalue.
	 *
	 * @return bool
	 */
	protected function sanitize_checkbox( $value ) {
		return $this->ys_widget_utility->sanitize_checkbox( $value );
	}

	/**
	 * 日付の有効性チェック
	 *
	 * @param string $date    日付文字列.
	 * @param string $default デフォルト値.
	 *
	 * @return string
	 */
	protected function sanitize_date( $date, $default = '' ) {
		return $this->ys_widget_utility->sanitize_date( $date, $default );
	}

	/**
	 * 時間の有効性チェック
	 *
	 * @param string $time    時間.
	 * @param string $default デフォルト値.
	 *
	 * @return string
	 */
	protected function sanitize_time( $time, $default = '00:00' ) {
		return $this->ys_widget_utility->sanitize_time( $time, $default );
	}
}
