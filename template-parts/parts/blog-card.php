<?php
/**
 * ブログカード フォーマットHTML
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ブログカードの表示は基本的にショートコードで処理しています。
 * YS_Shortcode_Blog_Cardクラス内でデータを作成し、このファイルをテンプレートとしてHTMLを生成します。
 * (class/shortcode/class-ys-shortcode-blog-card.php)
 */
global $ys_blog_card_data;
if ( empty( $ys_blog_card_data ) ) {
	return;
}
/**
 * URL
 */
$url = $ys_blog_card_data['url'];
/**
 * タイトル
 */
$title = $ys_blog_card_data['title'];
/**
 * 概要
 */
$dscr = $ys_blog_card_data['dscr'];
/**
 * 画像
 */
$thumbnail = $ys_blog_card_data['thumbnail'];
/**
 * 属性
 */
$attr = $ys_blog_card_data['attr'];
/**
 * ボタンテキスト
 */
$btn_text = $ys_blog_card_data['btn_text'];
/**
 * ドメイン（外部サイトのみ）
 */
$domain = $ys_blog_card_data['domain'];
/**
 * ボタン部分のラッパークラス
 */
$btn_wrap_class = 'text--right';
if ( $domain ) {
	$btn_wrap_class = 'flex flex--j-between flex--a-center';
}
?>
<a class="ys-blog-card__link" href="<?php echo $url; ?>" <?php echo $attr; ?>>
	<div class="ys-blog-card__row flex flex--row flex--nowrap -no-gutter">
		<?php if ( $thumbnail ) : ?>
			<figure class="ys-blog-card__image flex__col--auto">
				<?php echo $thumbnail; ?>
			</figure>
		<?php endif; ?>
		<div class="ys-blog-card__text flex__col">
			<div class="ys-blog-card__title"><?php echo $title; ?></div>
			<?php if ( $dscr ) : ?>
				<div class="ys-blog-card__dscr text-sub has-small-font-size"><?php echo $dscr; ?></div>
			<?php endif; ?>
			<div class="ys-blog-card__btn-wrap <?php echo $btn_wrap_class; ?>">
				<?php if ( $domain ) : ?>
					<span class="ys-blog-card__domain text-sub has-x-small-font-size"><?php echo $domain; ?></span>
				<?php endif; ?>
				<span class="ys-blog-card__btn"><?php echo $btn_text; ?><i class="fas fa-angle-right"></i></span>
			</div>
		</div>
	</div>
</a>
