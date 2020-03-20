<?php
/**
 * CSS,JavaScript読み込み関連のためのクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * CSS,JavaScript読み込み関連のためのクラス
 */
class YS_Scripts {
	/**
	 * 読み込むCSS
	 *
	 * @var array
	 */
	private $enqueue_styles = array();
	/**
	 * インラインCSS
	 *
	 * @var array
	 */
	private $inline_styles = array();
	/**
	 * Onload-script
	 *
	 * @var array
	 */
	private $onload_script = array();
	/**
	 * Lazyload-script
	 *
	 * @var array
	 */
	private $lazyload_script = array();

	/**
	 * インラインCSS
	 *
	 * @var string
	 */
	private $inline_css = '';

	/**
	 * 初期化処理
	 */
	public function init() {

		/**
		 * 管理画面外のみjQuery操作
		 */
		if ( ! is_admin() && ! ys_is_login_page() && ! is_customize_preview() ) {
			/**
			 * [jQuery]削除
			 */
			if ( ys_is_disable_jquery() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'disable_jquery' ) );
			}
			/**
			 * [jQuery]のフッター読み込み
			 */
			if ( ys_is_deregister_jquery() ) {
				add_action( 'init', array( $this, 'jquery_in_footer' ) );
			}
		}

	}




	/**
	 * [jQuery]の削除
	 */
	public function disable_jquery() {
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );
		wp_dequeue_script( 'jquery' );
		wp_dequeue_script( 'jquery-core' );
	}

	/**
	 * [jQuery]をフッターに移動
	 */
	public function jquery_in_footer() {
		global $wp_scripts;
		$ver = ys_get_theme_version();
		$src = '';
		/**
		 * 必要があればwp_scriptsを初期化
		 */
		wp_scripts();
		if ( null !== $wp_scripts ) {
			$jquery = $wp_scripts->registered['jquery-core'];
			$ver    = $jquery->ver;
			$src    = $jquery->src;
		}
		/**
		 * CDN経由の場合
		 */
		if ( ys_is_load_cdn_jquery() ) {
			$src = ys_get_option( 'ys_load_cdn_jquery_url', '' );
			$ver = null;
		}
		if ( '' === $src ) {
			return;
		}
		/**
		 * [jQuery削除]
		 */
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );
		/**
		 * フッターで読み込むか
		 */
		$in_footer = ys_is_load_jquery_in_footer();
		/**
		 * [jQueryをフッターに移動]
		 */
		wp_register_script(
			'jquery-core',
			$src,
			array(),
			$ver,
			$in_footer
		);
		wp_register_script(
			'jquery',
			false,
			array( 'jquery-core' ),
			$ver,
			$in_footer
		);
	}

}
