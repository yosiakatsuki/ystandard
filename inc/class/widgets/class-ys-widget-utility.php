<?php
/**
 * ウィジェットで利用する機能
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Widget_Utility
 */
class YS_Widget_Utility {

	/**
	 * タクソノミーとタームの区切り文字
	 *
	 * @var string
	 */
	private $delimiter = '__';

	/**
	 * YS_Widget_Utility constructor.
	 */
	public function __construct() {

	}

	/**
	 * テーマ内で使える画像サイズ取得
	 */
	public function get_image_sizes() {
		global $_wp_additional_image_sizes;
		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
				$sizes[ $size ]['width']  = get_option( "{$size}_size_w" );
				$sizes[ $size ]['height'] = get_option( "{$size}_size_h" );
			} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$sizes[ $size ] = array(
					'width'  => $_wp_additional_image_sizes[ $size ]['width'],
					'height' => $_wp_additional_image_sizes[ $size ]['height'],
				);

			}
		}

		return $sizes;
	}

	/**
	 * 日付条件のデフォルト値を取得
	 *
	 * @return array
	 */
	public static function get_default_date() {
		$start_date = date_i18n( 'Y-m-d' );
		$start_time = date_i18n( 'H:i' );
		$end_date   = new DateTime( date_i18n( 'Y-m-d' ) );
		$end_date->modify( '+7 days' );
		$end_date = $end_date->format( 'Y-m-d' );

		return array(
			'start_date' => $start_date,
			'start_time' => $start_time,
			'end_date'   => $end_date,
			'end_time'   => '00:00:00',
		);
	}

	/**
	 * 掲載期間中判断
	 *
	 * @param array $instance instance.
	 *
	 * @return bool
	 */
	public static function is_during_the_period( $instance ) {
		$start_date = new DateTime( $instance['start_date'] . ' ' . $instance['start_time'] );
		$end_date   = new DateTime( $instance['end_date'] . ' ' . $instance['end_time'] );
		$now_date   = new DateTime( date_i18n( 'Y-m-d H:i:s' ) );
		/**
		 * 日付判断
		 */
		if ( $now_date < $start_date ) {
			return false;
		}
		if ( $instance['end_flag'] ) {
			if ( $end_date < $now_date ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * 特定タクソノミーのみ表示判断
	 *
	 * @param array $instance instance.
	 *
	 * @return bool
	 */
	public function has_term( $instance ) {
		if ( empty( $instance['taxonomy'] ) ) {
			return true;
		}
		$selected = $this->get_selected_taxonomy( $instance['taxonomy'] );
		if ( is_single() || is_category() || is_tag() || is_tax() ) {
			$terms = get_term_children( $selected['term_id'], $selected['taxonomy_name'] );
			if ( is_wp_error( $terms ) ) {
				$terms = array();
			}
			$terms[] = $selected['term_id'];
			if ( has_term( $terms, $selected['taxonomy_name'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 保存用選択値の作成
	 *
	 * @param object $taxonomy タクソノミーオブジェクト.
	 * @param object $term     タームオブジェクト.
	 *
	 * @return string
	 */
	public function get_select_taxonomy_value( $taxonomy, $term ) {
		return esc_attr( $taxonomy->name . $this->delimiter . $term->term_id );
	}

	/**
	 * 選択値をタクソノミーとタームに分割
	 *
	 * @param string $value 選択値.
	 *
	 * @return array
	 */
	public function get_selected_taxonomy( $value ) {
		$selected = explode( $this->delimiter, $value );
		if ( ! is_array( $selected ) ) {
			return null;
		}
		$term_label = '';
		$term_slug  = '';
		$term_id    = array_pop( $selected );
		$taxonomy   = implode( $this->delimiter, $selected );
		$term       = get_term( $term_id, $taxonomy );
		if ( ! is_wp_error( $term ) && ! is_null( $term ) ) {
			$term_label = $term->name;
			$term_slug  = $term->slug;
		}

		return array(
			'taxonomy_name' => $taxonomy,
			'term_id'       => $term_id,
			'term_label'    => $term_label,
			'term_slug'     => $term_slug,
		);
	}

	/**
	 * タクソノミー選択用Select出力
	 *
	 * @param WP_Widget $widget            ウィジェットオブジェクト.
	 * @param string    $selected_taxonomy 選択中ターム.
	 * @param array     $args              パラメータ.
	 */
	public function the_taxonomies_select_html( $widget, $selected_taxonomy, $args = array() ) {
		/**
		 * パラメーター初期化
		 */
		$args = wp_parse_args(
			$args,
			array(
				'object_type'   => array( 'post' ),
				'empty_message' => '',
			)
		);
		/**
		 * タクソノミー取得
		 */
		$taxonomies = get_taxonomies(
			array(
				'object_type' => $args['object_type'],
				'public'      => true,
				'show_ui'     => true,
			),
			'objects'
		);
		echo '<select name="' . $widget->get_field_name( 'taxonomy' ) . '">';
		echo '<option value="">';
		if ( ! empty( $taxonomies ) ) {
			echo '選択して下さい';
		} else {
			if ( empty( $args['empty_message'] ) ) {
				echo esc_html( $args['empty_message'] );
			} else {
				echo '選択できるカテゴリー・タグがありません';
			}
		}
		echo '</option>';
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				echo '<optgroup label="' . $taxonomy->label . '">';
				echo $this->get_taxonomies_option_html( $taxonomy, 0, $selected_taxonomy );
				echo '</optgroup>';
			}
		}
		echo '</select>';
	}

	/**
	 * タクソノミー一覧を階層構造を保ったまま作成する
	 *
	 * @param WP_Taxonomy $taxonomy          タクソノミー.
	 * @param integer     $parent            親タームID.
	 * @param string      $selected_taxonomy 選択中タクソノミー.
	 * @param string      $indent            インデント.
	 *
	 * @return string
	 */
	private function get_taxonomies_option_html( $taxonomy, $parent, $selected_taxonomy, $indent = '' ) {
		$result = '';
		$terms  = get_terms(
			$taxonomy->name,
			array(
				'parent'     => $parent,
				'hide_empty' => false,
			)
		);
		if ( 0 !== $parent ) {
			$indent .= '　';
		}
		foreach ( $terms as $term ) {
			$result .= sprintf(
				'<option value="%s" %s>%s</option>',
				$this->get_select_taxonomy_value( $taxonomy, $term ),
				selected( $this->get_select_taxonomy_value( $taxonomy, $term ), $selected_taxonomy ),
				esc_html( $indent . $term->name )
			);
			/**
			 * 階層設定に対応したタクソノミーは再帰処理
			 */
			if ( $taxonomy->hierarchical ) {
				$result .= $this->get_taxonomies_option_html( $taxonomy, $term->term_id, $selected_taxonomy, $indent );
			}
		}

		return $result;
	}

	/**
	 * チェックボックスのサニタイズ
	 *
	 * @param [type] $value チェックボックスのvalue.
	 *
	 * @return bool
	 */
	public function sanitize_checkbox( $value ) {
		if ( true === $value || 'true' === $value || 1 === $value || '1' === $value ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 日付の有効性チェック
	 *
	 * @param string $date    日付文字列.
	 * @param string $default デフォルト値.
	 *
	 * @return string
	 */
	public function sanitize_date( $date, $default = '' ) {
		if ( ! strptime( $date, '%Y-%m-%d' ) ) {
			return $default;
		}
		list( $y, $m, $d ) = explode( '-', $date );
		if ( checkdate( $m, $d, $y ) === true ) {
			return $date;
		} else {
			return $default;
		}
	}

	/**
	 * 時間の有効性チェック
	 *
	 * @param string $time    時間.
	 * @param string $default デフォルト値.
	 *
	 * @return string
	 */
	public function sanitize_time( $time, $default = '00:00' ) {
		update_option( 'test_time', $time );

		if ( 1 !== preg_match( '/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $time ) ) {
			return $default;
		}

		return $time;
	}
}