<?php
/**
 * 管理画面 - post meta
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

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
		add_action( 'init', [ $this, 'register_post_meta' ], 20 );
		add_action( 'admin_menu', [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_post_meta_seo' ] );
		add_action( 'save_post', [ $this, 'save_post_meta_sns' ] );
		add_action( 'save_post', [ $this, 'save_post_meta_post' ] );
	}

	/**
	 * メタボックスの追加
	 */
	public function add_meta_box() {
		$types = $this->get_meta_box_post_types();
		foreach ( $types as $type ) {
			$callback_args = [];
			if ( self::is_block_editor_post_type( $type ) ) {
				$callback_args['__back_compat_meta_box'] = true;
			}
			add_meta_box(
				'ys_post_option',
				'[ys] 投稿設定',
				[ $this, 'add_post_option' ],
				$type,
				'side',
				'default',
				$callback_args
			);
			add_meta_box(
				'ys_seo_option',
				'[ys] SEO設定',
				[ $this, 'add_seo_option' ],
				$type,
				'side',
				'default',
				$callback_args
			);
			add_meta_box(
				'ys_sns_option',
				'[ys] SNS設定',
				[ $this, 'add_sns_option' ],
				$type,
				'side',
				'default',
				$callback_args
			);
		}
	}

	/**
	 * Meta Boxを追加する投稿タイプを取得
	 *
	 * @return array
	 */
	public function get_meta_box_post_types() {
		$types = Utility::get_post_types( [], [ 'ys-parts' ] );

		return array_values(
			array_unique(
				apply_filters(
					'ys_get_meta_box_post_types',
					array_keys( $types )
				)
			)
		);

	}

	/**
	 * 投稿メタ定義を取得.
	 *
	 * @return array
	 */
	public static function get_meta_fields() {
		$fields = [
			'ys_hide_ad'           => [
				'type'        => 'boolean',
				'panel'       => 'post',
				'label'       => __( '広告を非表示にする', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_hide_toc'          => [
				'type'        => 'boolean',
				'panel'       => 'post',
				'label'       => __( '目次を非表示にする', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_hide_share'        => [
				'type'        => 'boolean',
				'panel'       => 'post',
				'label'       => __( 'シェアボタンを非表示にする', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_hide_publish_date' => [
				'type'        => 'boolean',
				'panel'       => 'post',
				'label'       => __( '投稿日・更新日を非表示にする', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_hide_author'       => [
				'type'        => 'boolean',
				'panel'       => 'post',
				'label'       => __( '著者情報を非表示にする', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_hide_related'      => [
				'type'        => 'boolean',
				'panel'       => 'post',
				'label'       => __( '関連記事を非表示にする', 'ystandard' ),
				'post_types'  => [ 'post' ],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_hide_paging'       => [
				'type'        => 'boolean',
				'panel'       => 'post',
				'label'       => __( '前の記事・次の記事を非表示にする', 'ystandard' ),
				'post_types'  => [ 'post' ],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_noindex'           => [
				'type'        => 'boolean',
				'panel'       => 'seo',
				'label'       => __( 'この記事をnoindexにする', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_hide_meta_dscr'    => [
				'type'        => 'boolean',
				'panel'       => 'seo',
				'label'       => __( 'meta descriptionタグを無効化する', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_boolean' ],
			],
			'ys_ogp_title'         => [
				'type'        => 'string',
				'control'     => 'text',
				'panel'       => 'sns',
				'label'       => __( 'OGP/Twitter Cards用タイトル', 'ystandard' ),
				'help'        => __( '空白の場合は投稿タイトルになります。', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => 'sanitize_text_field',
			],
			'ys_ogp_description'   => [
				'type'        => 'string',
				'control'     => 'textarea',
				'panel'       => 'sns',
				'label'       => __( 'OGP/Twitter Cards用description', 'ystandard' ),
				'help'        => __( '空白の場合は投稿本文からdescriptionを自動生成します。', 'ystandard' ),
				'post_types'  => [],
				'sanitize_cb' => [ __CLASS__, 'sanitize_ogp_description' ],
			],
		];

		return apply_filters( 'ys_block_editor_post_meta_fields', $fields );
	}

	/**
	 * ブロックエディターUIを使用する投稿タイプを取得.
	 *
	 * @return array
	 */
	public function get_block_editor_post_types() {
		$types = [];
		foreach ( $this->get_meta_box_post_types() as $post_type ) {
			if ( self::is_block_editor_post_type( $post_type ) ) {
				$types[] = $post_type;
			}
		}

		return apply_filters( 'ys_block_editor_post_meta_post_types', $types );
	}

	/**
	 * ブロックエディターUIを使用できる投稿タイプか判定.
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return bool
	 */
	public static function is_block_editor_post_type( $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		if ( ! $post_type_object || ! $post_type_object->show_in_rest ) {
			return false;
		}
		if ( ! use_block_editor_for_post_type( $post_type ) ) {
			return false;
		}

		return post_type_supports( $post_type, 'custom-fields' );
	}

	/**
	 * 投稿メタをREST APIへ登録.
	 */
	public function register_post_meta() {
		foreach ( [ 'post', 'page' ] as $post_type ) {
			if ( ! post_type_supports( $post_type, 'custom-fields' ) ) {
				add_post_type_support( $post_type, 'custom-fields' );
			}
		}

		foreach ( $this->get_block_editor_post_types() as $post_type ) {
			foreach ( self::get_meta_fields() as $key => $field ) {
				if ( ! self::is_field_available_for_post_type( $field, $post_type ) ) {
					continue;
				}
				register_post_meta(
					$post_type,
					$key,
					[
						'type'              => $field['type'],
						'single'            => true,
						'default'           => 'boolean' === $field['type'] ? false : '',
						'show_in_rest'      => true,
						'sanitize_callback' => $field['sanitize_cb'],
						'auth_callback'     => [ __CLASS__, 'can_edit_post_meta' ],
					]
				);
			}
			add_action( "rest_after_insert_{$post_type}", [ $this, 'do_rest_save_actions' ], 10, 3 );
		}
	}

	/**
	 * フィールドが投稿タイプで利用できるか判定.
	 *
	 * @param array  $field     フィールド定義.
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return bool
	 */
	public static function is_field_available_for_post_type( $field, $post_type ) {
		return empty( $field['post_types'] ) || in_array( $post_type, $field['post_types'], true );
	}

	/**
	 * REST APIから投稿メタを編集できるか判定.
	 *
	 * @param bool   $allowed   許可状態.
	 * @param string $meta_key  メタキー.
	 * @param int    $object_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function can_edit_post_meta( $allowed, $meta_key, $object_id ) {
		return current_user_can( 'edit_post', $object_id );
	}

	/**
	 * 真偽値をサニタイズ.
	 *
	 * @param mixed $value 入力値.
	 *
	 * @return bool
	 */
	public static function sanitize_boolean( $value ) {
		return rest_sanitize_boolean( $value );
	}

	/**
	 * OGP descriptionをサニタイズ.
	 *
	 * @param mixed $value 入力値.
	 *
	 * @return string
	 */
	public static function sanitize_ogp_description( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * REST API保存後に既存の保存アクションを実行.
	 *
	 * @param \WP_Post         $post     投稿オブジェクト.
	 * @param \WP_REST_Request $request  RESTリクエスト.
	 * @param bool             $creating 新規作成か.
	 */
	public function do_rest_save_actions( $post, $request, $creating ) {
		do_action( 'ys_save_post_meta_seo', $post->ID );
		do_action( 'ys_save_post_meta_sns', $post->ID );
		do_action( 'ys_save_post_meta_post', $post->ID );
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
			<?php do_action( 'ys_meta_box_seo', $post_id ); ?>
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
			<?php do_action( 'ys_meta_box_sns', $post_id ); ?>
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
		do_action( 'ys_meta_box_post', $post_id );
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
			$value = self::sanitize_boolean( wp_unslash( $_POST[ $key ] ) );
			if ( $value ) {
				update_post_meta( $post_id, $key, '1' );
			} else {
				delete_post_meta( $post_id, $key );
			}
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
			$text = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
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
			$value = wp_unslash( $_POST[ $key ] );
			$text  = $remove_breaks ? self::sanitize_ogp_description( $value ) : sanitize_textarea_field( $value );
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
