<?php
/**
 * シェアボタン テンプレート : 公式ボタン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( empty( $share_button ) ) {
	return;
}
$url          = $share_button['official']['url'];
$title        = $share_button['official']['title'];
$twitter_attr = '';
if ( $share_button['official']['twitter-via'] ) {
	$twitter_attr .= ' data-via="' . $share_button['official']['twitter-via'] . '"';
}
if ( $share_button['official']['twitter-related'] ) {
	$twitter_attr .= ' data-related="' . $share_button['official']['twitter-related'] . '"';
}
?>
<div class="sns-share is-official">
	<?php if ( isset( $share_button['text']['before'] ) && $share_button['text']['before'] ) : ?>
		<p class="sns-share__before"><?php echo esc_html( $share_button['text']['before'] ); ?></p>
	<?php endif; ?>
	<ul class="sns-share__container">
		<?php if ( isset( $share_button['sns']['twitter'] ) ) : ?>
			<li class="sns-share__button is-twitter">
				<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-text="<?php echo esc_attr( $title ); ?>" data-url="<?php echo esc_url_raw( $url ); ?>"<?php echo $twitter_attr; ?> data-show-count="false">Tweet</a>
			</li>
		<?php endif; ?>
		<?php if ( isset( $share_button['sns']['facebook'] ) ) : ?>
			<li class="sns-share__button is-facebook">
				<div class="fb-like" data-href="<?php echo esc_url_raw( $url ); ?>" data-width="" data-layout="button" data-action="like" data-size="small"></div>
			</li>
		<?php endif; ?>
		<?php if ( isset( $share_button['sns']['hatenabookmark'] ) ) : ?>
			<li class="sns-share__button is-hatenabookmark">
				<a href="https://b.hatena.ne.jp/entry/" class="hatena-bookmark-button" data-hatena-bookmark-layout="basic-label-counter" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="https://b.st-hatena.com/images/v4/public/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a>
			</li>
		<?php endif; ?>
		<?php if ( isset( $share_button['sns']['pocket'] ) ) : ?>
			<li class="sns-share__button is-pocket">
				<a data-pocket-label="pocket" data-pocket-count="none" class="pocket-btn" data-lang="en"></a>
			</li>
		<?php endif; ?>
		<?php if ( isset( $share_button['sns']['line'] ) ) : ?>
			<li class="sns-share__button is-line">
				<div class="line-it-button" data-lang="ja" data-type="share-a" data-ver="3" data-url="<?php echo esc_url_raw( $url ); ?>" data-color="default" data-size="small" data-count="false" style="display: none;"></div>
			</li>
		<?php endif; ?>
	</ul>
	<?php if ( isset( $share_button['text']['after'] ) && $share_button['text']['after'] ) : ?>
		<p class="sns-share__after"><?php echo esc_html( $share_button['text']['after'] ); ?></p>
	<?php endif; ?>
</div>


