<?php
/**
 * ショートコード基本クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * キャッシュ処理クラス
 */
class YS_Shortcode_Base {

	/**
	 * タクソノミーとタームの区切り文字
	 *
	 * @var string
	 */
	const TAX_DELIMITER = '__';
	/**
	 * タクソノミーとタームのリストの区切り文字
	 *
	 * @var string
	 */
	const TAX_LIST_DELIMITER = ' ';

	/**
	 * 基本パラメーター
	 *
	 * @var array
	 */
	protected $attr_base = array();

	/**
	 * ショートコードパラメーター
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * エラー
	 *
	 * @var  array
	 */
	protected $err = array();

	/**
	 * コンストラクタ
	 *
	 * @param array $args ユーザー指定パラメーター.
	 * @param array $attr 追加パラメーター.
	 */
	public function __construct( $args = array(), $attr = array() ) {
		/**
		 * 基本パラメーターの作成
		 */
		$this->set_base_attr( $attr );
		/**
		 * ショートコード用パラメータ-作成
		 */
		$this->args = shortcode_atts(
			$this->attr_base,
			$args
		);
	}

	/**
	 * ショートコード基本パラメーターの取得
	 *
	 * @return array
	 */
	static public function get_base_attr() {
		return array(
			'id'                 => '',
			'class'              => '',
			'class_base'         => '',
			'display_start_date' => '',
			'enable_end_date'    => '',
			'display_end_date'   => '',
			'display_tax_list'   => '',
			'display_tax'        => '',
			'display_tax_term'   => '',
			'display_category'   => '',
			'display_tag'        => '',
			'display_pc'         => true,
			'display_mobile'     => true,
			'display_amp'        => true,
			'title'              => '',
			'title_tag'          => 'h2',
			'title_class'        => '',
			'wrap_html'          => '<div%s>%s</div>',
		);
	}


	/**
	 * 日付フォーマットの取得
	 *
	 * @return string
	 */
	static public function get_date_format() {
		return 'Y/m/d H:i:00';
	}

	/**
	 * パラメーター取得
	 *
	 * @return array
	 */
	public function get_args() {
		return $this->args;
	}

	/**
	 * 基本パラメーターの作成
	 *
	 * @param array $attr 追加パラメーター.
	 */
	public function set_base_attr( $attr = array() ) {
		/**
		 * マージ
		 */
		$this->attr_base = array_merge( YS_Shortcode_Base::get_base_attr(), $attr );
	}

	/**
	 * ラッパーHTMLのセット
	 *
	 * @param string $format HTMLフォーマット.
	 */
	public function set_wrap_html( $format ) {
		$this->args['wrap_html'] = $format;
	}

	/**
	 * クラス追加
	 *
	 * @param string $class Class.
	 */
	public function add_class( $class ) {
		if ( '' !== $this->args['class'] ) {
			$this->args['class'] = $this->args['class'] . ' ' . $class;
		} else {
			$this->args['class'] = $class;
		}
	}

	/**
	 * タイトル設定
	 *
	 * @param string $title タイトル.
	 */
	public function set_title( $title ) {
		$this->args['title'] = $title;
	}

	/**
	 * エラーメッセージのセット
	 *
	 * @param string $message エラーメッセージ.
	 */
	protected function set_error_message( $message ) {
		$this->err[] = $message;
	}

	/**
	 * エラー表示
	 *
	 * @return string
	 */
	protected function get_error_message() {
		if ( ! empty( $this->err ) && is_user_logged_in() ) {
			/**
			 * 編集権限がある人のみエラー内容表示
			 */
			if ( current_user_can( 'edit_posts' ) ) {
				return sprintf(
					'<div style="color:#C22929;font-size:0.7rem">%s</div>',
					implode( '<br>', $this->err )
				);

			}
		}

		return '';
	}


	/**
	 * パラメーター取得
	 *
	 * @param string $key  キー.
	 * @param string $type 取得タイプ(bool,date).
	 *
	 * @return mixed
	 */
	public function get_param( $key, $type = '' ) {
		if ( isset( $this->args[ $key ] ) ) {
			$result = $this->args[ $key ];

			if ( '' !== $type ) {
				/**
				 * 真・偽で取得
				 */
				if ( 'bool' === $type || 'boolean' === $type ) {
					return ys_to_bool( $result );
				}
				/**
				 * 時間で取得
				 */
				if ( 'date' === $type ) {
					$format = YS_Shortcode_Base::get_date_format();
					$date   = Datetime::createFromFormat( $format, $result );
					if ( '' !== $result && false === $date ) {
						$this->set_error_message(
							'日付「' . $key . '」の指定にエラーがあります。 ' . $format . ' の形式で設定してください。'
						);
					}

					return $result;
				}
				/**
				 * 数値で取得
				 */
				if ( 'int' === $type || 'integer' === $type ) {
					if ( ! is_numeric( $result ) ) {
						return 0;
					}

					return (int) $result;
				}
			}

			return $result;
		}

		return '';
	}

	/**
	 * パラメーターのセット
	 *
	 * @param string $key   key.
	 * @param string $value value.
	 */
	public function set_param( $key, $value ) {
		$this->args[ $key ] = $value;
	}

	/**
	 * HTMLの作成
	 *
	 * @param string $content コンテンツとなるHTML.
	 *
	 * @return string
	 */
	public function get_html( $content ) {
		if ( null === $content ) {
			return '';
		}
		if ( empty( $content ) ) {
			return '';
		}
		$html_attr = '';
		/**
		 * HTML属性 idの作成
		 */
		if ( '' !== $this->get_param( 'id' ) ) {
			$html_attr .= sprintf( ' id="%s"', esc_attr( $this->get_param( 'id' ) ) );
		}
		/**
		 * HTML属性 classの作成
		 */
		$class = '';
		if ( '' !== $this->get_param( 'class_base' ) ) {
			$class .= esc_attr( $this->get_param( 'class_base' ) );
		}
		if ( '' !== $this->get_param( 'class' ) ) {
			if ( '' !== $class ) {
				$class .= ' ';
			}
			$class .= esc_attr( $this->get_param( 'class' ) );
		}
		if ( '' !== $class ) {
			$html_attr .= sprintf( ' class="%s"', $class );
		}

		/**
		 * 表示ディスプレイタイプ判断
		 */
		if ( ! $this->_is_active_display_html_type() ) {
			return '';
		}
		/**
		 * タクソノミー判断
		 */
		if ( ! $this->_is_active_display_term() ) {
			return '';
		}
		/**
		 * 日付判断
		 */
		if ( ! $this->_is_active_period() ) {
			return '';
		}
		/**
		 * タイトル作成
		 */
		$title = $this->get_param( 'title' );
		if ( '' !== $title ) {
			/**
			 * タイトル追加
			 */
			$tag         = $this->get_param( 'title_tag' );
			$title_class = $this->get_param( 'title_class' );
			if ( '' !== $title_class ) {
				$title_class = ' class="' . $title_class . '"';
			}
			$title   = sprintf(
				'<%s%s>%s</%s>',
				$tag,
				$title_class,
				$title,
				$tag
			);
			$content = $title . $content;
		}
		/**
		 * HTML作成
		 */
		$result = sprintf(
			$this->args['wrap_html'],
			$html_attr,
			$content
		);

		return $result . $this->get_error_message();
	}

	/**
	 * 保存用選択値の作成
	 *
	 * @param string $taxonomy タクソノミー.
	 * @param string $term     ターム.
	 *
	 * @return string
	 */
	public static function join_tax_term( $taxonomy, $term ) {
		return esc_attr( $taxonomy . self::TAX_DELIMITER . $term );
	}

	/**
	 * タクソノミーとタームを分割する
	 *
	 * @param string $value タクソノミーとタームの結合文字.
	 *
	 * @return array|null
	 */
	public static function split_tax_term( $value ) {
		$selected = explode( self::TAX_DELIMITER, $value );
		if ( ! is_array( $selected ) ) {
			return null;
		}
		$term_label = '';
		$term_slug  = '';
		/**
		 * 最後の要素をtermとして取り出す
		 */
		$term_id = array_pop( $selected );
		/**
		 * 残りをつなげてタクソノミーとする
		 */
		$taxonomy = implode( self::TAX_DELIMITER, $selected );
		$term     = get_term( $term_id, $taxonomy );
		if ( ! is_wp_error( $term ) && null !== $term ) {
			$term_label = $term->name;
			$term_slug  = $term->slug;
		}

		return array(
			'taxonomy_name' => $taxonomy,
			'term_id'       => $term_id,
			'term_label'    => $term_label,
			'term_slug'     => $term_slug,
		);
	}

	/**
	 * 日付の判断
	 *
	 * @return bool
	 */
	protected function _is_active_period() {
		/**
		 * パラメータ取得＆判断
		 */
		return YS_Shortcode_Base::is_active_period(
			$this->get_param( 'display_start_date', 'date' ),
			$this->get_param( 'display_end_date', 'date' ),
			$this->get_param( 'enable_end_date', 'bool' )
		);
	}

	/**
	 * 日付の判断
	 *
	 * @param string $start_date 開始日時.
	 * @param string $end_date   終了日時.
	 * @param bool   $end_flg    終了時刻使用フラグ.
	 * @param string $format     時刻フォーマット.
	 *
	 * @return bool
	 */
	public static function is_active_period( $start_date, $end_date, $end_flg, $format = '' ) {
		/**
		 * 指定が無ければtrue
		 */
		if ( empty( $start_date ) && empty( $end_date ) ) {
			return true;
		}
		/**
		 * 日時作成
		 */
		if ( empty( $format ) ) {
			$format = YS_Shortcode_Base::get_date_format();
		}
		$start_date = Datetime::createFromFormat( $format, $start_date );
		$end_date   = Datetime::createFromFormat( $format, $end_date );
		$now_date   = Datetime::createFromFormat( $format, date_i18n( $format ) );
		/**
		 * 開始判断
		 */
		if ( ! empty( $start_date ) && $start_date > $now_date ) {
			/**
			 * 現在時刻が開始時刻より前であればfalse
			 */
			return false;
		}

		/**
		 * 終了判断
		 */
		if ( $end_flg ) {
			if ( ! empty( $end_date ) && $end_date <= $now_date ) {
				/**
				 * 現在時刻が終了時刻以降であればfalse
				 */
				return false;
			}
		}

		return true;
	}


	/**
	 * PC・SP・AMPの判断(クラス内)
	 *
	 * @return bool
	 */
	protected function _is_active_display_html_type() {
		/**
		 * パラメータ取得＆判断
		 */
		return YS_Shortcode_Base::is_active_display_html_type(
			$this->get_param( 'display_pc', 'bool' ),
			$this->get_param( 'display_mobile', 'bool' ),
			$this->get_param( 'display_amp', 'bool' )
		);
	}

	/**
	 * PC・SP・AMPの判断
	 *
	 * @param bool $pc     PC.
	 * @param bool $mobile Mobile.
	 * @param bool $amp    AMP.
	 *
	 * @return bool
	 */
	public static function is_active_display_html_type( $pc, $mobile, $amp ) {
		if ( ys_is_amp() ) {
			if ( ! $amp ) {
				/**
				 * AMPフォーマットでAMP NGの場合はfalse
				 */
				return false;
			}
		} else {
			if ( ys_is_mobile() ) {
				if ( ! $mobile ) {
					/**
					 * 通常フォーマット(SP)でNGの場合はfalse
					 */
					return false;
				}
			} else {
				if ( ! $pc ) {
					/**
					 * 通常フォーマット(PC)でNGの場合はfalse
					 */
					return false;
				}
			}
		}

		return true;
	}


	/**
	 * 表示タクソノミー設定の判断
	 *
	 * @return bool
	 */
	protected function _is_active_display_term() {
		$tax_list = $this->get_tax_list();

		return $this->is_active_display_term( $tax_list );
	}

	/**
	 * 表示タクソノミー設定の判断
	 *
	 * @param string $tax_list  タクソノミー・タームの一覧.
	 * @param string $delimiter リストの区切り文字.
	 *
	 * @return bool
	 */
	public static function is_active_display_term( $tax_list, $delimiter = self::TAX_LIST_DELIMITER ) {
		/**
		 * 設定が無ければ絞り込みなし
		 */
		if ( '' === $tax_list ) {
			return true;
		}

		$result   = false;
		$tax_list = explode( $delimiter, $tax_list );

		/**
		 * タクソノミーアーカイブページの場合
		 */
		if ( is_tax() || is_category() || is_tag() ) {
			$queried_obj = get_queried_object();
			$taxonomy    = $queried_obj->taxonomy;
			$term        = $queried_obj->slug;
			if ( $term ) {
				$tax = self::join_tax_term( $taxonomy, $term );
				if ( ys_in_array( $tax, $tax_list ) ) {
					return true;
				};
			}
		}
		/**
		 * 詳細系ページ
		 */
		if ( is_singular() ) {
			$post_type = ys_get_post_type();
			if ( $post_type ) {
				$taxonomies = get_taxonomies(
					array(
						'object_type' => array( $post_type ),
						'public'      => true,
						'show_ui'     => true,
					),
					'objects'
				);
				/**
				 * 各タクソノミーについて検査
				 */
				foreach ( $taxonomies as $name => $taxonomy ) {
					$terms = get_the_terms( get_the_ID(), $name );
					if ( $terms ) {
						/**
						 * 各タームについて検査
						 */
						foreach ( $terms as $term ) {
							$tax = self::join_tax_term( $name, $term->slug );
							if ( ys_in_array( $tax, $tax_list ) ) {
								return true;
							};
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * タクソノミー指定設定の取得
	 *
	 * @return string
	 */
	public function get_tax_list() {
		$tax_list = '';

		/**
		 * タグが指定されてる
		 */
		if ( '' !== $this->get_param( 'display_tag' ) ) {
			$tax_list = $this->join_tax_term( 'post_tag', $this->get_param( 'display_tag' ) );
		}

		/**
		 * カテゴリーが指定されている
		 */
		if ( '' !== $this->get_param( 'display_category' ) ) {
			$tax_list = $this->join_tax_term( 'category', $this->get_param( 'display_category' ) );
		}
		/**
		 * タクソノミーとタームのセットが指定されている
		 */
		if ( '' !== $this->get_param( 'display_tax' ) && '' !== $this->get_param( 'display_tax_term' ) ) {
			$tax_list = $this->join_tax_term( $this->get_param( 'display_tax' ), $this->get_param( 'display_tax_term' ) );
		}
		/**
		 * リストで指定されている
		 */
		if ( '' !== $this->get_param( 'display_tax_list' ) ) {
			$tax_list = $this->get_param( 'display_tax_list' );
		}

		return $tax_list;
	}

	/**
	 * 表示カラムチェック
	 */
	protected function check_col() {
		/**
		 * グローバル
		 */
		$col = $this->get_param( 'col', 'int' );
		if ( 1 > $col ) {
			$col = 1;
		}
		if ( 6 < $col ) {
			$col = 6;
		}
		/**
		 * スマホ
		 */
		$col_sp = $this->get_param( 'col_sp', 'int' );
		if ( 1 > $col_sp || 6 < $col_sp ) {
			$this->set_param( 'col_sp', $col );
		}
		/**
		 * タブレット
		 */
		$col_tablet = $this->get_param( 'col_tablet', 'int' );
		if ( 1 > $col_tablet || 6 < $col_tablet ) {
			$this->set_param( 'col_tablet', $col );
		}
		/**
		 * PC
		 */
		$col_pc = $this->get_param( 'col_pc', 'int' );
		if ( 1 > $col_pc || 6 < $col_pc ) {
			$this->set_param( 'col_pc', $col );
		}
	}
}
