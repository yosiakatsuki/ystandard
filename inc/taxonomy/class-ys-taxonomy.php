<?php
/**
 * タクソノミー関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Taxonomy
 */
class YS_Taxonomy {

	/**
	 * YS_Taxonomy constructor.
	 */
	public function __construct() {
		$this->add_term_meta_options();
		add_filter( 'ys_get_the_archive_title', array( $this, 'override_title' ) );
		add_filter( 'get_the_archive_description', array( $this, 'override_description' ) );
	}

	/**
	 * タームの拡張設定追加
	 */
	private function add_term_meta_options() {
		/**
		 * 設定追加対象のタクソノミー
		 */
		$taxonomies = apply_filters(
			'ys_add_term_meta_options_taxonomies',
			array(
				'category',
				'post_tag',
			)
		);

		foreach ( $taxonomies as $tax ) {
			add_action( "${tax}_add_form_fields", array( $this, 'add_form_fields' ) );
			add_action( "${tax}_edit_form_fields", array( $this, 'edit_form_fields' ), 10, 2 );
			add_action( "create_${tax}", array( $this, 'update_term_meta' ) );
			add_action( "edit_${tax}", array( $this, 'update_term_meta' ) );
		}

	}


	/**
	 * カテゴリー追加画面に入力欄追加
	 *
	 * @param string $taxonomy The taxonomy slug.
	 */
	public function add_form_fields( $taxonomy ) {
		$taxonomy = get_taxonomy( $taxonomy );
		?>
		<div class="form-field term-title-override-wrap">
			<label for="title-override">[ys]一覧ページのタイトルを置き換える</label>
			<input name="title-override" id="title-override" type="text" value="" size="40">
			<p><?php echo $taxonomy->label; ?>一覧ページのタイトルを入力した内容に置き換えます。</p>
		</div>
		<div class="form-field term-dscr-override-wrap">
			<label for="dscr-override">[ys]一覧ページの説明を置き換える</label>
			<?php
			wp_dropdown_pages(
				array(
					'name'              => 'dscr-override',
					'echo'              => true,
					'show_option_none'  => __( '&mdash; Select &mdash;' ),
					'option_none_value' => '0',
					'selected'          => '0',
					'post_type'         => 'ys-parts',
					'post_status'       => 'publish,private',
				)
			)
			?>
			<p><?php echo $taxonomy->label; ?>の説明を選択した[ys]パーツの内容に置き換えます。</p>
		</div>
		<?php
	}

	/**
	 * カテゴリー編集画面に入力欄追加
	 *
	 * @param WP_Term $term     Current taxonomy term object.
	 * @param string  $taxonomy Current taxonomy slug.
	 */
	public function edit_form_fields( $term, $taxonomy ) {
		$taxonomy = get_taxonomy( $taxonomy );
		?>
		<tr class="form-field term-title-override-wrap">
			<th scope="row">
				<label for="title-override">[ys]一覧ページのタイトルを置き換える</label>
			</th>
			<td>
				<input name="title-override" id="title-override" type="text" value="<?php echo esc_attr( get_term_meta( $term->term_id, 'title-override', true ) ); ?>" size="40">
				<p><?php echo $taxonomy->label; ?>一覧ページのタイトルを入力した内容に置き換えます。</p>
			</td>
		</tr>
		<tr class="form-field term-dscr-override-wrap">
			<th scope="row">
				<label for="dscr-override">[ys]一覧ページの説明を置き換える</label>
			</th>
			<td>
				<?php
				wp_dropdown_pages(
					array(
						'name'              => 'dscr-override',
						'echo'              => true,
						'show_option_none'  => __( '&mdash; Select &mdash;' ),
						'option_none_value' => '0',
						'selected'          => get_term_meta( $term->term_id, 'dscr-override', true ),
						'post_type'         => 'ys-parts',
						'post_status'       => 'publish,private',
					)
				)
				?>
				<p><?php echo $taxonomy->label; ?>の説明を選択した[ys]パーツの内容に置き換えます。</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * 入力された値の保存・削除
	 *
	 * @param int $term_id Term ID.
	 */
	function update_term_meta( $term_id ) {
		/**
		 * タイトルの上書き
		 */
		if ( isset( $_POST['title-override'] ) && ! empty( $_POST['title-override'] ) ) {
			update_term_meta( $term_id, 'title-override', esc_attr( $_POST['title-override'] ) );
		} else {
			delete_term_meta( $term_id, 'title-override' );
		}
		/**
		 * 説明の上書き
		 */
		if ( isset( $_POST['dscr-override'] ) && ! empty( $_POST['dscr-override'] ) ) {
			update_term_meta( $term_id, 'dscr-override', esc_attr( $_POST['dscr-override'] ) );
		} else {
			delete_term_meta( $term_id, 'dscr-override' );
		}
	}

	/**
	 * 一覧タイトルの上書き
	 *
	 * @param string $title タイトル.
	 *
	 * @return string
	 */
	public function override_title( $title ) {
		if ( is_tax() || is_tag() || is_category() ) {
			$term = get_queried_object();
			if ( $term ) {
				$override = get_term_meta( $term->term_id, 'title-override', true );
				$title    = ! empty( $override ) ? $override : $title;
			}
		}

		return $title;
	}

	/**
	 * 説明文の上書き
	 *
	 * @param string $description 説明文.
	 *
	 * @return string
	 */
	public function override_description( $description ) {
		if ( is_tax() || is_tag() || is_category() ) {
			$term = get_queried_object();
			if ( $term ) {
				$page_id = get_term_meta( $term->term_id, 'dscr-override', true );
				/**
				 * 書き換え設定があれば説明書き換え
				 */
				if ( is_numeric( $page_id ) && 0 !== (int) $page_id ) {
					$post = get_post( $page_id );
					if ( $post ) {
						$content     = apply_filters( 'the_content', $post->post_content );
						$content     = str_replace( ']]>', ']]&gt;', $content );
						$description = sprintf(
							'<div class="entry-content entry__content">%s</div>',
							$content
						);
					}
				}
			}
		}

		return $description;
	}

}

new YS_Taxonomy();
