<?php
/**
 * 人気記事ランキング
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 人気記事ランキングウィジェット
 */
class YS_Widget_Recent_Posts extends WP_Widget {
	/**
	 * デフォルトタイトル
	 *
	 * @var string
	 */
	const TITLE = '記事一覧';

	/**
	 * タクソノミーとタームの区切り文字
	 *
	 * @var string
	 */
	const TAX_DELIMITER = '__';

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	protected $widget_id = 'ys_recent_posts';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	protected $widget_name = '[ys]新着記事一覧';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = [
		'classname'                   => 'ys_recent_posts',
		'description'                 => '新着記事一覧',
		'customize_selective_refresh' => true,
	];

	/**
	 * Default instance.
	 *
	 * @var array
	 */
	private $default_instance = [];

	/**
	 * Sets up widget instance.
	 */
	public function __construct() {
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$this->widget_options
		);
		/**
		 * 追加設定
		 */
		$this->default_instance = array_merge(
			\ystandard\Recent_Posts::SHORTCODE_ATTR,
			[
				'taxonomy_select' => [],
				'title'           => self::TITLE,
			]
		);
	}

	/**
	 * ウィジェットの内容を出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {

		/**
		 * ショートコード実行
		 */
		$shortcode = \ystandard\Utility::do_shortcode(
			\ystandard\Recent_Posts::SHORTCODE,
			$instance,
			null,
			false
		);
		/**
		 * 結果があれば内容表示
		 */
		if ( $shortcode ) {
			echo $args['before_widget'];
			/**
			 * ウィジェットタイトル
			 */
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			echo $shortcode;
			echo $args['after_widget'];
		}
	}

	/**
	 * 管理用のオプションのフォームを出力
	 *
	 * @param array $instance ウィジェットオプション.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			$this->default_instance
		);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<div class="widget-form__section">
			<h4>表示設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'count' ); ?>">表示する投稿数:</label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['count']; ?>" size="3"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'list_type' ); ?>">表示タイプ:</label>
				<select name="<?php echo $this->get_field_name( 'list_type' ); ?>">
					<?php
					foreach ( \ystandard\Recent_Posts::LIST_TYPE as $key => $value ) :
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['list_type'] ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_category' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" value="1" <?php checked( \ystandard\Utility::to_bool( $instance['show_category'] ), true ); ?> />カテゴリーを表示する
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_date' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" value="1" <?php checked( \ystandard\Utility::to_bool( $instance['show_date'] ), true ); ?> />日付を表示する
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" value="1" <?php checked( \ystandard\Utility::to_bool( $instance['show_excerpt'] ), true ); ?> />抜粋を表示する
				</label>
			</p>
		</div>
		<div class="widget-form__section">
			<h4>表示列数設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'col_sp' ); ?>"><i class="fas fa-mobile-alt fa-fw"></i></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_sp' ); ?>" name="<?php echo $this->get_field_name( 'col_sp' ); ?>" type="number" step="1" min="1" max="6" value="<?php echo $this->sanitize_col( $instance['col_sp'] ); ?>" size="1"/><br>
				<label for="<?php echo $this->get_field_id( 'col_tablet' ); ?>"><i class="fas fa-tablet-alt fa-fw"></i></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_tablet' ); ?>" name="<?php echo $this->get_field_name( 'col_tablet' ); ?>" type="number" step="1" min="1" max="6" value="<?php echo $this->sanitize_col( $instance['col_tablet'] ); ?>" size="1"/><br>
				<label for="<?php echo $this->get_field_id( 'col_pc' ); ?>"><i class="fas fa-desktop fa-fw"></i></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_pc' ); ?>" name="<?php echo $this->get_field_name( 'col_pc' ); ?>" type="number" step="1" min="1" max="6" value="<?php echo $this->sanitize_col( $instance['col_pc'] ); ?>" size="1"/><br>
				<span class="ys-widget-form__sub">※表示タイプが「横スライド」の場合、列設定は無視されます。</span>
			</p>
		</div>
		<div class="ys-widget-form__section">
			<h4>画像設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_img' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_img' ); ?>" name="<?php echo $this->get_field_name( 'show_img' ); ?>" value="1" <?php checked( \ystandard\Utility::to_bool( $instance['show_img'] ), true ); ?> />アイキャッチ画像を表示する
				</label><br/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>">表示する画像のサイズ:</label><br/>
				<select name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>">
					<?php foreach ( $this->get_image_sizes() as $key => $value ) : ?>
						<option
							value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['thumbnail_size'] ); ?>><?php echo $key; ?>
							(横:<?php echo esc_html( $value['width'] ); ?> x
							縦:<?php echo esc_html( $value['height'] ); ?>)
						</option>
					<?php endforeach; ?>
				</select><br/>
				<span class="ys-widget-option__sub">※表示タイプが「カード」の場合、「表示する画像サイズ」は「thumbnail」以外を選択して下さい。</span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'thumbnail_ratio' ); ?>">表示する画像の縦横比:</label><br/>
				<select name="<?php echo $this->get_field_name( 'thumbnail_ratio' ); ?>">
					<?php foreach ( \ystandard\Recent_Posts::RATIO_TYPE as $key => $value ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['thumbnail_ratio'] ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<div class="widget-form__section">
			<h4>カテゴリー・タグで記事を絞り込む</h4>
			<p>
				<?php $this->the_taxonomies_select_html( $instance['taxonomy_select'] ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * タクソノミー選択用Select出力
	 *
	 * @param array $selected_taxonomy 選択中ターム.
	 */
	private function the_taxonomies_select_html( $selected_taxonomy ) {
		/**
		 * タクソノミー取得
		 */
		$taxonomies = get_taxonomies(
			[
				'object_type' => 'post',
				'public'      => true,
				'show_ui'     => true,
			],
			'objects'
		);
		?>
		<select name="<?php $this->get_field_name( 'taxonomy_select' ); ?>[]" class="ys-widget__select multiple" size="6" multiple>
			<option value=""><?php echo empty( $taxonomies ) ? '選択できるカテゴリー・タグがありません' : '選択して下さい'; ?></option>
			<?php
			/**
			 * タクソノミーごとにリストを作成
			 */
			if ( ! empty( $taxonomies ) ) :
				foreach ( $taxonomies as $taxonomy ) :
					?>
					<optgroup label="<?php echo $taxonomy->label; ?>">
						<?php $this->get_taxonomies_option_html( $taxonomy, 0, $selected_taxonomy ); ?>
					</optgroup>
				<?php
				endforeach;
			endif;
			?>
		</select><br>
		<span class="ys-widget-form__sub">※CtrlまたはCmdやShiftを押しながらクリックすることで複数選択することも可能です。<br>※選択解除する場合はCtrlまたはCmdを押しながらクリックして下さい。</span>
		<?php
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
	private function get_taxonomies_option_html( $taxonomy, $parent, $selected_taxonomy, $indent = '' ) {
		$result = '';
		$terms  = get_terms(
			$taxonomy->name,
			[
				'parent'     => $parent,
				'hide_empty' => false,
			]
		);
		if ( 0 !== $parent ) {
			$indent .= '　';
		}
		foreach ( $terms as $term ) :
			?>
			<option value="<?php echo $this->get_select_taxonomy_value( $taxonomy, $term ); ?>" <?php echo selected( in_array( $this->get_select_taxonomy_value( $taxonomy, $term ), $selected_taxonomy, true ) ); ?>><?php echo esc_html( $indent . $term->name ); ?></option>
			<?php
			/**
			 * 階層設定に対応したタクソノミーは再帰処理
			 */
			if ( $taxonomy->hierarchical ) {
				$result .= $this->get_taxonomies_option_html( $taxonomy, $term->term_id, $selected_taxonomy, $indent );
			}
		endforeach;

		return $result;
	}

	/**
	 * 保存用選択値の作成
	 *
	 * @param object $taxonomy タクソノミーオブジェクト.
	 * @param object $term     タームオブジェクト.
	 *
	 * @return string
	 */
	private function get_select_taxonomy_value( $taxonomy, $term ) {
		return esc_attr( $taxonomy->name . self::TAX_DELIMITER . $term->term_id );
	}

	/**
	 * 選択値のリストをタクソノミーとタームに分割
	 *
	 * @param array $list 選択値のリスト.
	 *
	 * @return array|bool
	 */
	protected function get_selected_taxonomy_list( $list ) {
		$result = [];
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
		$selected = explode( self::TAX_DELIMITER, $value );
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
		$taxonomy = implode( self::TAX_DELIMITER, $selected );
		$term     = get_term( $term_id, $taxonomy );
		if ( ! is_wp_error( $term ) && null !== $term ) {
			$term_label = $term->name;
			$term_slug  = $term->slug;
		}

		return [
			'taxonomy_name' => $taxonomy,
			'term_id'       => $term_id,
			'term_label'    => $term_label,
			'term_slug'     => $term_slug,
		];
	}

	/**
	 * ウィジェットオプションの保存処理
	 *
	 * @param array $new_instance 新しいオプション.
	 * @param array $old_instance 以前のオプション.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance['count']           = $this->sanitize_num(
			$new_instance['count'],
			$this->default_instance['count']
		);
		$instance['list_type']       = $new_instance['list_type'];
		$instance['col_sp']          = $this->sanitize_col(
			$new_instance['col_sp'],
			$this->default_instance['col_sp']
		);
		$instance['col_tablet']      = $this->sanitize_col(
			$new_instance['col_tablet'],
			$this->default_instance['col_tablet']
		);
		$instance['col_pc']          = $this->sanitize_col(
			$new_instance['col_pc'],
			$this->default_instance['col_pc']
		);
		$instance['show_img']        = $this->sanitize_checkbox( $new_instance['show_img'] );
		$instance['show_date']       = $this->sanitize_checkbox( $new_instance['show_date'] );
		$instance['show_category']   = $this->sanitize_checkbox( $new_instance['show_category'] );
		$instance['show_excerpt']    = $this->sanitize_checkbox( $new_instance['show_excerpt'] );
		$instance['thumbnail_size']  = $new_instance['thumbnail_size'];
		$instance['thumbnail_ratio'] = $new_instance['thumbnail_ratio'];

		$instance['taxonomy_select'] = $new_instance['taxonomy_select'];

		$list = $this->get_selected_taxonomy_list( $new_instance['taxonomy_select'] );
		if ( ! empty( $list ) ) {
			$instance['taxonomy']  = $list[0]['taxonomy_name'];
			$instance['term_slug'] = $list[0]['term_slug'];
		}

		return $instance;
	}

	/**
	 * チェックボックスのサニタイズ
	 *
	 * @param [type] $value チェックボックスのvalue.
	 *
	 * @return bool
	 */
	private function sanitize_checkbox( $value ) {
		return \ystandard\Utility::to_bool( $value );
	}

	/**
	 * 数値チェック
	 *
	 * @param int $value   value.
	 * @param int $default default.
	 *
	 * @return int
	 */
	private function sanitize_num( $value, $default = 1 ) {
		if ( ! is_numeric( $value ) ) {
			return $default;
		}

		return $value;
	}

	/**
	 * カラム数のサニタイズ
	 *
	 * @param int|string $col カラム数.
	 *
	 * @return int
	 */
	private function sanitize_col( $col, $default = 1 ) {

		if ( ! is_numeric( $col ) ) {
			return $default;
		}
		$col = (int) $col;
		if ( 1 > $col ) {
			return 1;
		}
		if ( 6 < $col ) {
			return 6;
		}

		return $col;
	}
}
