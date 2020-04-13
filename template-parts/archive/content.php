<?php
/**
 * 記事一覧テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<main id="main" class="archive__main site-main">
	<?php do_action( 'ys_site_main_prepend' ); ?>
	<?php
	/**
	 * アーカイブヘッダーの読み込み
	 */
	ys_get_template_part( 'template-parts/archive/header' );
	?>
	<div class="archive__container is-<?php echo ys_get_archive_type(); ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			do_action( 'ys_archive_before_content' );
			ys_get_template_part(
				'template-parts/archive/details',
				ys_get_archive_type()
			);
			do_action( 'ys_archive_after_content' );
		endwhile;
		?>
	</div>
	<?php
	/**
	 * ページネーション
	 */
	ys_get_template_part( 'template-parts/parts/pagination' );
	?>
	<?php do_action( 'ys_site_main_append' ); ?>
</main>
