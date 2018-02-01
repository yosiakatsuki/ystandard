<?php
	/**
	 * 読み込むテンプレートをまとめた配列を取得
	 */
	$templates = ys_get_entry_footer_template();
	foreach ( $templates as $template ): ?>
		<div class="entry__footer-block">
			<?php get_template_part( $template ); ?>
		</div><!-- .entry__footer-block -->
	<?php endforeach; ?>