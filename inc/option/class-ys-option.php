<?php
/**
 * 設定 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Option
 */
class YS_Option {

	/**
	 * 設定取得
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 * @param mixed  $type    取得する型.
	 *
	 * @return mixed
	 */
	public static function get_option( $name, $default, $type = false ) {

		$result = get_option( $name, self::get_default( $name, $default ) );

		/**
		 * 指定のタイプで取得
		 */
		if ( false !== $type ) {
			switch ( $type ) {
				case 'bool':
				case 'boolean':
					$result = ys_to_bool( $result );
					break;
				case 'int':
					$result = intval( $result );
			}
		}

		return apply_filters( "ys_get_option_${name}", $result, $name );
	}

	/**
	 * デフォルト値書き換え
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_default( $name, $default = false ) {
		return apply_filters( "ys_get_option_default_${name}", $default, $name );
	}

	/**
	 * 設定取得(bool)
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_option_by_bool( $name, $default = false ) {
		return self::get_option( $name, $default, 'bool' );
	}

	/**
	 * 設定取得(int)
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_option_by_int( $name, $default = 0 ) {
		return self::get_option( $name, $default, 'int' );
	}


	/**
	 * 設定リストの作成・取得とキャッシュ作成
	 *
	 * @return array
	 * @deprecated
	 */
	public static function create_cache() {
		$options  = array();
		$defaults = self::get_defaults();
		/**
		 * 設定一覧の作成
		 */
		foreach ( $defaults as $key => $value ) {
			$options[] = self::get_option( $key, $value );
		}
		/**
		 * キャッシュの作成
		 */
		if ( 'none' !== get_option( 'ys_query_cache_ys_options', 'none' ) ) {
			$expiration = (int) get_option( 'ys_query_cache_ys_options', 0 );
			$options    = YS_Cache::set_cache(
				'ystandard_options',
				$options,
				array(),
				$expiration
			);
		}

		return $options;
	}

	/**
	 * 設定の変更処理
	 *
	 * @param string $old_key     旧設定.
	 * @param mixed  $old_default 旧設定の初期値.
	 * @param string $new_key     新設定.
	 * @param mixed  $new_default 新設定の初期値.
	 */
	private function change_option_key( $old_key, $old_default, $new_key, $new_default ) {
		if ( get_option( $new_key, $new_default ) === $new_default ) {
			if ( get_option( $old_key, $old_default ) !== $old_default ) {
				update_option(
					$new_key,
					get_option( $old_key, $new_default )
				);
				delete_option( $old_key );
			}
		}
	}

	/**
	 * 設定デフォルト値取得
	 *
	 * @param string $name    設定名.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 *
	 * @deprecated
	 */
	public static function get_default_option( $name, $default ) {
		$defaults = self::get_defaults();
		/**
		 * 結果作成
		 */
		$result = $default;
		if ( isset( $defaults[ $name ] ) ) {
			$result = $defaults[ $name ];
		}

		return apply_filters( "ys_get_option_default_${name}", $result, $name, $defaults );
	}

	/**
	 * 設定初期値取得
	 *
	 * @return array
	 * @deprecated
	 */
	public static function get_defaults() {

		return ys_get_option_defaults();
	}

	/**
	 * オプション値をキャッシュから取得
	 *
	 * @param string $name 設定キー.
	 *
	 * @return string|null
	 * @deprecated
	 */
	public static function get_cache_options( $name ) {
		if ( is_customize_preview() || is_preview() ) {
			return null;
		}
		if ( 'none' === get_option( 'ys_query_cache_ys_options', 'none' ) ) {
			return null;
		}
		$result = null;
		/**
		 * 設定値のキャッシュ機能
		 */
		global $ystandard_option;

		/**
		 * グローバルにセットされてない場合はキャッシュから取得 or リスト作成
		 */
		if ( ! is_array( $ystandard_option ) ) {
			$ystandard_option = YS_Cache::get_cache( 'ystandard_options', array() );
			if ( false === $ystandard_option ) {
				$ystandard_option = self::create_cache();
			}
		}
		/**
		 * 設定チェック
		 */
		if ( isset( $ystandard_option[ $name ] ) ) {
			$result = $ystandard_option[ $name ];
		}

		return $result;
	}

	/**
	 * 設定キャッシュの再作成
	 *
	 * @deprecated
	 */
	public function cache_refresh() {
		if ( 'none' !== get_option( 'ys_query_cache_ys_options', 'none' ) ) {
			ys_get_options_and_create_cache();
		} else {
			YS_Cache::delete_cache( 'ystandard_options', array() );
		}
	}

	/**
	 * 削除された設定
	 *
	 * @return array
	 */
	private function deleted_options() {
		return array(
			'ys_front_page_type'        => '3.13.0',
			'ys_ogp_fb_admins'          => '3.13.0',
			'ys_subscribe_col_sp'       => '3.13.0',
			'ys_subscribe_col_pc'       => '3.13.0',
			'ys_query_cache_ys_options' => '3.13.0',
			'ys_enqueue_gutenberg_css'  => '3.13.0',
		);
	}
}
