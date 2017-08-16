<?php
/**
 *	************************************
 *
 *	人気記事ランキング
 *
 *	************************************
 */
class YS_Ranking_Widget extends WP_Widget {


	private $default_title = '人気記事';
	private $default_post_count = 5;
	private $default_show_img = 1;
	private $default_img_width = 75;
	private $default_img_height = 75;


	/**
	 * ウィジェット名などを設定
	 */
	public function __construct() {
		parent::__construct(
			'ys_widgets_ranking', // Base ID
			'[ys]人気記事ランキング', // Name
			array( 'description' => '個別記事・カテゴリーアーカイブでは関連するカテゴリーのランキング、それ以外ではサイト全体の人気記事ランキングを表示します', ) // Args
		);
	}

	/**
	 * ウィジェットの内容を出力
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		extract( $args );
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$post_count = ( isset($instance['post_count']) ) ? esc_attr($instance['post_count']) : $this->default_post_count;
		$show_img = ( isset($instance['show_img']) ) ? esc_attr($instance['show_img']) : $this->default_show_img;
		$img_width = ( isset($instance['img_width']) ) ? esc_attr($instance['img_width']) : $this->default_img_width;
		$img_height = ( isset($instance['img_height']) ) ? esc_attr($instance['img_height']) : $this->default_img_height;

		echo $before_widget;
		// ウィジェットタイトル
		if ( empty( $title ) ) {
			$title = $this->default_title;
		}
		echo $before_title . apply_filters( 'widget_title', $title ). $after_title;
		// クエリ作成
		$query = null;
		$option = null;

		// 投稿とカテゴリーページの場合
		// カスタムタクソノミー対応は各自でやってください！
		if(is_single() || is_category()) {
			// カテゴリーで絞り込む
			$cat_ids = ys_utilities_get_cat_id_list();
			// オプションパラメータ作成
			$option = array('category__in'=>$cat_ids);
			// 投稿ならば表示中の投稿をのぞく
			if(is_single()){
				global $post;
				$option = wp_parse_args( array('post__not_in'=>array($post->ID)), $option );
			}
		}

		$option = apply_filters('ys_ranking_widget_option',$option);

		$query = ys_viewcount_get_query_all($post_count,$option);

		// 個別記事・カテゴリーアーカイブで関連記事が取れない場合、全体の人気記事にする
		if( ( is_single() || is_category() ) && !$query->have_posts()){
			wp_reset_postdata();
			$query = ys_viewcount_get_query_all($post_count);
		}

		if( $query -> have_posts() ) {
			$html_postlist = '';
				while ($query -> have_posts()) : $query -> the_post();
					//imgタグ作る
					$imgtag = '';
					$img = ys_utilities_get_post_thumbnail('thumbnail');
					if($show_img && $img) {
						$img[1] = $img_width;
						$img[2] = $img_height;
						$imgtag = '<img src="'.$img[0].'" '.image_hwstring( $img[1], $img[2] ).' alt="'.esc_attr($img[4]).'" />';

						$imgtag = apply_filters('ys_ranking_widget_image',$imgtag,$img);

					}
					// HTMLタグ作成
					$post_url = get_the_permalink();
					$post_title = get_the_title();

					$html_post = "<li><a class=\"clearfix\" href=\"$post_url\">$imgtag<p>$post_title</p></a></li>";
					$html_post = apply_filters('ys_ranking_widget_post',$html_post);
					$html_postlist .= $html_post;
				endwhile;

			$html_wrap = '<ul class="ys-ranking-list">{post_list}</ul>';
			$html_wrap = apply_filters('ys_ranking_widget_warp',$html_wrap);

			// 表示
			echo str_replace('{post_list}',$html_postlist,$html_wrap);

		} else {
			echo '<p>関連する人気記事はありません</p>';
		}
		wp_reset_postdata();

		echo $after_widget;

	}

	/**
	 * 管理用のオプションのフォームを出力
	 *
	 * @param array $instance ウィジェットオプション
	 */
	public function form( $instance ) {
		// 管理用のオプションのフォームを出力
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$post_count = ( isset($instance['post_count']) ) ? esc_attr($instance['post_count']) : $this->default_post_count;
		$show_img = ( isset($instance['show_img']) ) ? esc_attr($instance['show_img']) : $this->default_show_img;
		$img_width = ( isset($instance['img_width']) ) ? esc_attr($instance['img_width']) : $this->default_img_width;
		$img_height = ( isset($instance['img_height']) ) ? esc_attr($instance['img_height']) : $this->default_img_height;

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_count' ); ?>">表示する投稿数:</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="number" step="1" min="1" value="<?php echo $post_count; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_img' ); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id( 'show_img' ); ?>" name="<?php echo $this->get_field_name( 'show_img' ); ?>" value="1" <?php checked($show_img,1); ?> />アイキャッチ画像を表示する
			</label><br />
			<label for="<?php echo $this->get_field_id( 'img_width' ); ?>" style="display:inline-block;width:82px;">画像表示幅:</label>
			<input class="small-text" id="<?php echo $this->get_field_id( 'img_width' ); ?>" name="<?php echo $this->get_field_name( 'img_width' ); ?>" type="number" step="10" min="1" value="<?php echo $img_width; ?>" size="10" /><br />
			<label for="<?php echo $this->get_field_id( 'img_height' ); ?>" style="display:inline-block;width:82px;">画像表示高さ:</label>
			<input class="small-text" id="<?php echo $this->get_field_id( 'img_height' ); ?>" name="<?php echo $this->get_field_name( 'img_height' ); ?>" type="number" step="10" min="1" value="<?php echo $img_height; ?>" size="10" /><br />
		</p>
		<?php
	}

	/**
	 * ウィジェットオプションの保存処理
	 *
	 * @param array $new_instance 新しいオプション
	 * @param array $old_instance 以前のオプション
	 */
	public function update( $new_instance, $old_instance ) {
		// ウィジェットオプションの保存処理
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['post_count'] = ( is_numeric( $new_instance['post_count'] ) ) ? (int)$new_instance['post_count'] : $this->default_post_count;
		$instance['show_img'] = ys_utilities_sanitize_checkbox($new_instance['show_img']);
		$instance['img_width'] = ( is_numeric( $new_instance['img_width'] ) ) ? (int)$new_instance['img_width'] : $this->default_img_width;
		$instance['img_height'] = ( is_numeric( $new_instance['img_height'] ) ) ? (int)$new_instance['img_height'] : $this->default_img_height;

		return $instance;
	}
}