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
	 * タクソノミーとタームの区切り文字
	 *
	 * @var string
	 */
	protected $delimiter = '__';

	/**
	 * 共通オプション
	 *
	 * @var array
	 */
	protected $base_options = array();

	/**
	 * Default instance.
	 *
	 * @var array
	 */
	protected $default_instance = array();

	/**
	 * 更新デフォルト値
	 *
	 * @var array
	 */
	protected $update_default = array();


	/**
	 * YS_Widget_Base constructor.
	 *
	 * @param string $id_base         ID.
	 * @param string $name            Name.
	 * @param array  $widget_options  Widget Options.
	 * @param array  $control_options Control Options.
	 */
	public function __construct( $id_base, $name, $widget_options = array(), $control_options = array() ) {
		/**
		 * ウィジェットオプション追加
		 */
		$widget_options = array_merge(
			array( 'customize_selective_refresh' => true ),
			$widget_options
		);
		parent::__construct( $id_base, $name, $widget_options, $control_options );
		/**
		 * オプション初期値を作成
		 */
		$this->base_options = array_merge(
			YS_Shortcode_Base::get_base_attr(),
			array(
				'taxonomy' => '',
			),
			$this->get_default_date()
		);
	}

	/**
	 * 日付条件のデフォルト値を取得
	 *
	 * @return array
	 */
	public static function get_default_date() {
		$date_format = YS_Shortcode_Base::get_date_format();
		$start_date  = date_i18n( 'Y-m-d' );
		$start_time  = date_i18n( 'H:i:00' );
		$end_date    = Datetime::createFromFormat(
			$date_format,
			date_i18n( $date_format )
		);
		$end_date->modify( '+7 days' );
		$end_date = $end_date->format( 'Y-m-d' );

		return array(
			'start_date' => $start_date,
			'start_time' => $start_time,
			'end_date'   => $end_date,
			'end_time'   => '00:00:00',
			'end_flag'   => false,
		);
	}

	/**
	 * 設定の初期値をセット
	 *
	 * @param array $instance 設定初期値.
	 */
	protected function set_default_instance( $instance ) {
		$this->default_instance = wp_parse_args(
			$instance,
			$this->base_options
		);
		/**
		 * 更新用デフォルト値
		 */
		$this->update_default = $this->default_instance;
		unset( $this->update_default['end_flag'] );
		unset( $this->update_default['display_pc'] );
		unset( $this->update_default['display_mobile'] );
		unset( $this->update_default['display_amp'] );
	}

	/**
	 * テーマ内で使える画像サイズ取得
	 */
	protected function get_image_sizes() {
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
	 * 保存用選択値の作成
	 *
	 * @param object $taxonomy タクソノミーオブジェクト.
	 * @param object $term     タームオブジェクト.
	 *
	 * @return string
	 */
	protected function get_select_taxonomy_value( $taxonomy, $term ) {
		return esc_attr( $taxonomy->name . $this->delimiter . $term->term_id );
	}

	/**
	 * 選択値をタクソノミーとタームに分割
	 *
	 * @param string $value 選択値.
	 *
	 * @return array
	 */
	protected function get_selected_taxonomy( $value ) {
		$selected = explode( $this->delimiter, $value );
		if ( ! is_array( $selected ) ) {
			return null;
		}
		$term_label = '';
		$term_slug  = '';
		/**
		 * 最後の要素をtermとして取り出す
		 */
		$term_id = array_pop( $selected );
		/**
		 * 残りをつなげてタクソノミーとする
		 */
		$taxonomy = implode( $this->delimiter, $selected );
		$term     = get_term( $term_id, $taxonomy );
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
	 * @param string $selected_taxonomy 選択中ターム.
	 * @param array  $args              パラメータ.
	 */
	protected function the_taxonomies_select_html( $selected_taxonomy, $args = array() ) {
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
		$args = apply_filters( 'ys_widget_taxonomies_select_args', $args );
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
		echo '<select name="' . $this->get_field_name( 'taxonomy' ) . '[]" multiple>';
		if ( empty( $taxonomies ) ) {
			echo '<option value="">';
			if ( empty( $args['empty_message'] ) ) {
				echo esc_html( $args['empty_message'] );
			} else {
				echo '選択できるカテゴリー・タグがありません';
			}
			echo '</option>';
		}

		/**
		 * タクソノミーごとにリストを作成
		 */
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				echo '<optgroup label="' . $taxonomy->label . '">';
				echo $this->get_taxonomies_option_html( $taxonomy, 0, $selected_taxonomy );
				echo '</optgroup>';
			}
		}
		echo '</select><br>';
		echo '<span class="ystandard-info--sub">※CtrlまたはCmdやShiftを押しながらクリックすることで複数選択することも可能です。<br>※選択解除する場合はCtrlまたはCmdを押しながらクリックして下さい。</span>';
	}

	/**
	 * タクソノミー一覧を階層構造を保ったまま作成する
	 *
	 * @param WP_Taxonomy $taxonomy          タクソノミー.
	 * @param integer     $parent            親タームID.
	 * @param array       $selected_taxonomy 選択中タクソノミー.
	 * @param string      $indent            インデント.
	 *
	 * @return string
	 */
	protected function get_taxonomies_option_html( $taxonomy, $parent, $selected_taxonomy, $indent = '' ) {
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
				selected( in_array( $this->get_select_taxonomy_value( $taxonomy, $term ), $selected_taxonomy, true ) ),
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
	 * 詳細設定
	 *
	 * @param array $instance 設定値.
	 */
	protected function form_ys_advanced( $instance ) {
		?>
		<div class="ys-widget-advanced-option">
			<h4 class="ys-widget-advanced-option__title">詳細設定</h4>
			<div class="ys-widget-advanced-option__toggle">
				<div class="ys-admin-section">
					<h5>掲載開始条件</h5>
					<p>
						<label for="<?php echo $this->get_field_id( 'start_date' ); ?>">開始日付</label>
						<input class="" id="<?php echo $this->get_field_id( 'start_date' ); ?>" name="<?php echo $this->get_field_name( 'start_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['start_date'] ); ?>"/><br>
						<label for="<?php echo $this->get_field_id( 'start_time' ); ?>">開始時間</label>
						<input class="" id="<?php echo $this->get_field_id( 'start_time' ); ?>" name="<?php echo $this->get_field_name( 'start_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['start_time'] ); ?>"/>
					</p>
				</div>
				<div class="ys-admin-section">
					<h5>掲載終了条件</h5>
					<p>
						<label for="<?php echo $this->get_field_id( 'end_flag' ); ?>">
							<input type="checkbox" id="<?php echo $this->get_field_id( 'end_flag' ); ?>" name="<?php echo $this->get_field_name( 'end_flag' ); ?>" value="1" <?php checked( $instance['end_flag'], 1 ); ?> />終了条件を有効にする</label>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'end_date' ); ?>">終了日付</label>
						<input class="" id="<?php echo $this->get_field_id( 'end_date' ); ?>" name="<?php echo $this->get_field_name( 'end_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['end_date'] ); ?>"/><br>
						<label for="<?php echo $this->get_field_id( 'end_time' ); ?>">終了時間</label>
						<input class="" id="<?php echo $this->get_field_id( 'end_time' ); ?>" name="<?php echo $this->get_field_name( 'end_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['end_time'] ); ?>"/>
					</p>
				</div>
				<div class="ys-admin-section">
					<h5>掲載するカテゴリー・タグ</h5>
					<p>
						<?php $this->the_taxonomies_select_html( $instance['taxonomy'] ); ?><br>
						<span class="ystandard-info--sub">※カテゴリー・タグを選択した場合、投稿詳細ページ かつ 該当のカテゴリー・タグをもつ投稿ページしか表示されません。（一覧ページなどでは表示されません）</span>
					</p>
				</div>
				<?php if ( ys_get_option( 'ys_amp_enable' ) ) : ?>
					<div class="ys-admin-section">
						<h5>PC・モバイル・AMPの表示選択</h5>
						<p>
							<label for="<?php echo $this->get_field_id( 'display_pc' ); ?>">
								<input type="checkbox" id="<?php echo $this->get_field_id( 'display_pc' ); ?>" name="<?php echo $this->get_field_name( 'display_pc' ); ?>" value="1" <?php checked( $instance['display_pc'], 1 ); ?> />PCページで表示する</label><br>
							<label for="<?php echo $this->get_field_id( 'display_mobile' ); ?>">
								<input type="checkbox" id="<?php echo $this->get_field_id( 'display_mobile' ); ?>" name="<?php echo $this->get_field_name( 'display_mobile' ); ?>" value="1" <?php checked( $instance['display_mobile'], 1 ); ?> />モバイルページで表示する</label><br>
							<label for="<?php echo $this->get_field_id( 'display_amp' ); ?>">
								<input type="checkbox" id="<?php echo $this->get_field_id( 'display_amp' ); ?>" name="<?php echo $this->get_field_name( 'display_amp' ); ?>" value="1" <?php checked( $instance['display_amp'], 1 ); ?> />AMPページで表示する</label>
						</p>
					</div>
				<?php endif; ?>
			</div><!-- /.ys-widget-advanced-option__toggle -->
		</div>
		<?php
	}


	/**
	 * 掲載期間中判断
	 *
	 * @param array $instance instance.
	 *
	 * @return bool
	 */
	protected function is_active_period( $instance ) {
		/**
		 * 日付判断
		 */
		return YS_Shortcode_Base::is_active_period(
			$instance['start_date'] . ' ' . $instance['start_time'],
			$instance['end_date'] . ' ' . $instance['end_time'],
			$instance['end_flag']
		);
	}

	/**
	 * 特定タクソノミーのみ表示判断
	 * TODO:タクソノミーの分割機能実装
	 *
	 * @param array $instance instance.
	 *
	 * @return bool
	 */
	protected function is_active_term( $instance ) {
		if ( empty( $instance['taxonomy'] ) ) {
			return true;
		}
		foreach ( $instance['taxonomy'] as $selected_taxonomy ) {
			$selected = $this->get_selected_taxonomy( $selected_taxonomy );
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
		}

		return false;
	}

	/**
	 * PC・SP・AMPの判断
	 *
	 * @param array $instance instance.
	 *
	 * @return bool
	 */
	protected function is_active_display_html_type( $instance ) {
		/**
		 * 表示タイプ判断
		 */
		return YS_Shortcode_Base::is_active_display_html_type(
			$instance['display_pc'],
			$instance['display_mobile'],
			$instance['display_amp']
		);
	}

	/**
	 * 共通部分の設定保存
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array
	 */
	protected function update_base_options( $new_instance, $old_instance ) {
		$new_instance = wp_parse_args( $new_instance, $this->update_default );
		$instance     = $old_instance;
		/**
		 * タイトル
		 */
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		/**
		 * 掲載開始
		 */
		$instance['start_date'] = $this->sanitize_date(
			$new_instance['start_date'],
			$this->default_instance['start_date']
		);
		$instance['start_time'] = $this->sanitize_time(
			$new_instance['start_time'],
			$this->default_instance['start_time']
		);
		/**
		 * 掲載終了
		 */
		$instance['end_flag'] = $this->get_checkbox_value( 'end_flag', $new_instance );
		$instance['end_date'] = $this->sanitize_date(
			$new_instance['end_date'],
			$this->default_instance['end_date']
		);
		$instance['end_time'] = $this->sanitize_time(
			$new_instance['end_time'],
			$this->default_instance['end_time']
		);
		/**
		 * カテゴリー・タグ
		 */
		$instance['taxonomy'] = $new_instance['taxonomy'];
		/**
		 * PC/モバイル・AMP表示
		 */
		$instance['display_pc']     = $this->get_checkbox_value( 'display_pc', $new_instance );
		$instance['display_mobile'] = $this->get_checkbox_value( 'display_mobile', $new_instance );
		$instance['display_amp']    = $this->get_checkbox_value( 'display_amp', $new_instance );

		return $instance;
	}

	/**
	 * チェックボックスの更新値を取得する
	 *
	 * @param string $key      キー.
	 * @param array  $instance 更新値.
	 *
	 * @return bool
	 */
	protected function get_checkbox_value( $key, $instance ) {
		if ( ! isset( $instance[ $key ] ) ) {
			return false;
		}

		return $this->sanitize_checkbox( $instance[ $key ] );
	}

	/**
	 * チェックボックスのサニタイズ
	 *
	 * @param [type] $value チェックボックスのvalue.
	 *
	 * @return bool
	 */
	protected function sanitize_checkbox( $value ) {
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
	protected function sanitize_date( $date, $default = '' ) {
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
	protected function sanitize_time( $time, $default = '00:00' ) {
		if ( 1 !== preg_match( '/^(0[0-9]{1}|1{1}[0-9]{1}|2{1}[0-3]{1}):(0[0-9]{1}|[1-5]{1}[0-9]{1})$/', $time ) ) {
			return $default;
		}

		return $time;
	}
}
