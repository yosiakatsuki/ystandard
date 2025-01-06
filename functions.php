<?php
/**
 * テーマ内で使用する関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 関数群を読み込み
 */
require_once __DIR__ . '/inc/class-ys-loader.php';


add_filter(
	'ys_get_header_thumbnail',
	function ( $thumbnail ) {


		ob_start();
		?>
		<div style="background-color: #eee;padding: 10% 30%;">
			<h1>
				<?php
				if ( is_category() ) {
					echo 'is_category';
				}
				if ( is_tag() ) {
					echo 'is_tag';
				}
				if ( is_tax() ) {
					echo 'is_tax';
				}
				if ( is_author() ) {
					echo 'is_author';
				}
				if ( is_date() ) {
					echo 'is_date';
				}
				if ( is_home() ) {
					echo 'is_home';
				}
				if ( is_front_page() ) {
					echo 'is_front_page';
				}
				if ( is_page() ) {
					echo 'is_page';
				}
				if ( is_single() ) {
					echo 'is_single';
				}
				?>
			</h1>
		</div>
		<?php
		return ob_get_clean();
	}
);
