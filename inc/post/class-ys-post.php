<?php
/**
 * 投稿関連クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Post
 */
class YS_Post {
	/**
	 * YS_Post constructor.
	 */
	public function __construct() {
		add_filter( 'post_class', array( $this, 'post_class' ) );
		add_action( 'admin_menu', array( $this, 'add_meta_box' ) );
		add_action( 'transition_post_status', array( $this, 'update_post_meta' ), 10, 3 );
	}

	/**
	 * Post Classを操作する
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function post_class( $classes ) {
		/**
		 * [hentryの削除]
		 */
		if ( apply_filters( 'ystd_remove_hentry', true ) ) {
			$classes = array_diff( $classes, array( 'hentry' ) );
		}
		/**
		 * アイキャッチ画像の有無
		 */
		if ( is_singular() ) {
			if ( ys_is_active_post_thumbnail() ) {
				$classes[] = 'has-thumbnail';
			}
		}

		return $classes;
	}

	/**
	 * メタボックスの追加
	 */
	public function add_meta_box() {
		/**
		 * 投稿オプション
		 */
		add_meta_box(
			'ys_add_post_option',
			'yStandard投稿オプション',
			array( $this, 'add_post_option' ),
			array( 'post', 'page' )
		);
	}

	/**
	 * 投稿オプションHTML
	 */
	public function add_post_option() {
		global $post;
		?>
		<h3 class="meta-box__headline">SEO設定</h3>
		<div>
			<label for="ys_noindex">
				<input type="checkbox" id="ys_noindex" name="ys_noindex" value="1" <?php checked( ys_get_post_meta( 'ys_noindex', $post->ID ), '1', true ); ?> />この記事をnoindexにする
			</label><br>
			<label for="ys_hide_meta_dscr">
				<input type="checkbox" id="ys_hide_meta_dscr" name="ys_hide_meta_dscr" value="1" <?php checked( ys_get_post_meta( 'ys_hide_meta_dscr', $post->ID ), '1', true ); ?> />meta descriptionタグを<strong>無効化</strong>する
			</label>
			<div id="ys-ogp-description-section" class="meta-box__section">
				<label class="meta-box__label" for="ys_ogp_description">OGP/Twitter Cards用description</label>
				<textarea id="ys_ogp_description" class="meta-box__full-w" name="ys_ogp_description" rows="4" cols="40"><?php echo ys_get_post_meta( 'ys_ogp_description', $post->ID ); ?></textarea>
				<div class="meta-box__dscr">※OGP/Twitter Cardsのdescriptionとして出力する文章を設定できます。空白の場合、投稿本文から自動でdescriptionを作成します。</div><!-- .meta-box__dscr -->
			</div><!-- #ys-ogp-description-section -->
			<br>
		</div>
		<h3 class="meta-box__headline">投稿オプション</h3>
		<div>
			<label for="ys_hide_ad">
				<input type="checkbox" id="ys_hide_ad" name="ys_hide_ad" value="1" <?php checked( ys_get_post_meta( 'ys_hide_ad', $post->ID ), '1', true ); ?> />広告を<strong>非表示</strong>にする
			</label><br/>
			<label for="ys_hide_share">
				<input type="checkbox" id="ys_hide_share" name="ys_hide_share" value="1" <?php checked( ys_get_post_meta( 'ys_hide_share', $post->ID ), '1', true ); ?> />シェアボタンを<strong>非表示</strong>にする
			</label><br/>
			<label for="ys_hide_follow">
				<input type="checkbox" id="ys_hide_follow" name="ys_hide_follow" value="1" <?php checked( ys_get_post_meta( 'ys_hide_follow', $post->ID ), '1', true ); ?> />フォローボタンを<strong>非表示</strong>にする
			</label><br/>
			<label for="ys_hide_publish_date">
				<input type="checkbox" id="ys_hide_publish_date" name="ys_hide_publish_date" value="1" <?php checked( ys_get_post_meta( 'ys_hide_publish_date', $post->ID ), '1', true ); ?> />投稿日・更新日を<strong>非表示</strong>にする
			</label><br/>
			<label for="ys_hide_author">
				<input type="checkbox" id="ys_hide_author" name="ys_hide_author" value="1" <?php checked( ys_get_post_meta( 'ys_hide_author', $post->ID ), '1', true ); ?> />著者情報を<strong>非表示</strong>にする
			</label>
			<?php if ( ys_is_post_type_on_admin( 'post' ) ) : ?>
				<br/>
				<label for="ys_hide_related">
					<input type="checkbox" id="ys_hide_related" name="ys_hide_related" value="1" <?php checked( ys_get_post_meta( 'ys_hide_related', $post->ID ), '1', true ); ?> />関連記事を<strong>非表示</strong>にする
				</label>
				<br/>
				<label for="ys_hide_paging">
					<input type="checkbox" id="ys_hide_paging" name="ys_hide_paging" value="1" <?php checked( ys_get_post_meta( 'ys_hide_paging', $post->ID ), '1', true ); ?> />前の記事・次の記事を<strong>非表示</strong>にする
				</label>
				<?php if ( ys_get_option( 'ys_amp_enable' ) ) : ?>
					<br/>
					<label for="ys_post_meta_amp_desable">
						<input type="checkbox" id="ys_post_meta_amp_desable" name="ys_post_meta_amp_desable" value="1" <?php checked( ys_get_post_meta( 'ys_post_meta_amp_desable', $post->ID ), '1', true ); ?> />AMPページ生成を<strong>無効化</strong>する
					</label>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * カスタムフィールドの登録指示
	 *
	 * @param string  $new_status 新しい投稿ステータス.
	 * @param string  $old_status 古い投稿ステータス.
	 * @param WP_Post $post       投稿データ.
	 */
	public function update_post_meta( $new_status, $old_status, $post ) {
		/**
		 * 予約済み→公開は何もしない
		 */
		if ( 'future' === $old_status && 'publish' === $new_status ) {
			return;
		}
		/**
		 * クイック編集
		 */
		if ( isset( $_POST['action'] ) && 'inline-save' === $_POST['action'] ) {
			return;
		}
		add_action( 'save_post', array( $this, 'save_post_meta' ) );
	}

	/**
	 * 投稿オプションの保存
	 *
	 * @param int $post_id 投稿ID.
	 */
	public function save_post_meta( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		/**
		 * Noindex設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_noindex' );
		/**
		 * Meta description設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_meta_dscr' );
		/**
		 * OGP用description
		 */
		$this->save_post_textarea( $_POST, $post_id, 'ys_ogp_description' );
		/**
		 * 広告非表示設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_ad' );
		/**
		 * シェアボタン非表示設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_share' );
		/**
		 * フォローボタン非表示設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_follow' );
		/**
		 * 投稿日・更新日非表示設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_publish_date' );
		/**
		 * 投稿者非表示設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_author' );
		/**
		 * 関連記事非表示設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_related' );
		/**
		 * 前の記事・次の記事非表示設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_hide_paging' );
		/**
		 * AMPページ作成設定
		 */
		$this->save_post_checkbox( $_POST, $post_id, 'ys_post_meta_amp_desable' );
	}

	/**
	 * 投稿オプションの更新：チェックボックス
	 *
	 * @param array  $post    POST.
	 * @param int    $post_id 投稿ID.
	 * @param string $key     設定キー.
	 */
	private function save_post_checkbox( $post, $post_id, $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, $post[ $key ] );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	/**
	 * 投稿オプションの更新：textarea
	 *
	 * @param array  $post          POST.
	 * @param int    $post_id       投稿ID.
	 * @param string $key           設定キー.
	 * @param bool   $remove_breaks 改行を削除するか.
	 */
	private function save_post_textarea( $post, $post_id, $key, $remove_breaks = true ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			return;
		}
		if ( ! empty( $post[ $key ] ) ) {
			$text = wp_strip_all_tags( $post[ $key ], $remove_breaks );
			update_post_meta( $post_id, $key, $text );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

}

new YS_Post();
