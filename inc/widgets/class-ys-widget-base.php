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
	 * 日付部分のフォーマット
	 */
	const DATE_FORMAT = 'Y-m-d';
	/**
	 * 時間部分のフォーマット
	 */
	const TIME_FORMAT = 'H:i:00';
	/**
	 * 日時フォーマット
	 */
	const DATETIME_FORMAT = 'Y-m-d H:i:00';
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
			$this->get_default_date(),
			array(
				'display_tax_select' => array(),
			)
		);
	}

	/**
	 * 日付条件のデフォルト値を取得
	 *
	 * @return array
	 */
	public static function get_default_date() {
		$start_date = date_i18n( self::DATE_FORMAT );
		$start_time = date_i18n( self::TIME_FORMAT );
		$end_date   = Datetime::createFromFormat(
			self::DATETIME_FORMAT,
			date_i18n( self::DATETIME_FORMAT )
		);
		$end_date->modify( '+7 days' );
		$end_date = $end_date->format( self::DATE_FORMAT );

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
		/**
		 * フルサイズ追加
		 */
		$sizes['full'] = array(
			'width'  => '-',
			'height' => '-',
		);

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
		return YS_Shortcode_Base::join_tax_term( $taxonomy->name, $term->term_id );
	}

	/**
	 * 選択値のリストをタクソノミーとタームに分割
	 *
	 * @param array $list 選択値のリスト.
	 *
	 * @return array|bool
	 */
	protected function get_selected_taxonomy_list( $list ) {
		$result = array();
		if ( empty( $list ) ) {
			return false;
		}
		foreach ( $list as $item ) {
			$result[] = $this->get_selected_taxonomy( $item );
		}

		return $result;
	}

	/**
	 * 選択値をタクソノミーとタームに分割
	 *
	 * @param string $value 選択値.
	 *
	 * @return array
	 */
	protected function get_selected_taxonomy( $value ) {
		return YS_Shortcode_Base::split_tax_term( $value );
	}

	/**
	 * タクソノミー選択用Select出力
	 *
	 * @param string $form_name         項目名.
	 * @param array  $selected_taxonomy 選択中ターム.
	 * @param bool   $multiple          複数選択.
	 * @param array  $args              パラメータ.
	 */
	protected function the_taxonomies_select_html( $form_name, $selected_taxonomy, $multiple = false, $args = array() ) {
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
		/**
		 * 複数選択
		 */
		$multiple_attr  = '';
		$multiple_class = '';
		if ( $multiple ) {
			$multiple_attr  = ' size="6" multiple';
			$multiple_class = ' multiple';
		}
		echo '<select name="' . $this->get_field_name( $form_name ) . '[]" class="ys-widget-option__select' . $multiple_class . '"' . $multiple_attr . '>';
		echo '<option value="">';
		if ( empty( $taxonomies ) ) {
			if ( empty( $args['empty_message'] ) ) {
				echo esc_html( $args['empty_message'] );
			} else {
				echo '選択できるカテゴリー・タグがありません';
			}
		} else {
			echo '選択して下さい';
		}
		echo '</option>';

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
		/**
		 * 案内文
		 */
		if ( $multiple ) {
			echo '<span class="ys-widget-option__sub">※CtrlまたはCmdやShiftを押しながらクリックすることで複数選択することも可能です。<br>※選択解除する場合はCtrlまたはCmdを押しながらクリックして下さい。</span>';
		}
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
				selected( ys_in_array( $this->get_select_taxonomy_value( $taxonomy, $term ), $selected_taxonomy ) ),
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
	 * タクソノミー選択用 チェックボックス
	 *
	 * @param string $form_name         項目名.
	 * @param array  $selected_taxonomy 選択中ターム.
	 * @param array  $args              パラメータ.
	 */
	protected function the_taxonomies_checkbox_list( $form_name, $selected_taxonomy, $args = array() ) {
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
		echo '<div class="ys-widget-option__check-list">';
		if ( empty( $taxonomies ) ) {
			if ( empty( $args['empty_message'] ) ) {
				echo esc_html( $args['empty_message'] );
			} else {
				echo '選択できるカテゴリー・タグがありません';
			}
		} else {
			/**
			 * タクソノミーごとにリストを作成
			 */
			foreach ( $taxonomies as $taxonomy ) {
				echo '<div class="ys-widget-option__check-list-group">';
				echo '<div class="ys-widget-option__check-list-group-title">' . $taxonomy->label . '</div>';
				echo $this->the_taxonomies_checkbox( $form_name, $taxonomy, 0, $selected_taxonomy );
				echo '</div>';
			}
		}
		echo '</div>';
	}

	/**
	 * タクソノミー一覧を階層構造を保ったまま作成する
	 *
	 * @param string      $form_name         項目名.
	 * @param WP_Taxonomy $taxonomy          タクソノミー.
	 * @param integer     $parent            親タームID.
	 * @param array       $selected_taxonomy 選択中タクソノミー.
	 * @param string      $indent            インデント.
	 *
	 * @return string
	 */
	protected function the_taxonomies_checkbox( $form_name, $taxonomy, $parent, $selected_taxonomy, $indent = '' ) {
		/**
		 * ターム取得
		 */
		$terms = get_terms(
			$taxonomy->name,
			array(
				'parent'     => $parent,
				'hide_empty' => false,
			)
		);
		if ( ! $terms ) {
			return '';
		}
		/**
		 * リスト作成
		 */
		if ( 0 !== $parent ) {
			$indent .= '';
		}
		$result = '<div class="ys-widget-option__check-list-group-item">';
		foreach ( $terms as $term ) {
			$result .= sprintf(
				'<label for="%s"><input type="checkbox" id="%s" name="%s" value="%s" %s />%s</label>',
				$this->get_field_id( $form_name ) . $term->slug,
				$this->get_field_id( $form_name ) . $term->slug,
				$this->get_field_name( $form_name ) . '[]',
				$this->get_select_taxonomy_value( $taxonomy, $term ),
				checked( ys_in_array( $this->get_select_taxonomy_value( $taxonomy, $term ), $selected_taxonomy ), true, false ),
				esc_html( $indent . $term->name )
			);
			/**
			 * 階層設定に対応したタクソノミーは再帰処理
			 */
			if ( $taxonomy->hierarchical ) {
				$result .= $this->the_taxonomies_checkbox( $form_name, $taxonomy, $term->term_id, $selected_taxonomy, $indent );
			}
		}

		return $result . '</div>';
	}

	/**
	 * 詳細設定
	 *
	 * @param array $instance 設定値.
	 */
	protected function form_ys_advanced( $instance ) {
		?>
		<div class="ys-widget-option">
			<h4 class="ys-widget-option__title">詳細設定</h4>
			<input type="checkbox" id="ys-widget-<?php echo $this->get_field_id( 'option__toggle' ); ?>" class="fas ys-widget-option__toggle">
			<label class="ys-widget-option__toggle-label" for="ys-widget-<?php echo $this->get_field_id( 'option__toggle' ); ?>">
				<span class="fas ys-widget-option__toggle-icon"></span>
			</label>
			<div class="ys-widget-option__container">
				<div class="ys-widget-option__section">
					<h4>掲載開始時間</h4>
					<p>
						<label for="<?php echo $this->get_field_id( 'start_date' ); ?>">開始日付</label>
						<input class="" id="<?php echo $this->get_field_id( 'start_date' ); ?>" name="<?php echo $this->get_field_name( 'start_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['start_date'] ); ?>"/><br>
						<label for="<?php echo $this->get_field_id( 'start_time' ); ?>">開始時間</label>
						<input class="" id="<?php echo $this->get_field_id( 'start_time' ); ?>" name="<?php echo $this->get_field_name( 'start_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['start_time'] ); ?>"/>
					</p>
				</div>
				<div class="ys-widget-option__section">
					<h4>掲載終了時間</h4>
					<p>
						<label for="<?php echo $this->get_field_id( 'end_flag' ); ?>">
							<input type="checkbox" id="<?php echo $this->get_field_id( 'end_flag' ); ?>" name="<?php echo $this->get_field_name( 'end_flag' ); ?>" value="1" <?php checked( $instance['end_flag'], 1 ); ?> />掲載終了時間を有効にする</label>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'end_date' ); ?>">終了日付</label>
						<input class="" id="<?php echo $this->get_field_id( 'end_date' ); ?>" name="<?php echo $this->get_field_name( 'end_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['end_date'] ); ?>"/><br>
						<label for="<?php echo $this->get_field_id( 'end_time' ); ?>">終了時間</label>
						<input class="" id="<?php echo $this->get_field_id( 'end_time' ); ?>" name="<?php echo $this->get_field_name( 'end_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['end_time'] ); ?>"/>
					</p>
				</div>
				<div class="ys-widget-option__section">
					<h4>掲載するカテゴリー・タグ</h4>
					<p>
						<?php $this->the_taxonomies_checkbox_list( 'display_tax_select', $instance['display_tax_select'], true ); ?><br>
						<span class="ys-widget-option__sub">※カテゴリー・タグを選択した場合、投稿詳細ページ かつ 該当のカテゴリー・タグをもつ投稿ページしか表示されません。（一覧ページなどでは表示されません）</span>
					</p>
				</div>
				<div class="ys-widget-option__section">
					<h4>PC・モバイル・AMPの表示選択</h4>
					<p>
						<label for="<?php echo $this->get_field_id( 'display_pc' ); ?>">
							<input type="checkbox" id="<?php echo $this->get_field_id( 'display_pc' ); ?>" name="<?php echo $this->get_field_name( 'display_pc' ); ?>" value="1" <?php checked( $instance['display_pc'], 1 ); ?> />PCページで表示する</label><br>
						<label for="<?php echo $this->get_field_id( 'display_mobile' ); ?>">
							<input type="checkbox" id="<?php echo $this->get_field_id( 'display_mobile' ); ?>" name="<?php echo $this->get_field_name( 'display_mobile' ); ?>" value="1" <?php checked( $instance['display_mobile'], 1 ); ?> />モバイルページで表示する</label><br>
						<label for="<?php echo $this->get_field_id( 'display_amp' ); ?>">
							<?php if ( ys_get_option_by_bool( 'ys_amp_enable', false ) || ys_get_option_by_bool( 'ys_amp_enable_amp_plugin_integration', false ) ) : ?>
							<input type="checkbox" id="<?php echo $this->get_field_id( 'display_amp' ); ?>" name="<?php echo $this->get_field_name( 'display_amp' ); ?>" value="1" <?php checked( $instance['display_amp'], 1 ); ?> />AMPページで表示する</label>
						<?php endif; ?>
					</p>
				</div>
			</div><!-- /.ys-widget-option__toggle -->
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
			$instance['display_start_date'],
			$instance['display_end_date'],
			$instance['enable_end_date']
		);
	}

	/**
	 * 特定タクソノミーのみ表示判断
	 *
	 * @param array $instance instance.
	 *
	 * @return bool
	 */
	protected function is_active_term( $instance ) {

		return YS_Shortcode_Base::is_active_display_term( $instance['display_tax_list'] );
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
	 * テーマ独自条件設定の確認
	 *
	 * @param array $instance Instance.
	 *
	 * @return bool
	 */
	protected function is_active_ystandard_widget( $instance ) {
		/**
		 * 日付判断
		 */
		if ( ! $this->is_active_period( $instance ) ) {
			return false;
		}
		/**
		 * タクソノミー判断
		 */
		if ( ! $this->is_active_term( $instance ) ) {
			return false;
		}
		/**
		 * 表示ディスプレイタイプ判断
		 */
		if ( ! $this->is_active_display_html_type( $instance ) ) {
			return false;
		}

		return true;
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
		if ( isset( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		/**
		 * 掲載開始
		 */
		$instance['start_date']         = $this->sanitize_date(
			$new_instance['start_date'],
			$this->default_instance['start_date']
		);
		$instance['start_time']         = $this->sanitize_time(
			$new_instance['start_time'],
			$this->default_instance['start_time']
		);
		$instance['display_start_date'] = $this->get_display_date( $instance['start_date'], $instance['start_time'] );
		/**
		 * 掲載終了
		 */
		$instance['end_flag']         = $this->get_checkbox_value( 'end_flag', $new_instance );
		$instance['end_date']         = $this->sanitize_date(
			$new_instance['end_date'],
			$this->default_instance['end_date']
		);
		$instance['end_time']         = $this->sanitize_time(
			$new_instance['end_time'],
			$this->default_instance['end_time']
		);
		$instance['display_end_date'] = $this->get_display_date( $instance['end_date'], $instance['end_time'] );
		$instance['enable_end_date']  = $instance['end_flag'];

		/**
		 * カテゴリー・タグ
		 */
		$instance['display_tax_select'] = $new_instance['display_tax_select'];
		$instance['display_tax_list']   = $this->get_display_tax_list( $new_instance['display_tax_select'] );
		/**
		 * PC/モバイル・AMP表示
		 */
		$instance['display_pc']     = $this->get_checkbox_value( 'display_pc', $new_instance );
		$instance['display_mobile'] = $this->get_checkbox_value( 'display_mobile', $new_instance );
		$instance['display_amp']    = $this->get_checkbox_value( 'display_amp', $new_instance );

		return $instance;
	}

	/**
	 * ショートコードで使う日付文字列に変換する
	 *
	 * @param string $date 日付.
	 * @param string $time 時間.
	 *
	 * @return string
	 */
	private function get_display_date( $date, $time ) {
		if ( '' === $date || '' === $time ) {
			return '';
		}

		/**
		 * 時間部分の補完
		 */
		if ( 5 === strlen( $time ) ) {
			$time = $time . ':00';
		}
		/**
		 * Date作成
		 */
		$datetime = DateTime::createFromFormat(
			self::DATETIME_FORMAT,
			$date . ' ' . $time
		);
		/**
		 * 変換できなければ空を返す
		 */
		if ( ! $datetime ) {
			return '';
		}

		/**
		 * ショートコード側で使う日付フォーマットで変換する
		 */
		return $datetime->format( YS_Shortcode_Base::get_date_format() );
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
	 * ショートコード動作用のタクソノミー・ターム文字列を作る
	 *
	 * @param array $display_tax_select 選択した表示タクソノミー情報.
	 *
	 * @return string
	 */
	private function get_display_tax_list( $display_tax_select ) {
		/**
		 * 無ければ空文字
		 */
		if ( empty( $display_tax_select ) ) {
			return '';
		}
		$result = '';
		/**
		 * 選択されたタクソノミー + タームidをタクソノミー + タームslug にしてすべて結合する
		 */
		foreach ( $display_tax_select as $item ) {
			$tax = $this->get_selected_taxonomy( $item );
			if ( null !== $tax ) {
				$result .= YS_Shortcode_Base::join_tax_term( $tax['taxonomy_name'], $tax['term_slug'] ) . YS_Shortcode_Base::TAX_LIST_DELIMITER;
			}
		}

		return trim( $result );
	}

	/**
	 * チェックボックスのサニタイズ
	 *
	 * @param [type] $value チェックボックスのvalue.
	 *
	 * @return bool
	 */
	protected function sanitize_checkbox( $value ) {
		return ys_to_bool( $value );
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

	/**
	 * 数値チェック
	 *
	 * @param int $value   value.
	 * @param int $default default.
	 *
	 * @return int
	 */
	protected function sanitize_num( $value, $default = 1 ) {
		if ( ! is_numeric( $value ) ) {
			return $default;
		}

		return $value;
	}
}
