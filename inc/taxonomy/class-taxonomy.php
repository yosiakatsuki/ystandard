<?php
/**
 * タクソノミー関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Taxonomy
 *
 * @package ystandard
 */
class Taxonomy {

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
		add_action( 'init', [ $this, 'add_term_meta_options' ], PHP_INT_MAX );
		add_filter( 'ys_get_the_archive_title', [ $this, 'override_title' ] );
		add_filter( 'document_title_parts', [ $this, 'document_title_parts' ] );
		add_filter( 'get_the_archive_description', [ $this, 'override_description' ] );
		add_filter( 'ys_ogp_description_archive', [ $this, 'ogp_description' ] );
		add_filter( 'ys_ogp_image', [ $this, 'ogp_image' ] );
		add_filter( 'wp_tag_cloud', [ $this, '_tag_cloud' ] );
		add_action( 'set_singular_content', [ $this, 'set_singular_content' ] );
		add_action( 'ys_after_site_header', [ $this, 'header_post_thumbnail' ] );
	}

	/**
	 * タクソノミー表示セット
	 */
	public function set_singular_content() {
		add_action(
			'ys_singular_footer',
			[ __CLASS__, 'post_taxonomy' ],
			Content::get_footer_priority( 'taxonomy' )
		);
	}

	/**
	 * タグクラウドからスタイル属性削除
	 *
	 * @param string|array $return Tag cloud as a string or an array, depending on 'format' argument.
	 *
	 * @return string|array
	 */
	public function _tag_cloud( $return ) {

		if ( is_array( $return ) ) {
			return $return;
		}

		$return = preg_replace(
			'#style=".+?"#',
			'',
			$return
		);

		return $return;

	}

	/**
	 * カテゴリー・タグの一覧ページ画像表示
	 */
	public function header_post_thumbnail() {
		if ( ! $this->is_tax_cat_tag() ) {
			return;
		}
		$content = $this->get_term_content();
		if ( empty( $content['image'] ) ) {
			return;
		}
		$image = attachment_url_to_postid( $content['image'] );
		if ( ! $image ) {
			return;
		}
		$thumbnail = wp_get_attachment_image(
			$image,
			'post-thumbnail',
			false,
			[
				'id'    => 'site-header-thumbnail__image',
				'class' => 'site-header-thumbnail__image',
				'alt'   => get_the_title(),
			]
		);

		ob_start();
		Template::get_template_part(
			'template-parts/parts/header-thumbnail',
			'',
			[ 'header_thumbnail' => $thumbnail ]
		);
		echo ob_get_clean();
	}

	/**
	 * 投稿ページカテゴリー・タグ表示
	 */
	public static function post_taxonomy() {
		if ( ! self::is_active_post_taxonomy() ) {
			return;
		}

		Template::get_template_part( 'template-parts/parts/post-taxonomy' );
	}

	/**
	 * タクソノミー情報を取得
	 *
	 * @return array | bool
	 */
	public static function get_the_taxonomies_data() {
		$data       = [];
		$taxonomies = get_the_taxonomies();
		if ( ! $taxonomies ) {
			return false;
		}
		foreach ( $taxonomies as $name => $label ) {
			$taxonomy = get_taxonomy( $name );
			$terms    = get_the_terms( false, $name );
			if ( ! is_wp_error( $terms ) || ! $terms ) {
				$data[ $name ] = [
					'label' => $taxonomy ? $taxonomy->label : '',
					'terms' => $terms,
				];
			}
		}
		$post_type = Content::get_post_type();

		return apply_filters( "ys_get_${post_type}_taxonomies", $data );
	}


	/**
	 * 記事一覧表示用ターム情報取得
	 *
	 * @param string $taxonomy タクソノミー.
	 *
	 * @return bool|array
	 */
	public static function get_the_term_data( $taxonomy = false ) {

		if ( false === $taxonomy ) {
			$taxonomy = Utility::get_meta_taxonomy();
		}
		$terms = get_the_terms( false, $taxonomy );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return false;
		}

		return [
			'name' => $terms[0]->name,
			'slug' => $terms[0]->slug,
		];
	}

	/**
	 * 投稿ページカテゴリー・タグ表示 判定
	 *
	 * @return bool;
	 */
	private static function is_active_post_taxonomy() {
		$post_type = Content::get_post_type();

		$filter = apply_filters( "ys_is_active_${post_type}_taxonomy", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$result   = Option::get_option_by_bool( "ys_show_${fallback}_category", true );
		} else {
			$result = $filter;
		}

		return $result;
	}


	/**
	 * タームの拡張設定追加
	 */
	public function add_term_meta_options() {
		/**
		 * 設定追加対象のタクソノミー
		 */
		$taxonomies = get_taxonomies(
			[
				'public'  => true,
				'show_ui' => true,
			]
		);
		if ( $taxonomies ) {
			$taxonomies = array_keys( $taxonomies );
		}
		$taxonomies = apply_filters( 'ys_add_term_meta_options_taxonomies', $taxonomies );

		foreach ( $taxonomies as $tax ) {
			add_action( "${tax}_edit_form_fields", [ $this, 'edit_form_fields' ], 10, 2 );
			add_action( "${tax}_add_form_fields", [ $this, 'add_form_fields' ] );
			add_action( "edit_${tax}", [ $this, 'update_term_meta' ] );
		}

	}

	/**
	 * カテゴリー追加画面に入力欄追加
	 *
	 * @param string $taxonomy Current taxonomy slug.
	 */
	public function add_form_fields( $taxonomy ) {
		$taxonomy = get_taxonomy( $taxonomy );
		$manual   = Admin::manual_link( 'manual_category/taxonomies', '', true );
		?>
		<?php if ( ! empty( $manual ) ) : ?>
			<hr>
			<div class="form-field term-parent-wrap">
				<p style="font-style: normal;">
					<?php echo $taxonomy->label; ?>一覧ページの編集機能について
					<?php echo $manual; ?>
				</p>
			</div>
			<hr>
		<?php endif; ?>
		<?php
	}

	/**
	 * カテゴリー編集画面に入力欄追加
	 *
	 * @param \WP_Term $term     Current taxonomy term object.
	 * @param string   $taxonomy Current taxonomy slug.
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
				<?php echo Admin::manual_link( 'manual/cat-tag-archive-title' ); ?>
			</th>
			<td>
				<input name="title-override" id="title-override" type="text" value="<?php echo esc_attr( get_term_meta( $term->term_id, 'title-override', true ) ); ?>" size="40">
				<p><?php echo $taxonomy->label; ?>一覧ページのタイトルを入力した内容に置き換えます。</p>
			</td>
		</tr>
		<tr class="form-field term-dscr-override-wrap">
			<th scope="row">
				<label for="dscr-override">[ys]一覧ページの説明を置き換える</label>
				<?php echo Admin::manual_link( 'manual/cat-tag-archive-content' ); ?>
			</th>
			<td>
				<?php
				$html = wp_dropdown_pages(
					[
						'name'              => 'dscr-override',
						'echo'              => false,
						'show_option_none'  => __( '&mdash; Select &mdash;' ),
						'option_none_value' => '0',
						'selected'          => get_term_meta( $term->term_id, 'dscr-override', true ),
						'post_type'         => 'ys-parts',
						'post_status'       => 'publish',
					]
				);
				if ( $html ) {
					echo $html;
				} else {
					echo '<p class="term-dscr-no-item">[ys]パーツがありません。</p>';
				}
				?>
				<p><?php echo $taxonomy->label; ?>の説明を選択した<a href="<?php echo esc_url_raw( admin_url( 'edit.php?post_type=ys-parts' ) ); ?>" target="_blank">[ys]パーツ</a>の内容に置き換えます。</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="term-image">[ys]<?php echo $taxonomy->label; ?>一覧ページ画像</label>
				<?php echo Admin::manual_link( 'manual/cat-tag-archive-eye-catch' ); ?>
			</th>
			<td>
				<div class="form-field term-image-wrap">
					<?php
					Admin::custom_uploader_control(
						'term-image',
						get_term_meta( $term->term_id, 'term-image', true )
					);
					?>
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

		if ( ! Admin::verify_nonce( self::NONCE_NAME, self::NONCE_ACTION ) ) {
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
			$dscr = Content::get_custom_excerpt( '', 0, $parts_id );
		}

		return [
			'parts_id' => (int) $parts_id,
			'title'    => get_term_meta( $term->term_id, 'title-override', true ),
			'dscr'     => $dscr,
			'image'    => get_term_meta( $term->term_id, 'term-image', true ),
		];
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
	 * OGP概要文書き換え
	 *
	 * @param string $dscr Archive description.
	 *
	 * @return string
	 */
	public function ogp_description( $dscr ) {
		if ( $this->is_tax_cat_tag() ) {
			$content = $this->get_term_content();
			if ( $content['dscr'] ) {
				$dscr = $content['dscr'];
			}
		}

		return $dscr;
	}

	/**
	 * OGP画像書き換え
	 *
	 * @param string $image Archive image.
	 *
	 * @return string
	 */
	public function ogp_image( $image ) {
		if ( $this->is_tax_cat_tag() ) {
			$content = $this->get_term_content();
			if ( $content['image'] ) {
				$image = $content['image'];
			}
		}

		return $image;
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

new Taxonomy();
