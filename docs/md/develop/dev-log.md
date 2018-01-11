## 2018/01/11
- Google Analyticsの設定 ユニバーサルアナリティクスを追加済み
- gtagまで追加したら一旦コミット→AMPのアナリティクスを対処

## 2018/01/07
- そもそもやりたかったのはTwitterカード等の設定をキレイにすることだったはず
  - Twitterカード、OGPの修正完了
- Google Analyticsのタグ出力部分
  - template-parts/google-analytics/gtag.php,analytics.php,amp.phpを作ってコードの追加する

## 2018/01/06
- カスタマイザー項目の洗い出し完了
- 色設定の改修
  - ys_customizer_create_inline_css作ったとこまで

## 2018/01/05 朝
- カスタマイザー項目の洗い出し
- ystandard 基本設定部分の洗い出し完了

## 2018/01/03
- enqueueファイルのリファクタリング

## 2018/01/01
- AMP用headタグ修正
- やること→ wp_headに登録する関数をinc/head/wp_head.phpに集約　主にextras.php

## 2017/12/31
- インラインCSS取得クラスの作成
- やること→styleとminifyを配列内に作成する、setでサニタイズする

## 2017/12/30
- head周りの修正を開始
- template-parts/head/head.phpが作業途中
