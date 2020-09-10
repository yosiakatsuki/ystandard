<?php
/**
 * 管理画面 - post meta
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Admin_Post_Meta
 *
 * @package ystandard
 */
class Post_Meta {

	/**
	 * Nonce Action.
	 */
	const NONCE_ACTION = 'ystandard_post_meta';
	/**
	 * Nonce Name.
	 */
	const NONCE_NAME = 'ystandard_post_meta_nonce';

	/**
	 * Admin_Post_Meta constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_post_meta_seo' ] );
		add_action( 'save_post', [ $this, 'save_post_meta_sns' ] );
		add_action( 'save_post', [ $this, 'save_post_meta_post' ] );
	}

	/**
	 * メタボックスの追加
	 */
	public function add_meta_box() {
		add_meta_box(
			'ys_post_option',
			'[ys] 投稿設定',
			[ $this, 'add_post_option' ],
			[ 'post', 'page' ],
			'side'
		);
		add_meta_box(
			'ys_seo_option',
			'[ys] SEO設定',
			[ $this, 'add_seo_option' ],
			[ 'post', 'page' ],
			'side'
		);
		add_meta_box(
			'ys_sns_option',
			'[ys] SNS設定',
			[ $this, 'add_sns_option' ],
			[ 'post', 'page' ],
			'side'
		);
	}

	/**
	 * SEO設定HTML
	 *
	 * @param \WP_Post $post The object for the current post/page.
	 */
	public function add_seo_option( $post ) {
		$this->nonce_field( 'seo' );
		$post_id = $post->ID;
		?>
		<div class="meta-box__section">
			<div class="meta-box__list">
				<label for="ys_noindex">
					<input type="checkbox" id="ys_noindex" name="ys_noindex" value="1" <?php $this->checked( 'ys_noindex', $post_id ); ?> />この記事をnoindexにする
				</label>
			</div>
			<div class="meta-box__list">
				<label for="ys_hide_meta_dscr">
					<input type="checkbox" id="ys_hide_meta_dscr" name="ys_hide_meta_dscr" value="1" <?php $this->checked( 'ys_hide_meta_dscr', $post_id ); ?> />meta descriptionタグを<strong>無効化</strong>する
				</label>
			</div>
			<?php do_action( 'ys_meta_box_seo' ); ?>
		</div>
		<?php
	}

	/**
	 * SNSオプションHTML
	 *
	 * @param \WP_Post $post The object for the current post/page.
	 */
	public function add_sns_option( $post ) {
		$this->nonce_field( 'sns' );
		$post_id = $post->ID;
		?>
		<div class="meta-box__section">
			<div class="meta-box__list">
				<label class="meta-box__label" for="ys_ogp_title">OGP/Twitter Cards用タイトル</label>
				<input id="ys_ogp_title" type="text" class="meta-box__text" name="ys_ogp_title" value="<?php echo esc_attr( Content::get_post_meta( 'ys_ogp_title', $post_id ) ); ?>"/>
				<div class="meta-box__dscr">※OGP/Twitter Cardsのタイトルとして出力する文章を設定できます。空白の場合投稿タイトルになります。</div>
			</div>
			<div class="meta-box__list">
				<label class="meta-box__label" for="ys_ogp_description">OGP/Twitter Cards用description</label>
				<textarea id="ys_ogp_description" class="meta-box__textarea" name="ys_ogp_description" rows="4" cols="40"><?php echo esc_textarea( Content::get_post_meta( 'ys_ogp_description', $post_id ) ); ?></textarea>
				<div class="meta-box__dscr">※OGP/Twitter Cardsのdescriptionとして出力する文章を設定できます。空白の場合、投稿本文から自動でdescriptionを作成します。</div>
			</div>
			<?php do_action( 'ys_meta_box_sns' ); ?>
		</div>
		<?php
	}

	/**
	 * 投稿オプションHTML
	 *
	 * @param \WP_Post $post The object for the current post/page.
	 */
	public function add_post_option( $post ) {
		$this->nonce_field( 'post' );
		$post_id = $post->ID;
		?>
		<div class="meta-box__section">
			<div class="meta-box__list">
				<label for="ys_hide_ad">
					<input type="checkbox" id="ys_hide_ad" name="ys_hide_ad" value="1" <?php $this->checked( 'ys_hide_ad', $post_id ); ?> />広告を<strong>非表示</strong>にする
				</label>
			</div>
			<div class="meta-box__list">
				<label for="ys_hide_toc">
					<input type="checkbox" id="ys_hide_toc" name="ys_hide_toc" value="1" <?php $this->checked( 'ys_hide_toc', $post_id ); ?> />目次を<strong>非表示</strong>にする
				</label>
			</div>
			<div class="meta-box__list">
				<label for="ys_hide_share">
					<input type="checkbox" id="ys_hide_share" name="ys_hide_share" value="1" <?php $this->checked( 'ys_hide_share', $post_id ); ?> />シェアボタンを<strong>非表示</strong>にする
				</label>
			</div>
			<div class="meta-box__list">
				<label for="ys_hide_publish_date">
					<input type="checkbox" id="ys_hide_publish_date" name="ys_hide_publish_date" value="1" <?php $this->checked( 'ys_hide_publish_date', $post_id ); ?> />投稿日・更新日を<strong>非表示</strong>にする
				</label>
			</div>
			<div class="meta-box__list">
				<label for="ys_hide_author">
					<input type="checkbox" id="ys_hide_author" name="ys_hide_author" value="1" <?php $this->checked( 'ys_hide_author', $post_id ); ?> />著者情報を<strong>非表示</strong>にする
				</label>
			</div>
		</div>
		<?php if ( Admin::is_post_type_on_admin( 'post' ) ) : ?>
			<div class="meta-box__section">
				<h3 class="meta-box__title">投稿オプション</h3>
				<div class="meta-box__dscr">※投稿ページ用設定</div>
				<div class="meta-box__list">
					<label for="ys_hide_related">
						<input type="checkbox" id="ys_hide_related" name="ys_hide_related" value="1" <?php $this->checked( 'ys_hide_related', $post_id ); ?> />関連記事を<strong>非表示</strong>にする
					</label>
				</div>
				<div class="meta-box__list">
					<label for="ys_hide_paging">
						<input type="checkbox" id="ys_hide_paging" name="ys_hide_paging" value="1" <?php $this->checked( 'ys_hide_paging', $post_id ); ?> />前の記事・次の記事を<strong>非表示</strong>にする
					</label>
				</div>
			</div>
		<?php endif; ?>
		<?php
		do_action( 'ys_meta_box_post' );
	}

	/**
	 * Post meta保存
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post_meta_seo( $post_id ) {

		if ( ! $this->verify_save_post_meta( $post_id, 'seo' ) ) {
			return;
		}
		/**
		 * Noindex設定
		 */
		self::save_post_checkbox( $post_id, 'ys_noindex' );
		/**
		 * Meta description設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_meta_dscr' );

		do_action( 'ys_save_post_meta_seo', $post_id );
	}

	/**
	 * Post meta保存
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post_meta_sns( $post_id ) {

		if ( ! $this->verify_save_post_meta( $post_id, 'sns' ) ) {
			return;
		}
		self::save_post_text( $post_id, 'ys_ogp_title' );
		/**
		 * OGP用description
		 */
		self::save_post_textarea( $post_id, 'ys_ogp_description' );

		do_action( 'ys_save_post_meta_sns', $post_id );
	}

	/**
	 * Post meta保存
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post_meta_post( $post_id ) {

		if ( ! $this->verify_save_post_meta( $post_id, 'post' ) ) {
			return;
		}
		/**
		 * 広告非表示設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_ad' );
		/**
		 * 目次非表示設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_toc' );
		/**
		 * シェアボタン非表示設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_share' );
		/**
		 * 投稿日・更新日非表示設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_publish_date' );
		/**
		 * 投稿者非表示設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_author' );
		/**
		 * 関連記事非表示設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_related' );
		/**
		 * 前の記事・次の記事非表示設定
		 */
		self::save_post_checkbox( $post_id, 'ys_hide_paging' );

		do_action( 'ys_save_post_meta_post', $post_id );
	}

	/**
	 * Nonce Field
	 *
	 * @param string $type タイプ.
	 */
	private function nonce_field( $type ) {
		$action = self::NONCE_ACTION . '_' . $type;
		$nonce  = self::NONCE_NAME . '_' . $type;
		wp_nonce_field( $action, $nonce );
	}

	/**
	 * Post meta保存 チェック
	 *
	 * @param int    $post_id The ID of the post being saved.
	 * @param string $type    Type.
	 *
	 * @return bool
	 */
	private function verify_save_post_meta( $post_id, $type ) {
		/**
		 * Nonceチェック.
		 */
		if ( ! Admin::verify_nonce( self::NONCE_NAME . '_' . $type, self::NONCE_ACTION . '_' . $type ) ) {
			return false;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}
		/**
		 * ユーザー権限の確認
		 */
		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return false;
			}
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * 投稿オプションの更新：チェックボックス
	 *
	 * @param int    $post_id 投稿ID.
	 * @param string $key     設定キー.
	 */
	public static function save_post_checkbox( $post_id, $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, $_POST[ $key ] );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	/**
	 * 投稿オプションの更新：textarea
	 *
	 * @param int    $post_id 投稿ID.
	 * @param string $key     設定キー.
	 */
	public static function save_post_text( $post_id, $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			return;
		}
		if ( ! empty( $_POST[ $key ] ) ) {
			$text = esc_attr( $_POST[ $key ] );
			update_post_meta( $post_id, $key, $text );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	/**
	 * 投稿オプションの更新：textarea
	 *
	 * @param int    $post_id       投稿ID.
	 * @param string $key           設定キー.
	 * @param bool   $remove_breaks 改行を削除するか.
	 */
	public static function save_post_textarea( $post_id, $key, $remove_breaks = true ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			return;
		}
		if ( ! empty( $_POST[ $key ] ) ) {
			$text = wp_strip_all_tags( $_POST[ $key ], $remove_breaks );
			update_post_meta( $post_id, $key, $text );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	/**
	 * チェックボックスのチェック判定
	 *
	 * @param string $key     Meta key.
	 * @param int    $post_id Post ID.
	 */
	private function checked( $key, $post_id ) {
		checked(
			Content::get_post_meta( $key, $post_id ),
			'1',
			true
		);
	}
}

new Post_Meta();
