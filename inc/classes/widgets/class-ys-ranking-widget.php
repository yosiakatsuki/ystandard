<?php
/**
 * 人気記事ランキング
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 人気記事ランキングウィジェット
 */
class YS_Ranking_Widget extends WP_Widget {
	/**
	 * タイトル
	 *
	 * @var string
	 */
	private $default_title = '人気記事';
	/**
	 * 表示投稿数
	 *
	 * @var integer
	 */
	private $default_post_count = 5;
	/**
	 * 画像表示有無
	 *
	 * @var integer
	 */
	private $default_show_img = 1;
	/**
	 * 画像サイズ
	 *
	 * @var string
	 */
	private $default_thmbnail_size = 'thumbnail';
	/**
	 * 期間
	 *
	 * @var string
	 */
	private $default_period = 'all';
	/**
	 * 期間リスト
	 *
	 * @var array
	 */
	private $period_list = array(
		'all' => '全期間',
		'm'   => '月別',
		'w'   => '週別',
		'd'   => '日別',
	);
	/**
	 * 絞り込み初期値
	 *
	 * @var string
	 */
	private $default_filtering = 'cat';
	/**
	 * 絞り込みオプションの選択値
	 *
	 * @var array
	 */
	private $filtering_list = array(
		'cat' => '同じカテゴリーでのランキング',
		'all' => '全記事ランキング',
	);
	/**
	 * ウィジェット名などを設定
	 */
	public function __construct() {
		parent::__construct(
			'ys_widgets_ranking',
			'[ys]人気記事ランキング',
			array(
				'description' => '個別記事・カテゴリーアーカイブでは関連するカテゴリーのランキング、それ以外ではサイト全体の人気記事ランキングを表示します',
			)
		);
	}

	/**
	 * ウィジェットの内容を出力
	 *
	 * @param array $args args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		$title         = '';
		$post_count    = $this->default_post_count;
		$show_img      = $this->default_show_img;
		$thmbnail_size = $this->default_thmbnail_size;
		$period        = $this->default_period;
		$filtering     = $this->default_filtering;
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
			$thmbnail_size = esc_attr( $instance['thmbnail_size'] );
		}
		if ( isset( $instance['period'] ) ) {
			$period = esc_attr( $instance['period'] );
		}
		if ( isset( $instance['filtering'] ) ) {
			$filtering = esc_attr( $instance['filtering'] );
		}

		echo $args['before_widget'];
		/**
		 * ウィジェットタイトル
		 */
		if ( empty( $title ) ) {
			$title = $this->default_title;
		}
		echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		/**
		 * クエリ作成
		 */
		$query  = null;
		$option = null;
		/**
		 * 投稿とカテゴリーページの場合
		 * カスタムタクソノミー対応はそのうち
		 */
		if ( is_single() || is_category() ) {
			if ( 'cat' === $filtering ) {
				/**
				 * カテゴリーで絞り込む
				 */
				$cat_ids = ys_get_the_category_id_list();
				/**
				 * オプションパラメータ作成
				 */
				$option = array( 'category__in' => $cat_ids );
			}
			/**
			 * 投稿ならば表示中の投稿をのぞく
			 */
			if ( is_single() ) {
				global $post;
				$option = wp_parse_args(
					array( 'post__not_in' => array( $post->ID ) ),
					$option
				);
			}
		}
		$option = apply_filters( 'ys_ranking_widget_option', $option, $title );
		$query  = ys_get_post_views_query( $period, $post_count, $option );
		/**
		 * 個別記事・カテゴリーアーカイブで関連記事が取れない場合、全体の人気記事にする
		 */
		if ( ( is_single() || is_category() ) && ! $query->have_posts() ) {
			wp_reset_postdata();
			$query = ys_get_post_views_query( $period, $post_count );
		}
		if ( $query->have_posts() ) {
			$html = '';
			while ( $query->have_posts() ) {
				$query->the_post();
				$img      = '';
				$img_type = 'ys-ranking__img--no-img';
				if ( $show_img ) {
					$img      = $this->get_thumbnail( $thmbnail_size );
					$img_type = 'ys-ranking__img--' . $thmbnail_size;
				}
				/**
				 * HTMLタグ作成
				 */
				$post_url   = get_the_permalink();
				$post_title = get_the_title();

				$html_post = sprintf(
					'<li class="ys-ranking__item"><a class="image-mask__wrap clearfix" href="%s">%s<span class="ys-ranking__title">%s</span></a></li>',
					get_the_permalink(),
					$img,
					get_the_title()
				);
				$html_post = apply_filters( 'ys_ranking_widget_post', $html_post, get_the_ID() );
				$html     .= $html_post;
			}
			$wrap = '<ul class="ys-ranking__list ' . $img_type . '">%s</ul>';
			$html = sprintf(
				apply_filters( 'ys_ranking_widget_warp', $wrap ),
				$html
			);
			/**
			 * 表示
			 */
			echo $html;
		} else {
			echo '<p>関連する人気記事はありません</p>';
		}
		wp_reset_postdata();
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
		$title         = '';
		$post_count    = $this->default_post_count;
		$show_img      = $this->default_show_img;
		$thmbnail_size = $this->default_thmbnail_size;
		$period        = $this->default_period;
		$filtering     = $this->default_filtering;
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
			$thmbnail_size = esc_attr( $instance['thmbnail_size'] );
		}
		if ( isset( $instance['period'] ) ) {
			$period = esc_attr( $instance['period'] );
		}
		if ( isset( $instance['filtering'] ) ) {
			$filtering = esc_attr( $instance['filtering'] );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_count' ); ?>">表示する投稿数:</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" type="number" step="1" min="1" value="<?php echo $post_count; ?>" size="3" />
		</p>
		<div class="ysadmin-ranking-widget__thumb">
			<h4>画像設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_img' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_img' ); ?>" name="<?php echo $this->get_field_name( 'show_img' ); ?>" value="1" <?php checked( $show_img, 1 ); ?> />アイキャッチ画像を表示する
				</label><br />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'thmbnail_size' ); ?>">表示する画像のサイズ</label><br />
				<select name="<?php echo $this->get_field_name( 'thmbnail_size' ); ?>">
					<?php foreach ( $this->get_image_sizes() as $key => $value ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $thmbnail_size ); ?>><?php echo $key; ?> (横:<?php echo esc_html( $value['width'] ); ?> x 縦:<?php echo esc_html( $value['height'] ); ?>)</option>
					<?php endforeach; ?>
				</select>
				<span style="color:#aaa;font-size:.9em;">※「thumbnail」の場合、75pxの正方形で表示されます<br>※それ以外の画像はウィジェットの横幅に対して16:9の比率で表示されます。<br>※横幅300px~480pxの画像がおすすめです</span>
			</p>
		</div>
		<div class="ysadmin-ranking-widget__period">
			<h4>期間設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'period' ); ?>">ランキング作成の期間</label>
				<select name="<?php echo $this->get_field_name( 'period' ); ?>">
					<?php foreach ( $this->period_list as $key => $value ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $period ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<div class="ysadmin-ranking-widget__filtering">
			<h4>同じカテゴリーでの絞り込み</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'filtering' ); ?>">ランキングの記事絞り込み</label>
				<select name="<?php echo $this->get_field_name( 'filtering' ); ?>">
					<?php foreach ( $this->filtering_list as $key => $value ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $filtering ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<?php
	}

	/**
	 * ウィジェットオプションの保存処理
	 *
	 * @param array $new_instance 新しいオプション.
	 * @param array $old_instance 以前のオプション.
	 */
	public function update( $new_instance, $old_instance ) {
		/**
		 * 初期値のセット
		 */
		$instance                  = array();
		$instance['title']         = '';
		$instance['post_count']    = $this->default_post_count;
		$instance['show_img']      = $this->sanitize_checkbox( $new_instance['show_img'] );
		$instance['thmbnail_size'] = $this->default_thmbnail_size;
		$instance['period']        = $this->default_period;
		$instance['filtering']     = $this->default_filtering;
		/**
		 * 更新値のセット
		 */
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
		}
		if ( ( is_numeric( $new_instance['post_count'] ) ) ) {
			$instance['post_count'] = (int) $new_instance['post_count'];
		}
		if ( ( ! empty( $new_instance['thmbnail_size'] ) ) ) {
			$instance['thmbnail_size'] = $new_instance['thmbnail_size'];
		}
		if ( ( ! empty( $new_instance['period'] ) ) ) {
			$instance['period'] = $new_instance['period'];
		}
		if ( ( ! empty( $new_instance['filtering'] ) ) ) {
			$instance['filtering'] = $new_instance['filtering'];
		}
		return $instance;
	}

	/**
	 * 画像タグ作成
	 *
	 * @param [type] $thmbnail_size サムネイルタイプ.
	 * @return string
	 */
	private function get_thumbnail( $thmbnail_size ) {
		if ( has_post_thumbnail() ) {
			$thmbnail_size = apply_filters( 'ys_ranking_widget_thumbnail_type', $thmbnail_size );
			$img           = get_the_post_thumbnail( get_the_ID(), $thmbnail_size );
			$img           = apply_filters( 'ys_ranking_widget_image', $img, get_the_ID() );
			$img           = sprintf(
				'<figure class="ratio__image">%s</figure>',
				$img
			);
		} else {
			$img = '<div class="ys-ranking__no-img flex flex--c-c"><i class="fa fa-picture-o" aria-hidden="true"></i></div>';
		}
		$size = '16-9';
		if ( 'thumbnail' === $thmbnail_size ) {
			$size = '1-1';
		}
		$read_more = '<div class="image-mask flex flex--c-c"><p class="image-mask__text">' . ys_get_entry_read_more_text() . '</p></div>';
		$img       = sprintf(
			'<div class="ys-ranking__img"><div class="ratio ratio__' . $size . '"><div class="ratio__item">%s</div></div>%s</div>',
			$img,
			$read_more
		);
		return $img;
	}
	/**
	 * テーマ内で使える画像サイズ取得
	 */
	private function get_image_sizes() {
		global $_wp_additional_image_sizes;
		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
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
	 * チェックボックスのサニタイズ
	 *
	 * @param [type] $value チェックボックスのvalue.
	 * @return bool
	 */
	private function sanitize_checkbox( $value ) {
		if ( true == $value || 'true' === $value ) {
			return true;
		} else {
			return false;
		}
	}
}