<?php
/**
 * パンくずリストテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( is_front_page() || is_page_template( 'page-template/template-one-column-no-title.php' ) ) {
	return;
}
$items = ys_get_breadcrumbs();
?>
<div id="breadcrumbs" class="breadcrumbs">
	<div class="container">
		<ol class="breadcrumbs__list list-style--none color__font-sub" itemscope itemtype="http://schema.org/BreadcrumbList">
			<?php foreach ( $items as $key => $item ) : ?>
				<li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<?php if ( empty( $item['link'] ) ) : ?>
						<span itemscope itemtype="http://schema.org/Thing" itemprop="item">
							<span itemprop="name"><?php echo esc_html( $item['title'] ); ?></span>
						</span>
					<?php else : ?>
						<a class="color__font-sub" itemscope itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo esc_url( $item['link'] ); ?>">
							<span itemprop="name"><?php echo esc_html( $item['title'] ); ?></span>
						</a>
					<?php endif; ?>
					<meta itemprop="position" content="<?php echo esc_attr( $key + 1 ); ?>" />
				</li>
			<?php endforeach; ?>
		</ol>
	</div><!-- .container -->
</div><!-- #breadcrumbs.breadcrumbs -->