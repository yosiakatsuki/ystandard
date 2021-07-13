<?php
/**
 * もろもろ読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * Class YS_Loader
 */
class YS_Loader {

	/**
	 * 関数定義したファイルの読み込み
	 */
	const LOAD_FUNCTION_FILES = [
		__DIR__ . '/compatibility/compatibility.php',
		__DIR__ . '/template/template-function.php',
	];

	/**
	 * 処理しないディレクトリ
	 */
	const EXCLUDE_DIR = [
		'..',
		'.',
	];

	/**
	 * ディレクトリリスト
	 *
	 * @var array
	 */
	private $dir_list = [];

	/**
	 * YS_Loader constructor.
	 */
	public function __construct() {
		$this->dir_list = array_diff( scandir( __DIR__ ), self::EXCLUDE_DIR );
		spl_autoload_register( [ $this, 'autoload_register' ] );
		$this->register_function();
		$this->register_classes();
	}

	/**
	 * ファイル読み込み
	 *
	 * @param string $path File path.
	 */
	public static function require_file( $path ) {
		if ( in_array( $path, get_included_files(), true ) ) {
			return;
		}
		require_once $path;
	}

	/**
	 * Autoload
	 *
	 * @param string $class Class Name.
	 *
	 * @return bool
	 */
	public function autoload_register( $class ) {
		if ( false === strpos( $class, 'ystandard' ) ) {
			return false;
		}
		/**
		 * ファイル名を作成
		 */
		$class = str_replace( '_', '-', ltrim( $class, '\\' ) );
		$file  = 'class-' . str_replace( 'ystandard\\', '', $class ) . '.php';
		if ( function_exists( 'mb_strtolower' ) ) {
			$file = mb_strtolower( $file );
		} else {
			$file = strtolower( $file );
		}

		foreach ( $this->dir_list as $dir ) {
			$target = __DIR__ . DIRECTORY_SEPARATOR . $dir;
			if ( ! is_dir( $target ) ) {
				continue;
			}
			$path = $target . DIRECTORY_SEPARATOR . $file;
			if ( is_file( $path ) && is_readable( $path ) ) {
				$this->require_file( $path );

				return true;
			}
		}

		return false;
	}

	/**
	 * 関数ファイル読み込み
	 */
	private function register_function() {
		foreach ( self::LOAD_FUNCTION_FILES as $path ) {
			if ( is_file( $path ) && is_readable( $path ) ) {
				$this->require_file( $path );
			}
		}
	}

	/**
	 * クラスファイルのロード
	 */
	private function register_classes() {
		foreach ( $this->dir_list as $dir ) {
			$target = __DIR__ . DIRECTORY_SEPARATOR . $dir;

			if ( ! is_dir( $target ) ) {
				continue;
			}
			$file_list = array_diff( scandir( $target ), [ '..', '.' ] );
			foreach ( $file_list as $file ) {

				if ( false === strpos( $file, 'class-' ) ) {
					continue;
				}
				$path = $target . DIRECTORY_SEPARATOR . $file;
				if ( is_file( $path ) && is_readable( $path ) ) {
					$this->require_file( $path );
				}
			}
		}
	}
}

new YS_Loader();
