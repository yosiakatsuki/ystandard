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
class YS_Widget_Post_Ranking extends YS_Widget_Get_Posts {
	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	protected $widget_id = 'ys_post_ranking';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	protected $widget_name = '[ys]人気記事ランキング';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = array(
		'classname'                   => 'ys_post_ranking',
		'description'                 => '個別記事・カテゴリーアーカイブでは関連するカテゴリーのランキング、それ以外ではサイト全体の人気記事ランキングを表示します',
		'customize_selective_refresh' => true,
	);

	/**
	 * デフォルトタイトル
	 *
	 * @var string
	 */
	protected $default_title = '人気記事';

	/**
	 * 実行するショートコード
	 *
	 * @var string
	 */
	protected $shortcode = 'ys_post_ranking';

	/**
	 * Sets up widget instance.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'filter_check' => true,
			)
		);
	}

	/**
	 * 管理用のオプションのフォームを出力
	 *
	 * @param array $instance ウィジェットオプション.
	 */
	protected function form_widget( $instance ) {
		?>
		<div class="ys-widget-option__section">
			<h4>ランキング設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'ranking_type' ); ?>">ランキングタイプ:</label>
				<select name="<?php echo $this->get_field_name( 'ranking_type' ); ?>">
					<?php foreach ( YS_Shortcode_Get_Posts::RANKING_TYPE as $key => $value ) : ?>
						<option
							value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['ranking_type'] ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'filter_check' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'filter_check' ); ?>" name="<?php echo $this->get_field_name( 'filter_check' ); ?>" value="1" <?php checked( ys_sanitize_bool( $instance['filter_check'] ), true ); ?> />同一カテゴリー内のランキングを作成する
				</label><br/>
			</p>
		</div>
		<?php
	}

	/**
	 * ウィジェットオプションの保存処理
	 *
	 * @param array $instance     更新用オプション.
	 * @param array $new_instance 新しいオプション.
	 * @param array $old_instance 以前のオプション.
	 *
	 * @return array
	 */
	protected function update_widget_option( $instance, $new_instance, $old_instance ) {

		$instance['ranking_type'] = $new_instance['ranking_type'];
		$instance['filter_check'] = $this->sanitize_checkbox( $new_instance['filter_check'] );
		if ( $this->sanitize_checkbox( $new_instance['filter_check'] ) ) {
			$instance['filter'] = 'category';
		} else {
			$instance['filter'] = '';
		}
		return $instance;
	}
}
