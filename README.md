# yStandard

![yStandard](./screenshot.png "yStandard")

## カスタマイズありきの一風変わったWordPressテーマ「yStandard」

yStandardは「自分色に染めた、自分だけのサイトを作る楽しさ」を感じてもらうために作った一風変わったテーマです

詳しくは公式サイトをご覧ください

[yStandard](https://wp-ystandard.com/)

## 「yStandard」の由来

「標準」といった意味の「Standard」に作者が自作物やハンドルネームによく使う「ys」というフレーズをくっつけて、「yStandard」にしました。

先頭の「y」に意味はなく、発音する必要も無いと思っておりましたが、「yStandard」を「y」の部分まで発音すると「why standard」に聞こえることから"一風変わった"というコンセプトを掲げています

## 必要な動作環境

- WordPress : 5.4以上
- PHP : 7.3以上

## Third-party resources

### normalize.css
License: MIT License  
Source : <https://github.com/necolas/normalize.css>

### Simple Icons
License: CC0 - 1.0  
Source : <https://github.com/simple-icons/simple-icons>

### Feather
License: MIT  
Source : <https://github.com/feathericons/feather>

### Font Awesome

Font License: SIL OFL 1.1  
Code License: MIT License  
Source      : <https://fortawesome.github.io/Font-Awesome/>

### Theme Update Checker Library

License: MIT License  
Source : <https://github.com/YahnisElsts/plugin-update-checker>

### TGM-Plugin-Activation

License: GPL-2.0  
Source : <https://github.com/TGMPA/TGM-Plugin-Activation>

### \_decimal.scss

License: MIT License  
Source : <https://gist.github.com/terkel/4373420>

### css-vars-ponyfill

License: MIT License  
Source : <https://github.com/jhildenbiddle/css-vars-ponyfill>

### object-fit-images

License: MIT License  
Source : <https://github.com/fregante/object-fit-images>

## 変更履歴

### v4.x.x

### v4.2.0
- [追加] サイト全体の文字色・薄文字色の設定追加
- [追加] アイキャッチ画像の表示・非表示を切り替える設定追加
- [追加] アーカイブページサムネイル変更用フック追加
  - template-parts/archive/details.php
  - template-parts/archive/details-list.php
- [修正] フッターメインエリアのデフォルトカラーがカスタマイザーと表示側で違う点の修正
- [調整] 編集画面:グループブロックで背景色ありの時の上下余白調整 
- [調整] カテゴリー・タグの表示スタイル調整 
- [調整] グローバルメニューでメニュー項目が多い場合、モバイルではスクロールするように調整 

### v4.1.0
- [追加] お知らせバーに複数のリンクを設定出来る機能追加
- [追加] お知らせバーで絵文字を使えるように修正
- [修正] 投稿内にXMLがあるとdescription自動生成がおかしくなる点の修正
- [修正] ギャラリーブロックのキャプションが中央表示されない点の修正
- [修正] 記事詳細下の関連記事一覧がスマートフォンで見た時に右側の余白が大きい点の修正
- [修正] 投稿一覧ページのレイアウト修正
  - template-parts/header/header-logo.php
- [修正] 背景色ありレイアウト - 一覧ページ（リスト）の画像周りの余白調整
- [修正] 投稿一覧ウィジェットで長いタイトルを入力すると画面からはみ出ることがある点の修正
- [修正] アイキャッチ画像の表示判断に旧設定の読み込みが紛れている点の修正

### v4.0.8
- [修正] グローバルナビゲーションを表示していない時にJavaScriptのエラーが発生する点の修正

### v4.0.7
- [修正] yStandardのお知らせ機能調整

### v4.0.6
- [修正] SNSシェアボタンアイコン表示不具合修正

### v4.0.5
- [修正] 新着記事・関連記事のキャッシュ機能不具合修正
- [修正] アイコン ショートコード一覧ページで一部のSNSアイコンがコピーできない点の修正
- [調整] yStandardからのお知らせをキャッシュ
- [調整] 一覧ページレイアウトの余白調整
- [調整] パーツ機能のフィルター調整
- [調整] SNSアイコン表示ショートコードのHTML構造調整
- [調整] アイコン表示の初期サイズ指定
- [調整] Spotifyの埋め込みでサムネイル下に隙間が出来る点の調整

### v4.0.4
- [修正] 「AMPエラー：参照している AMP URL はスタンドアロン AMP です」が発生する点の修正

### v4.0.3
- [追加] 「yStandardを始めよう！」ページにカスタマイザーのリンクを追加
- [調整] 拡張機能設定のパネル表示位置の調整

#### v4.0.2
- [追加] サイドバー無効化フック追加
- [修正] モバイルフッターメニューの表示・非表示切り替えをハンバーガーメニューに揃える
- [修正] モバイルフッターメニューのアイコン表示サイズを揃える

#### v4.0.1
- [修正] 著者情報のSNSアイコンでWebのアイコンが下にずれる点の修正
- [修正] スペーサーブロックmargin-topが効いてしまう点の修正
- [修正] おすすめプラグイン機能　翻訳調整
- [修正] アーカイブページ 概要文の2ページ目以降削除

#### v4.0.0
- [追加] v4.0.0リリース

### v3.x.x

v3以前の履歴は以下のページをご覧ください。  
https://wp-ystandard.com/ystandard-update/ystandard-update-old/
