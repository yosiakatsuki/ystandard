# yStandard

![yStandard](./screenshot.png "yStandard")

## カスタマイズありきの一風変わったWordPressテーマ「yStandard」

yStandardは「自分色に染めた、自分だけのサイトを作る楽しさ」を感じてもらうために作った一風変わったテーマです

詳しくは公式サイトをご覧ください

[yStandard](https://wp-ystandard.com/)

## 「yStandard」の由来

「標準」といった意味の「Standard」に作者が自作物やハンドルネームによく使う「ys」というフレーズをくっつけて、「yStandard」にしました。
（「ys-standard」という案もありましたがなんとなくやめておきました。）

先頭の「y」に意味はなく、発音する必要も無いと思っておりましたが、「yStandard」を「y」の部分まで発音すると「why standard」に聞こえることから"一風変わった"というコンセプトを掲げています

## 必要な動作環境

- WordPress : 5.0以上
- PHP : 7.2以上

## Third-party resources

### Font Awesome

Font License: SIL OFL 1.1  
Code License: MIT License  
Source      : <https://fortawesome.github.io/Font-Awesome/>

### Theme Update Checker Library

License: GPL  
Source : <http://w-shadow.com/>


### \_decimal.scss

License: MIT License  
Source : <https://gist.github.com/terkel/4373420>

## 変更履歴

### v3.x.x
- v3.5.1
  - 不具合修正
    - ボタンブロックの角丸0指定が効いていない不具合の修正
    - 追加CSSをエディターのCSSとしてセットする際の不具合修正
- v3.5.0
  - 調整
    - WordPress 5.3で使えるようになったブロック・変更になったブロックに合わせてCSSを調整
    - 次の記事・前の記事スタイル調整
    - 汎用クラスの一部削除
    - Gutenberg用スタイルシート読み込み設定の調整
- v3.4.1
  - 不具合修正
    - ブロックエディターの編集画面CSSが適用されない問題の修正
  - 調整
    - ブログカード表示のドメイン部分をモバイルでは非表示に変更
- v3.4.0
  - 機能追加
    - AMPプラグイン連携機能の追加
      - 影響のあるテンプレートファイル
        - header.php
        - footer.php
    - タイトルなしテンプレートのスリム版追加
  - 調整
    - 画像ブロックの余白調整
    - 背景色ありの段落ブロックスタイル調整
- v3.3.0
  - 機能追加
    - モバイル固定フッターメニュー追加
    - 広告ラベルの変更機能追加
  - 調整
    - フッターナビゲーション調整
    - 広告表示の調整
- v3.2.1
  - 動画ヘッダー設定の不具合対処
  - ヘッダーメニュー下線にテキストカラーが反映されない不具合修正
- v3.2.0
  - 機能追加
    - Font Awesomeのjs/css切り替え機能追加
    - Font Awesome Kitsの設定追加
- v3.1.4
  - 不具合修正
    - ボタンブロックの編集画面スタイルの調整
    - アドミンバー用スタイルシートの読み込み不具合修正
- v3.1.3
  - 不具合修正
    - アドミンバーのユーザー画像が大きく表示される点の修正
    - メディアと文章ブロックの画像幅変更不具合修正
- v3.1.2
  - 調整
    - AMP：画像タグ内の!importantを削除
- v3.1.1
  - 調整
    - カバーブロックのスタイル調整・編集画面側CSSの調整
- v3.1.0
  - 機能追加
    - 投稿者SNSリンク、フッターSNSリンクにAmazonアイコンを追加
      - template-parts/footer/footer-sns.php を修正
  - 調整
    - FontAwesome関連処理の調整
    - ブログカード形式の外部サイト概要文の表示文字数調整
    - 投稿者画像の取得方法変更
      - ys_the_author_avatar,ys_get_author_avatar を非推奨に変更
    - PHP処理でコストの掛かりそうな処理をリファクタリング
- v3.0.7
  - 不具合修正
    - パンくずリストの構造化データ エラー対処
- v3.0.6
  - 不具合修正
    - 投稿リストショートコードのスライド表示がsafariでスライド表示にならない点の修正
- v3.0.5
  - 調整
    - ボタンブロックのスタイル調整
    - グローバルメニュー サブテキストの調整
    - 記事上下ウィジェットの案内文修正
- v3.0.4
  - 不具合修正
    - `has-text-align-xxx`の不具合修正
- v3.0.3
  - 不具合修正
    - カスタムプロフィール画像選択機能の不具合修正
- v3.0.2
  - 不具合修正
    - 広告表示用テキストウィジェットにAdsenseを貼り付けると正しく表示されない点の修正
- v3.0.1
  - 不具合修正
    - 記事下広告の上部余白が消えている点の修正
    - TOPとそれ以外でサイトタイトルの高さが微妙に違う点の修正
    - AMPレイアウトでパンくず前後の余白が大きくなる点修正
    - yStandardウィジェットの共通設定でタイプ別の表示設定が選択できない点の修正
  - 調整
    - 記事下フォローボックスの枠線削除
- v3.0.0 : 2019/07/31
  - v3.0.0リリース
  
### v2までの更新履歴

- [v2までの更新履歴はこちら](./docs/md/history.md)
