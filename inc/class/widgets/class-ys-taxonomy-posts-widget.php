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
class YS_Taxonomy_Posts_Widget extends YS_Widget_Post_List {
	/**
	 * タイトル
	 *
	 * @var string
	 */
	private $default_title = '記事一覧';

	/**
	 * ウィジェット名などを設定
	 */
	public function __construct() {
		parent::__construct(
			'ys_taxonomy_posts',
			'[ys]カテゴリー・タグの記事一覧',
			array(
				'description' => 'カテゴリー・タグが付いている記事一覧',
			)
		);
	}

	/**
	 * ウィジェットの内容を出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		$title          = '';
		$post_count     = $this->default_post_count;
		$show_img       = $this->default_show_img;
		$thumbnail_size = $this->default_thumbnail_size;
		$taxonomy_name  = '';
		$term_slug      = '';
		$term_label     = '';
		$mode           = $this->default_mode;
		$cols           = $this->default_cols;
		if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		if ( isset( $instance['post_count'] ) ) {
			$post_count = esc_attr( $instance['post_count'] );
		}
		if ( isset( $instance['show_img'] ) ) {
			$show_img = esc_attr( $instance['show_img'] );
		}
		if ( isset( $instance['thmbnail_size'] ) ) {
			$thumbnail_size = esc_attr( $instance['thmbnail_size'] );
		}
		if ( isset( $instance['thumbnail_size'] ) ) {
			$thumbnail_size = esc_attr( $instance['thumbnail_size'] );
		}
		if ( isset( $instance['taxonomy'] ) ) {
			$selected = $this->get_selected_taxonomy( $instance['taxonomy'] );
			if ( ! is_null( $selected ) ) {
				if ( empty( $title ) ) {
					$title = sprintf( '%sの記事一覧', $selected['term_label'] );
				}
			}
		}
		if ( isset( $instance['mode'] ) ) {
			$mode = esc_attr( $instance['mode'] );
		}
		if ( isset( $instance['cols'] ) ) {
			$cols = esc_attr( $instance['cols'] );
		}
		/**
		 * 画像なしの場合
		 */
		if ( ! $show_img ) {
			$thumbnail_size = '';
		}

		echo $args['before_widget'];
		/**
		 * ウィジェットタイトル
		 */
		if ( empty( $title ) ) {
			$title = $this->default_title;
		}
		$title = sprintf( $title, $selected['term_label'] );
		echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		echo do_shortcode( sprintf(
			'[ys_tax_posts title="%s" post_count="%s" show_img="%s" thumbnail_size="%s" taxonomy="%s" term_slug="%s" mode="%s" cols="%s"]',
			$title,
			$post_count,
			$show_img,
			$thumbnail_size,
			$selected['taxonomy_name'],
			$selected['term_slug'],
			$mode,
			$cols
		) );
		echo $args['after_widget'];
	}

	/**
	 * 管理用のオプションのフォームを出力
	 *
	 * @param array $instance ウィジェットオプション.
	 */
	public function form( $instance ) {
		/**
		 * 管理用のオプションのフォームを出力
		 */
		$title             = '';
		$post_count        = $this->default_post_count;
		$show_img          = $this->default_show_img;
		$thumbnail_size    = $this->default_thumbnail_size;
		$selected_taxonomy = '';
		$mode              = $this->default_mode;
		$cols              = $this->default_cols;
		if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		if ( isset( $instance['post_count'] ) ) {
			$post_count = esc_attr( $instance['post_count'] );
		}
		if ( isset( $instance['show_img'] ) ) {
			$show_img = esc_attr( $instance['show_img'] );
		}
		if ( isset( $instance['thmbnail_size'] ) ) {
			$thumbnail_size = esc_attr( $instance['thmbnail_size'] );
		}
		if ( isset( $instance['thumbnail_size'] ) ) {
			$thumbnail_size = esc_attr( $instance['thumbnail_size'] );
		}
		if ( isset( $instance['taxonomy'] ) ) {
			$selected_taxonomy = esc_attr( $instance['taxonomy'] );
		}
		if ( isset( $instance['mode'] ) ) {
			$mode = esc_attr( $instance['mode'] );
		}
		if ( isset( $instance['cols'] ) ) {
			$cols = esc_attr( $instance['cols'] );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widget" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_count' ); ?>">表示する投稿数:</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="number" step="1" min="1" value="<?php echo $post_count; ?>" size="3"/>
		</p>
		<div class="ys-admin-section">
			<h4>画像設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_img' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_img' ); ?>" name="<?php echo $this->get_field_name( 'show_img' ); ?>" value="1" <?php checked( $show_img, 1 ); ?> />アイキャッチ画像を表示する
				</label><br/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>">表示する画像のサイズ</label><br/>
				<select name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>">
					<?php foreach ( $this->get_image_sizes() as $key => $value ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $thumbnail_size ); ?>><?php echo $key; ?> (横:<?php echo esc_html( $value['width'] ); ?> x 縦:<?php echo esc_html( $value['height'] ); ?>)</option>
					<?php endforeach; ?>
				</select>
				<span class="ystandard-info--sub">※「thumbnail」の場合、75pxの正方形で表示されます<br>※それ以外の画像はウィジェットの横幅に対して16:9の比率で表示されます。<br>※横幅300px~480pxの画像がおすすめです</span>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>表示モード</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'mode' ); ?>">表示モード</label>
				<select name="<?php echo $this->get_field_name( 'mode' ); ?>">
					<?php
					$mode_list = YS_Post_List::get_mode();
					foreach ( $mode_list as $key => $value ) :
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $mode ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select><br>
				<span class="ystandard-info--sub">※表示モードが「横並び」「横スライド」の場合、「表示する画像サイズ」は「thumbnail」以外を選択して下さい。</span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'cols' ); ?>">列数</label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'cols' ); ?>" name="<?php echo $this->get_field_name( 'cols' ); ?>" type="number" step="1" min="1" max="4" value="<?php echo $cols; ?>" size="2"/><br>
				<span class="ystandard-info--sub">※表示モードが「横並び」の場合の表示列数を設定します。</span>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>カテゴリー・タグの選択</h4>
			<p>
				<?php $this->the_taxonomies_select_html( $this, $selected_taxonomy ); ?>
			</p>
		</div>
		<?php
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
		/**
		 * 初期値のセット
		 */
		$instance                   = array();
		$instance['title']          = '';
		$instance['post_count']     = $this->default_post_count;
		$instance['show_img']       = $this->sanitize_checkbox( $new_instance['show_img'] );
		$instance['thumbnail_size'] = $this->default_thumbnail_size;
		$instance['taxonomy']       = '';
		$instance['mode']           = $this->default_mode;
		$instance['cols']           = $this->sanitize_cols( $new_instance['cols'] );
		/**
		 * 更新値のセット
		 */
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
		}
		if ( ( is_numeric( $new_instance['post_count'] ) ) ) {
			$instance['post_count'] = (int) $new_instance['post_count'];
		}
		if ( ( ! empty( $new_instance['thumbnail_size'] ) ) ) {
			$instance['thumbnail_size'] = $new_instance['thumbnail_size'];
		}
		if ( ( ! empty( $new_instance['taxonomy'] ) ) ) {
			$instance['taxonomy'] = $new_instance['taxonomy'];
		}
		if ( ( ! empty( $new_instance['mode'] ) ) ) {
			$instance['mode'] = $new_instance['mode'];
		}

		return $instance;
	}
}
