<?php
/**
 * ページネーション
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

$pagination = ys_get_pagination();
if ( empty( $pagination ) ) {
	return;
}
?>
<nav class="pagination flex flex--j-center">
	<?php
	foreach ( $pagination as $item ) :
		if ( $item['url'] ) :
	?>
		<a class="<?php echo $item['class']; ?> flex flex--c-c" href="<?php echo $item['url']; ?>"><?php echo $item['text']; ?></a>
	<?php else : ?>
			<span class="<?php echo $item['class']; ?> flex flex--c-c"><?php echo $item['text']; ?></span>
	<?php
			endif;
		endforeach;
	?>
</nav>