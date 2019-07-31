<?php
/**
 * 固定ページ・投稿詳細ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<footer class="entry-footer singular__footer <?php ys_the_singular_class( 'footer' ); ?>">
	<?php
	/**
	 * フッターパーツ呼び出し
	 * template-parts/singular/footer-parts 参照
	 */
	ys_get_singular_footer_parts();
	?>
</footer><!-- .entry__footer -->