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
	 * Nonce Action.
	 */
	const NONCE_ACTION = 'ys_add_term_meta';

	/**
	 * Nonce Name.
	 */
	const NONCE_NAME = 'ys_add_term_nonce';

	/**
	 * YS_Taxonomy constructor.
	 */
	public function __construct() {
		$this->add_term_meta_options();
		add_filter( 'ys_get_the_archive_title', array( $this, 'override_title' ) );
		add_filter( 'document_title_parts', array( $this, 'document_title_parts' ) );
		add_filter( 'get_the_archive_description', array( $this, 'override_description' ) );
		add_filter( 'ys_get_ogp_and_twitter_card_param', array( $this, 'change_ogp' ) );
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
		wp_nonce_field(
			self::NONCE_ACTION,
			self::NONCE_NAME
		);

		?>
		<div class="form-field term-title-override-wrap">
			<label for="title-override">[ys]一覧ページのタイトルを置き換える</label>
			<input name="title-override" id="title-override" type="text" value="" size="40">
			<p><?php echo $taxonomy->label; ?>一覧ページのタイトルを入力した内容に置き換えます。</p>
		</div>
		<div class="form-field term-dscr-override-wrap">
			<label for="dscr-override">[ys]一覧ページの説明を置き換える</label>
			<?php
			$html = wp_dropdown_pages(
				array(
					'name'              => 'dscr-override',
					'echo'              => false,
					'show_option_none'  => __( '&mdash; Select &mdash;' ),
					'option_none_value' => '0',
					'selected'          => '0',
					'post_type'         => 'ys-parts',
					'post_status'       => 'publish',
				)
			);
			if ( $html ) {
				echo $html;
			} else {
				echo '<p style="padding: 1em 0;">※[ys]パーツがまだ1件も作成されていません。</p>';
			}
			?>
			<p><?php echo $taxonomy->label; ?>の説明を選択した<a href="<?php echo esc_url_raw( admin_url( 'edit.php?post_type=ys-parts' ) ); ?>">[ys]パーツ</a>の内容に置き換えます。</p>
		</div>
		<div class="form-field term-image-wrap" style="margin-bottom: 2em;">
			<label for="term-image" style="margin-bottom: 0.5em;">[ys]<?php echo $taxonomy->label; ?>一覧ページのOGP画像</label>
			<div id="ys_admin_show_custom_avatar_preview" class="ys-custom-image-upload-preview">
				画像が選択されてません。
			</div>
			<input type="text" id="term-image" name="term-image" class="ys-custom-image-upload-url" value="" hidden/>
			<button class="button action ys-custom-image-upload" type="button" data-uploaderpreview-width="500">画像をアップロード
			</button>
			<button class="button action ys-custom-image-clear" type="button" style="display: none;">画像を削除
			</button>
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
		wp_nonce_field(
			self::NONCE_ACTION,
			self::NONCE_NAME
		);
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
				$html = wp_dropdown_pages(
					array(
						'name'              => 'dscr-override',
						'echo'              => false,
						'show_option_none'  => __( '&mdash; Select &mdash;' ),
						'option_none_value' => '0',
						'selected'          => get_term_meta( $term->term_id, 'dscr-override', true ),
						'post_type'         => 'ys-parts',
						'post_status'       => 'publish',
					)
				);
				if ( $html ) {
					echo $html;
				} else {
					echo '<p style="padding: 0 0 1em;">※[ys]パーツがまだ1件も作成されていません。</p>';
				}
				?>
				<p><?php echo $taxonomy->label; ?>の説明を選択した<a href="<?php echo esc_url_raw( admin_url( 'edit.php?post_type=ys-parts' ) ); ?>">[ys]パーツ</a>の内容に置き換えます。</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="term-image">[ys]<?php echo $taxonomy->label; ?>一覧ページのOGP画像</label>
			</th>
			<td>
				<div class="form-field term-image-wrap">
					<div id="ys_admin_show_custom_avatar_preview" class="ys-custom-image-upload-preview">
						<?php
						$image = get_term_meta( $term->term_id, 'term-image', true );
						if ( '' !== $image ) {
							echo "<img style=\"width:800px;height:auto;max-width: 100%;\" src=\"${image}\" />";
						} else {
							echo '画像が選択されてません。';
						}
						?>
					</div>
					<input type="text" id="term-image" name="term-image" class="ys-custom-image-upload-url" value="<?php echo esc_url_raw( $image ); ?>" hidden/>
					<button class="button action ys-custom-image-upload" type="button" data-uploaderpreview-width="500" <?php echo ! empty( $image ) ? 'style="display: none;"' : ''; ?>>画像をアップロード
					</button>
					<button class="button action ys-custom-image-clear" type="button" <?php echo empty( $image ) ? 'style="display: none;"' : ''; ?>>画像を削除
					</button>
				</div>
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
		// nonceがセットされているかどうか確認.
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) ) {
			return;
		}

		// nonceが正しいかどうか検証.
		if ( ! wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::NONCE_ACTION ) ) {
			return;
		}
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
		/**
		 * OGP画像
		 */
		if ( isset( $_POST['term-image'] ) && ! empty( $_POST['term-image'] ) ) {
			update_term_meta( $term_id, 'term-image', esc_url_raw( $_POST['term-image'] ) );
		} else {
			delete_term_meta( $term_id, 'term-image' );
		}
	}

	/**
	 * タクソノミー・タグ・カテゴリー一覧かどうか
	 *
	 * @return bool
	 */
	private function is_tax_cat_tag() {
		return ( is_tax() || is_tag() || is_category() );
	}

	/**
	 * カテゴリー・タグの情報を取得
	 *
	 * @return array
	 */
	private function get_term_content() {
		$term     = get_queried_object();
		$parts_id = get_term_meta( $term->term_id, 'dscr-override', true );
		$dscr     = '';
		if ( ! is_numeric( $parts_id ) ) {
			$parts_id = 0;
		} else {
			$dscr = ys_get_the_custom_excerpt( '', 0, $parts_id );
		}

		return array(
			'parts_id' => (int) $parts_id,
			'title'    => get_term_meta( $term->term_id, 'title-override', true ),
			'dscr'     => $dscr,
			'image'    => get_term_meta( $term->term_id, 'term-image', true ),
		);
	}

	/**
	 * 一覧タイトルの上書き
	 *
	 * @param string $title タイトル.
	 *
	 * @return string
	 */
	public function override_title( $title ) {
		if ( $this->is_tax_cat_tag() ) {
			$content = $this->get_term_content();
			if ( $content['title'] ) {
				$override = $content['title'];
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
		if ( $this->is_tax_cat_tag() ) {
			$content = $this->get_term_content();
			if ( 0 < $content['parts_id'] ) {
				/**
				 * 書き換え設定があれば説明書き換え
				 */
				$description = do_shortcode( '[ys_parts use_entry_content="1" parts_id="' . $content['parts_id'] . '"]' );
			}
		}

		return $description;
	}

	/**
	 * OGP書き換え
	 *
	 * @param array $param OGPパラメーター.
	 *
	 * @return array
	 */
	public function change_ogp( $param ) {
		if ( $this->is_tax_cat_tag() ) {
			$content = $this->get_term_content();
			if ( $content['image'] ) {
				$param['image'] = $content['image'];
			}
			if ( $content['title'] ) {
				$param['title'] = $content['title'];
			}
			if ( $content['dscr'] ) {
				$param['description'] = $content['dscr'];
			}
		}

		return $param;
	}

	/**
	 * タイトル書き換え
	 *
	 * @param array $title タイトル.
	 *
	 * @return array
	 */
	public function document_title_parts( $title ) {
		if ( $this->is_tax_cat_tag() ) {
			$term       = get_queried_object();
			$term_title = get_term_meta( $term->term_id, 'title-override', true );
			if ( $term_title ) {
				$title['title'] = $term_title;
			}
		}

		return $title;
	}

}

new YS_Taxonomy();
