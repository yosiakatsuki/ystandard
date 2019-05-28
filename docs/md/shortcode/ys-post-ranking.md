# ショートコード:ys_post_ranking

記事ランキング作成 ショートコード

## パラメーター

### class_ul

`ul`に付くクラス

初期値：空文字

### class_li

`li`に付くクラス

初期値：空文字

### class_link

`a`に付くクラス

初期値：空文字

### list_type

記事リストの表示タイプ

* list : 画像とタイトルが横に表示されるタイプ。リストタイプ
* card : 画像とタイトルが縦に表示されるタイプ。カードタイプ
* slide : 記事リストが横スライドできるタイプ

初期値:horizon

### col

列数指定
1~6の間で指定する

初期値:1

### col_sp

スマートフォンでの列数指定
指定がない場合 `col` の値が使われる
指定がある場合は `col` より優先される

初期値：空文字

### col_tablet

タブレットでの列数指定
指定がない場合 `col` の値が使われる
指定がある場合は `col` より優先される

初期値：空文字

### col_pc

PCでの列数指定
指定がない場合 `col` の値が使われる
指定がある場合は `col` より優先される

初期値：空文字

### count

取得する記事数

初期値：5

### order

記事の並び順
ASC,DESCで指定する

初期値：DESC

### show_img

サムネイル画像の表示
true,false で指定する

初期値：true

### show_excerpt

抜粋の表示
true,false で指定する
合わせて `excerpt_length` の指定が必要になる

初期値：false

### excerpt_length

抜粋の文字数
`excerpt_length = true` のときに有効になる

初期値：50

### thumbnail_size

サムネイルのサイズ
`thumbnail`, `medium`, `large`, `full` など、メディア有効にされているサイズ名を指定する

`list` タイプの場合は `thumbnail`、
`card`, `slide` タイプの場合は `medium` 以上、横長フォーマットの画像サイズ指定がおすすめ

初期値：thumbnail

### thumbnail_ratio

サムネイル表示の縦横比
`4-3`, `16-9`, `3-1`, `2-1`, `1-1` から指定する

指定がない場合は `thumbnail_size` の指定によって自動で決定される

* `thumbnail_size = thumbnail` : `1-1`
* `thumbnail_size = thumbnail`以外 : `16-9`

初期値：空文字

### ranking_type

ランキング作成用パラメーター
`all`, `d`, `w`, `m` で指定する

指定が合った場合、ランキング作成モードで各パラメーターが自動調整される

初期値:`all`

### filter

フィルターオプション
主にランキング作成時に使用される
`filter = category`を指定することにより、投稿詳細とカテゴリー一覧でランキングが同一カテゴリー内の記事で作成される

初期値:`category`


## 旧パラメーターの互換性

v2までの指定との互換性情報
各パラメーターは以下のように変換される

旧パラメーターを使用中はログインユーザーにのみ警告が表示される

### mode

`list_type` に変換される

また、パラメーターも以下のように変換される

* horizon : `list` に変換
* vertical : `card` に変換

### period

yStandard記事ランキング作成での期間指定
`ranking_type` に変換される

### post_count

`count` に変換される

### cols

`col` に変換される

### class_list

`class_ul` に変換される

### class_item

`class_li` に変換される

## 廃止パラメーター

* `template`
