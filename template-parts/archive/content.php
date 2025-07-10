<?php
/**
 * 記事一覧テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();
?>
<main id="main" class="archive__main site-main">
	<?php
	// main 開始直後フック.
	do_action( 'ys_site_main_prepend' );
	// アーカイブヘッダーの読み込み.
	get_template_part( 'template-parts/archive/header' );
	?>
	<div class="archive__container is-<?php echo esc_attr( ys_get_archive_type() ); ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			do_action( 'ys_archive_before_content' );
			// 一覧読み込み.
			ys_get_template_part(
				'template-parts/archive/details',
				ys_get_archive_type()
			);
			do_action( 'ys_archive_after_content' );
		endwhile;
		?>
	</div>
	<?php
	// ページネーション.
	get_template_part( 'template-parts/parts/pagination' );
	// main 終了前フック.
	do_action( 'ys_site_main_append' );
	?>
</main>
