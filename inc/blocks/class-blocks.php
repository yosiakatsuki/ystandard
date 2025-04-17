<?php
/**
 * ブロック関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Path;

defined( 'ABSPATH' ) || die();

/**
 * Blocks
 */
class Blocks {

	/**
	 * Admin Styles.
	 *
	 * @var array
	 */
	private $enqueue_block_editor_styles;
	/**
	 * Styles.
	 *
	 * @var array
	 */
	private $enqueue_styles;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->enqueue_block_editor_styles = [];
		$this->enqueue_styles              = [];
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_theme_block_styles' ] );
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_styles' ], 100 );
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_editor_styles' ], 100 );
	}

	/**
	 * Enqueue Block Styles
	 *
	 * @return void
	 */
	public function enqueue_theme_block_styles() {
		$this->enqueue_block_styles( get_template_directory() . '/css/block-styles' );
		// フックで追加.
		$style_dir_path = apply_filters( 'ys_block_styles_dir_path', [] );
		// 子テーマも検索.
		if ( is_child_theme() ) {
			$style_dir_path[] = get_stylesheet_directory() . '/css/block-styles';
		}
		if ( is_array( $style_dir_path ) && ! empty( $style_dir_path ) ) {
			$style_dir_path = array_values( array_unique( $style_dir_path ) );
			foreach ( $style_dir_path as $path ) {
				$this->enqueue_block_styles( $path );
			}
		}
	}

	/**
	 * ブロックスタイル読み込み.
	 *
	 * @param string $dir CSS配置ディレクトリ.
	 *
	 * @return void
	 */
	private function enqueue_block_styles( $dir ) {
		foreach ( glob( "{$dir}/*", GLOB_ONLYDIR ) as $dir_path ) {
			$block        = $this->parse_block_name( basename( $dir_path ) );
			$block_name   = $block['namespace'] . '/' . $block['name'];
			$style_handle = "ystd-$block_name";
			$is_child     = false !== strpos( $dir_path, get_stylesheet_directory() );
			if ( $is_child ) {
				$style_handle .= '-child';
			}
			$theme_css_path = $dir_path . '/' . $block['name'] . '.css';
			if ( file_exists( $theme_css_path ) ) {
				// URL.
				$style_src              = $this->replace_path_to_uri( $theme_css_path, $is_child );
				$this->enqueue_styles[] = [
					'handle' => $style_handle,
					'src'    => $style_src,
					'path'   => $theme_css_path,
				];
			}

			// 必ず読み込むスタイル.
			$required = apply_filters( 'ys_required_block_style_names', [] );
			if ( is_array( $required ) ) {
				foreach ( $required as $item ) {
					if ( false !== strpos( $block['name'], $item ) ) {
						$this->enqueue_styles[] = [
							'handle' => $style_handle,
							'src'    => $style_src,
							'path'   => $theme_css_path,
						];
					}
				}
			}

			// エディター用.
			$editor_css_path = $dir_path . '/' . $block['name'] . '-editor.css';
			if ( is_admin() ) {
				if ( file_exists( $editor_css_path ) ) {
					$editor_src = $this->replace_path_to_uri( $editor_css_path, $is_child );
					// 読み込むCSS登録.
					$this->enqueue_block_editor_styles[] = [
						'handle' => "{$style_handle}-editor",
						'src'    => $editor_src,
						'path'   => $editor_css_path,
					];
				}
			}
		}
	}

	/**
	 * ブロックCSS
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ! is_array( $this->enqueue_styles ) ) {
			return;
		}
		foreach ( $this->enqueue_styles as $style ) {
			$this->enqueue_style( $style['handle'], $style['src'], $style['path'] );
		}
		$this->enqueue_styles = [];
	}

	/**
	 * ブロックエディター用CSS
	 *
	 * @return void
	 */
	public function enqueue_block_editor_styles() {
		if ( ! is_array( $this->enqueue_block_editor_styles ) || ! is_admin() ) {
			return;
		}
		foreach ( $this->enqueue_block_editor_styles as $style ) {
			$this->enqueue_style( $style['handle'], $style['src'], $style['path'] );
		}
		$this->enqueue_block_editor_styles = [];
	}

	/**
	 * Enqueue CSS
	 *
	 * @param string $handle Handle.
	 * @param string $src    CSS Uri.
	 * @param string $path   CSS Path.
	 *
	 * @return void
	 */
	private function enqueue_style( $handle, $src, $path ) {
		wp_enqueue_style(
			$handle,
			$src,
			[],
			filemtime( $path )
		);
		wp_style_add_data( $handle, 'path', $path );
	}

	/**
	 * フォルダ名からブロック名を作成.
	 *
	 * @param string $name ディレクトリ名.
	 *
	 * @return array
	 */
	private function parse_block_name( $name ) {
		$block_name = explode( '__', $name );
		$namespace  = 'core';
		if ( isset( $block_name[1] ) && ! empty( $block_name[1] ) ) {
			$namespace     = $block_name[0];
			$block_name[0] = '';
		}
		$block_name = implode( '__', array_filter( $block_name ) );

		return [
			'namespace' => $namespace,
			'name'      => $block_name,
		];
	}

	/**
	 * テーマパスをテーマURLに変換
	 *
	 * @param string  $path     Path.
	 * @param boolean $is_child 子テーマかどうか.
	 *
	 * @return string
	 */
	private function replace_path_to_uri( $path, $is_child = false ) {
		return Path::replace_template_path_to_uri( $path, $is_child );
	}
}

new Blocks();
