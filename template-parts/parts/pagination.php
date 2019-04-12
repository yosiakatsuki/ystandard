<?php
/**
 * ページネーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ページネーション作成に必要なデータは下記ファイル内で作成しています。
 * inc/pagination/pagination.php
 */
$pagination = ys_get_pagination();
if ( empty( $pagination ) ) {
	return;
}
?>
<nav class="pagination flex flex--row -no-gutter -all">
	<?php foreach ( $pagination as $item ) : ?>
		<?php if ( $item['url'] ) : ?>
			<a class="<?php echo $item['class']; ?> flex__col--auto flex flex--c-c" href="<?php echo $item['url']; ?>"><?php echo $item['text']; ?></a>
		<?php else : ?>
			<span class="<?php echo $item['class']; ?> flex flex--c-c"><?php echo $item['text']; ?></span>
		<?php endif; ?>
	<?php endforeach; ?>
</nav>