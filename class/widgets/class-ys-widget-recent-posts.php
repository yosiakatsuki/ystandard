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
class YS_Widget_Recant_Posts extends YS_Widget_Get_Posts {
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
	public $widget_options = array(
		'classname'                   => 'ys_recent_posts',
		'description'                 => '新着記事一覧',
		'customize_selective_refresh' => true,
	);

	/**
	 * デフォルトタイトル
	 *
	 * @var string
	 */
	protected $default_title = '記事一覧';

	/**
	 * 実行するショートコード
	 *
	 * @var string
	 */
	protected $shortcode = 'ys_recent_posts';

	/**
	 * Sets up widget instance.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'taxonomy_select' => array(),
			)
		);
	}

	/**
	 * 管理用のオプションのフォームを出力
	 *
	 * @param array $instance ウィジェットオプション.
	 */
	public function form_widget( $instance ) {
		?>
		<div class="ys-admin-section">
			<h4>カテゴリー・タグで記事を絞り込む</h4>
			<p>
				<?php $this->the_taxonomies_select_html( 'taxonomy_select', $instance['taxonomy_select'] ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * ウィジェット設定の作成
	 *
	 * @param array $instance     更新用オプション.
	 * @param array $new_instance 新しいオプション.
	 * @param array $old_instance 以前のオプション.
	 *
	 * @return array
	 */
	protected function update_widget_option( $instance, $new_instance, $old_instance ) {

		$instance['taxonomy_select'] = $new_instance['taxonomy_select'];

		$list = $this->get_selected_taxonomy_list( $new_instance['taxonomy_select'] );
		if ( ! empty( $list ) ) {
			$instance['taxonomy']  = $list[0]['taxonomy_name'];
			$instance['term_slug'] = $list[0]['term_slug'];
		}

		return $instance;
	}
}
