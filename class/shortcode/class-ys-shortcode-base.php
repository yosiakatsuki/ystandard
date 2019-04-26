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
	 * ラッパーHTML
	 *
	 * @var string
	 */
	protected $wrap_html = '<div%s>%s</div>';

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
		$this->wrap_html = $format;
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
				if ( 'bool' === $type ) {
					if ( 1 === $result || '1' === $result || true === $result || 'true' === $result ) {
						return true;
					} else {
						return false;
					}
				}
				/**
				 * 時間で取得
				 */
				if ( 'date' === $type ) {
					$format = YS_Shortcode_Base::get_date_format();
					$date   = Datetime::createFromFormat( $format, $result );
					if ( '' !== $result && false === $date ) {
						$this->set_error_message( '日付(' . $key . ')の指定にエラーがあります' );
					}

					return $result;
				}
			}

			return $result;
		}

		return '';
	}

	/**
	 * HTMLの作成
	 *
	 * @param string $content コンテンツとなるHTML.
	 *
	 * @return string
	 */
	public function get_html( $content ) {
		if ( is_null( $content ) ) {
			return '';
		}
		if ( empty( $content ) ) {
			return '';
		}
		$html_attr = '';
		/**
		 * HTML属性 id,classの作成
		 */
		if ( '' !== $this->get_param( 'id' ) ) {
			$html_attr .= sprintf( ' id="%s"', esc_attr( $this->get_param( 'id' ) ) );
		}
		if ( '' !== $this->get_param( 'class' ) ) {
			$html_attr .= sprintf( ' class="%s"', esc_attr( $this->get_param( 'class' ) ) );
		}
		/**
		 * フォーマット判断
		 */
		if ( ! $this->_is_active_display_html_type() ) {
			return '';
		}

		/**
		 * 日付判断
		 */
		if ( ! $this->_is_active_period() ) {
			return '';
		}

		/**
		 * HTML作成
		 */
		return sprintf(
			$this->wrap_html . $this->get_error_message(),
			$html_attr,
			$content
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
}
