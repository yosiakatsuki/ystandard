<?php
/**
 * ブログカード フォーマットHTML
 * 
 * URLのみの行の自動変換と[ysblogcard url=""]のショートコードで展開されるHTMLフォーマットです
 * {~~}部分が以下の内容に書き換えられて出力されます
 * 
 * {url} :
 *     リンク先URLに置換されます
 * {target} :
 *     外部サイトの場合 target="_blank"に置換されます
 * {thumbnail} : 
 *     自サイトのリンクの場合アイキャッチ画像 <figure class="ys-blog-card__thumb">[画像]</figure> で置換されます。
 *     外部サイトの場合は空白になります
 * {title} :
 *     ページのタイトルで置換されます
 * {dscr} :
 *     <div class="ys-blog-card__dscr">[抜粋]</div>で置換されます
 *     自サイトの場合投稿抜粋で置換されます
 *     外部サイトの場合 meta descriptionで置換されます（取得できた場合）
 * {domain} :
 *     サイトのドメイン文字列に置換されます。
 *     自サイトの場合サイトアイコンも表示します（設定されている場合）
 */
?>
<div class="ys-blog-card color__font-main">
	<a class="ys-blog-card__link" href="{url}"{target}>
		<div class="ys-blog-card__main clearfix">
			{thumbnail}
			<div class="ys-blog-card__text">
				<div class="ys-blog-card__title">{title}</div>
				{dscr}
			</div>
		</div>
		<div class="ys-blog-card__domain color__font-sub">{domain}</div>
	</a>
</div>