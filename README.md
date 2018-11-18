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

- WordPress : 4.5以上
- PHP : 5.6以上

## スタイルについて

### ブレークポイント

- SP
  - 指定なし
- Tablet
  - `@media (min-width: 600px) {}`（テーマデフォルト）
- PC
  - `@media (min-width: 960px) {}`（テーマデフォルト）
- 大画面
  - `@media (min-width: 1200px) {}`（テーマデフォルト）

### 汎用的なスタイル

- `.ys-box`
  - 囲みブロック用クラス
- `.ys-btn`
  - ボタン用クラス
- `.ys-btn--full`
  - 幅100%用ボタンクラス
- `.ys-btn--large`
  - 大きめボタン用クラス
- `.ys-text-left`
  - テキストの左寄せ
- `.ys-text-center`
  - テキストのセンタリング
- `.ys-text-right`
  - テキストの右寄せ
- `.ys-smaller`
  - フォントサイズを少し小さくする(0.8倍)
- `.ys-larger`
  - フォントサイズを少し大きくする(1.2倍)
- `.ys-normal`
  - フォントサイズをメインサイズにする

## カスタマイズ

### CSS,JSの読み込み

- ファーストビューにかかわらない部分のCSSの追加読み込み
  - `ys_enqueue_non_critical_css( $id, $src, $ver = false )`
    - `$id`  : CSSファイルのid(プログラム内で判断する用の文字列)
    - `$src` : CSSファイルのURL
    - `$ver` : バージョン文字列(キャッシュ対策用。クエリストリングに追加されます)

- 遅延読み込みさせるCSS
  - `ys_enqueue_lazyload_css( $id, $src, $ver = false )`
    - `$id`  : linkタグに追加するid
    - `$src` : CSSファイルのURL
    - `$ver` : バージョン文字列(キャッシュ対策用。クエリストリングに追加されます)

- ページ表示後にすぐ読み込むJavaScript
  - `ys_enqueue_onload_script( $id, $src )`
    - `$id`  : scriptタグに追加するid
    - `$src` : JavaScriptファイルのURL

- 遅延読み込みさせるJavaScript
  - `ys_enqueue_lazyload_script( $id, $src )`
    - `$id`  : scriptタグに追加するid
    - `$src` : JavaScriptファイルのURL

## Third-party resources

### Font Awesome

Font License: SIL OFL 1.1  
Code License: MIT License  
Source      : <https://fortawesome.github.io/Font-Awesome/>

### Theme Update Checker Library

License: GPL  
Source : <http://w-shadow.com/>

### object-fit-images

License: MIT License  
Source : <https://github.com/bfred-it/object-fit-images>

### stickyfill

License: MIT License  
Source : <https://github.com/wilddeer/stickyfill>

### \_decimal.scss

License: MIT License  
Source : <https://gist.github.com/terkel/4373420>

## 変更履歴

### v2.x.x
- 2.11.0 : 2018/11/18
  - 機能追加
    - カテゴリー・タグの記事一覧、人気記事一覧、記事下の関連記事…で表示される投稿一覧の結果をキャッシュする機能追加
  - 不具合修正
    - カスタム投稿タイプの一覧・詳細ページで、カテゴリーが設定されていない場合はカテゴリー情報を表示しない
    - ヘッダーの位置をセンターにしたときに、モバイルで見るとサブタイトルが中央揃えになっていない点の修正
  - 調整
    - ワンカラムテンプレートの横幅いっぱい画像表示で、アイキャッチがないときの表示調整
- 2.10.0 : 2018/11/04
  - 機能追加
    - 著者情報表示ウィジェット追加
- 2.9.5 : 2018/10/26
  - 不具合修正
    - モバイルメニューの表示でメニューが階層化されている場合、２階層目の最後のメニューがインデントされない点の修正
- 2.9.4 : 2018/10/14
  - 不具合修正
    - 抜粋文字数の設定が反映されない点の修正
  - 調整
    - ウィジェット ： 記事下エリア・記事上エリアの表示に関する説明文を調整
- 2.9.3 : 2018/10/08
  - 不具合修正
    - ビジュアルエディタのテーマのCSS適用機能のデフォルト値をOFFに変更
- 2.9.2 : 2018/09/28
  - 機能追加
    - Gutenberg対応：Youtube埋め込み表示対応
    - Gutenberg対応：yStandardオリジナルブログカードの展開対応
- 2.9.1 : 2018/09/28
  - 不具合修正
    - Gutenberg 編集画面にテーマのスタイルシート調整
- 2.9.0 : 2018/09/28
  - 機能追加
    - Gutenberg 編集画面にテーマのスタイルシートを適用
    - ビジュアルエディタにテーマのスタイルシートを適用
- 2.8.1 : 2018/09/21
  - 不具合修正
    - yStandard投稿オプションが予約投稿した記事から消える不具合対処
- 2.8.0 : 2018/09/20
  - 機能追加
    - ウィジェットのカテゴリー選択を階層表示に対応
    - 表示条件付きHTML、表示条件付きテキストウィジェットの「掲載するカテゴリー・タグ」の条件を子孫カテゴリーまで含める
- 2.7.1 : 2018/09/14
  - 不具合修正
    - YouTubeやGoogleマップをサイドバーに表示したとき、投稿詳細ページ以外でレスポンシブ表示に変換されない問題の対処
- 2.7.0 : 2018/08/30
  - 機能追加
    - ヘッダーメディア機能追加
  - 不具合修正
    - コメント欄HTML構造不具合修正
- 2.6.0 : 2018/08/19
  - 機能追加
    - カテゴリー・タグの一覧ページに表示される説明欄で使えるHTML種類の増加、ショートコードを使えるように
    - カテゴリー一覧タイトルのフィルターフックに「ページ」引数追加
  - 不具合修正
    - `search.php`テンプレートの追加
  - 調整
    - conditional-branch -> conditional-tag に名前変更
- 2.5.1 : 2018/07/20
  - 不具合修正
    - サイドバーの追従機能が動作しない点の修正
  - 調整
    - ランキング一覧、カテゴリー・タグ別記事一覧の余白調整
- 2.5.0 : 2018/07/12
  - 機能追加
    - 「表示条件付きカスタムHTMLウィジェット」「表示条件付きテキストウィジェット」の追加
    - 人気記事ランキング、カテゴリー別記事一覧ウィジェットに「横並び」「横スライド」表示を追加
    - 人気記事ランキング、カテゴリー別記事一覧ショートコードにパラメーター追加
      - mode
      - cols
      - class_list
      - class_item
      - class_link
    - 記事本文前後に表示するウィジェット表示エリアを追加
  - 調整
    - 人気記事ランキング、カテゴリー別記事一覧ウィジェット等のスタイル微調整
- 2.4.0 : 2018/06/13
  - 機能追加
    - 公開日・更新日の非表示設定
  - 調整
    - 広告出力用関数の機能調整
  - 不具合修正
    - ホームページ設定でホームページ表示を「固定ページ」を選択中の投稿ページタイトル表示不具合修正
    - テーマカスタマイザーの「SP」の表記を「モバイル」に統一
- v2.3.1 : 2018/06/06
  - 不具合修正
  	- jQueryのフッター移動機能調整
- v2.3.0 : 2018/06/05
  - 機能追加
    - jQueryのフッター移動機能追加
    - インフィード広告挿入機能追加
    - 一覧ページと投稿ページのカラム数変更機能 機能追加
    - yStandardウィジェットアイテムのショートコード化
    - 投稿タイトル上・下で実行するアクションフックを追加（ `ys_before_entry_title` , `ys_after_entry_title` ）
    - 記事タイトル上・下に広告枠を追加（PC,SP,AMP）
    - フロントページ作成タイプの追加 β版（DIY）
  - 調整
    - 背景色ありの場合のスタイル調整
    - カテゴリー・タグの記事一覧ウィジェットでカテゴリー・タグ未選択の場合は最新の記事一覧を表示するように修正
  - 不具合修正
    - スマホスライドメニューのボタン色が変えられない問題の対処（設定追加）
    - ヘッダーをセンタリングしている場合にSPメニューも真ん中寄せになってしまう問題の対処
    - サイト概要の文字色を変更できる設定を追加
- v2.2.0 : 2018/05/24
  - 機能追加・処理変更
    - ブログカードショートコードにパラメーター追加
    - ワンカラムテンプレートでのアイキャッチ画像表示タイプ選択オプションの追加
    - カスタマイザー作成関数をクラス化
  - 不具合修正
    - yStandard投稿オプションの文言調整
- v2.1.7 : 2018/05/12
  - 不具合修正
    - カスタムタクソノミーのターム一覧ページでOGPタグとパンくずが正常に作成されない点の修正
- v2.1.6 : 2018/05/10
  - 不具合修正
    - headタグのmeta referrerの指定を削除
    - 構造化データの articleSection が複数カテゴリーに対応していなかった点を修正
    - 複数カテゴリーの指定された記事のカテゴリー表示がカテゴリー一覧で違うものになる点の調整
- v2.1.5 : 2018/04/26
  - 不具合修正
    - tableタグがはみ出る場合がある点の修正
- v2.1.4 : 2018/04/26
  - 不具合修正
    - 構造化データのhentry関連エラーの修正
    - キャプションありの画像がスマホで切れて見える点の修正
- v2.1.3 : 2018/04/17
  - 不具合修正
    - OGPのapp_id,adminsの指定誤り修正
- v2.1.2 : 2018/04/15
  - 不具合修正
    - LINE共有ボタンで送信した内容がリンクとして機能しない点の修正
- v2.1.1 : 2018/04/12
  - 不具合修正
    - 記事下購読ボタン部分のアイキャッチ画像がiPadではみ出る問題の修正
    - 記事下 次の記事・前の記事の左右余白調整
- v2.1.0 : 2018/04/07
  - 機能追加
    - tableタグにいくつか表示パターンを追加
  - 不具合修正
    - ランキングウィジェットでカテゴリーアーカイブで下層カテゴリーが絞り込みに含まれていない点の修正
    - 背景色を設定した時の2カラムのコンテンツ域余白調整
- v2.0.1 : 2018/03/30
  - 不具合修正
    - README修正
    - 記事下広告領域でGoogle Adsenseを使った時にうまく展開されないことがある点の修正
    - フッターメニュー項目の余白の方向の変更
    - ウィジェット内でul,olを多階層にした場合の余白の調整
    - 記事下ウィジェット
  - yStandard設定ページにslackコミュニティへの導線や応援ページへの導線を追加
- v2.0.0 : 2018/03/28
  - 大幅リニューアル版をリリース

### v1.x.x

- v1.1.1 : 2017/11/30
  - 不具合修正
    - フロントページに指定している固定ページで「1カラム」テンプレートを選択しても1カラム表示にならない不具合の修正
    - amp-imgタグ置換の不具合修正
- v1.1.0 : 2017/11/10
  - 機能追加
    - 投稿のヘッダー部分にシェアボタンを表示できるオプションを追加
    - ys_is_amp, ys_is_amp_enableにフィルターフックを追加
  - 不具合修正
    - コメント欄周りのスタイル微調整
    - ページャーの表示崩れ修正
    - 設定ページの不具合修正

- v1.0.1 : 2017/09/14
  - 機能追加
    - 著者情報表示ショートコードにユーザー指定機能を追加
  - 不具合修正
    - 投稿内で著者情報表示ショートコードを使った時のスタイル調整
    - ヘッダーナビゲーションホバー時に表示される下層メニューの背景色を設定
    - リストの左に画像を回り込ませた場合に「・」が画像に近くなる点の調整

- v1.0.0
  - 機能追加
    - AMPページでのFont Awesomeをサポート
    - デフォルトのフォントカラーを黒濃いめに調整
    - AMP設定メニューはAMP有効化した時のみに表示する
    - AMPページ生成でWordPressサイトのoembed関連iframeの削除
    - AMP用 Google Analytics トラッキングID設定の追加
    - 広告のキャプション変更フィルターフック追加
    - 広告用HTML作成メソッドに広告ラベル無しオプションを追加
    - AMP正規表現置換処理を関数化
    - AMP用置換でテーマが置換する内容より先に置換処理をするためのフィルタを追加
  - 不具合修正
    - ギャラリーが機能していない点の修正
    - パーマリンク設定が「基本」の時にAMPページのURLが正しく出力されない点の修正
    - 記事下広告に高さの違うものを設定した時、上揃えにする
    - apple touch iconが反映されていない点の修正

### v0.7.x

- v0.7.0
  - 不具合修正
    - firefoxで一覧の画像が表示されない問題対処
    - ページングありのページでnext,prevのlinkタグがうまく設定出来ていない点対処
  - 機能追加
    - フォントを細く特徴的にする
    - 現在のテーマバージョン情報を表示する
    - シェアボタンで使うタイトルからWordPress標準で出力される&#8211;をハイフンに置換する
    - ツイート後におすすめユーザーを表示するオプションの追加
    - ワンカラム機能の追加

### v0.6.x

- v0.6.2 : 2017/05/15
  - 不具合修正
    - AMP表示で画像が横に飛び出している
    - 追従サイドバー直上の余白がなくなっている

- v0.6.1 : 2017/05/12
  - 不具合修正
    - 改行時の単語の切れ方を調整
    - 大きい画像を表示した際にAMPフォーマットで画像がはみ出る場合があるの対処
    - AMPフォーマットで記事下フォローボタンのアイコンフォントがお豆腐になる
    - 記事先頭のアイコン（SVG）がsafariで見た時に余分な余白がある
    - 次の記事・前の記事でHOMEボタンが表示されている時、中央寄せになっていない
    - 記事本文の相対的なフォントサイズの指定がうまくいってない
    - safariで追従サイドバーが見えなくなる

- v0.6.0 : 2017/05/02
  - preタグの改行指定変更
  - 構造化データhentryでauthorがありませんのエラーが出る点の対処
  - 固定ページでは記事内に広告を表示しないように修正

### v0.5.x

- v0.5.0 : 2017/04/21
  - 追加機能
    - 記事ごとにシェアボタン表示・非表示を設定できる機能
  - 不具合修正
    - 設定画面の設定更新時に「更新しました」表示が出ない不具合対処
    - フォーム設置した際にチェックボックスなどが表示されない不具合対処
    - オフカンバスメニュー開閉時に、メニューアイテムがごちゃごちゃする不具合対処
    - 長いブログ名の時、ブログ名がハンバーガーアイコンに激突する不具合修正
    - パンくずが2段になるときの余白が足りない不具合修正

### v0.4.x

- v0.4.0 : 2017/04/14
  - 記事直下に表示するウィジェット機能追加(スタイリングは`.widget-entry-footer`にて可能)
  - Twitterカードのカード種類を`summary_large_image`に変更
  - 次の記事・前の記事のリンクに画像を追加

### v0.3.x

- v0.3.2 : 2017/04/08
  - RSSフィードにアイキャッチ画像を表示
  - Twitter埋め込みの余白調整
  - タイトルとブログ名の区切り文字の変更機能追加
  - アイキャッチ画像の出力方法変更
- v0.3.1 : 2017/04/01
  - 汎用ボタンクラスのスタイル調整
  - 汎用ボタン、カスタマイズ用クラス追加
  - ブラウザキャッシュ対策（CSSのURLにクエリストリングを追加）
  - 広告用ウィジェットのスタイル調整
- v0.3.0 : 2017/03/24 全体的な色調整、シェアボタンのフック追加、購読リンク4種設定、jQuery読み込みオプション、遅延js,css読み込み機能、コンテンツ幅調整（800px）

### v0.2.x

- v0.2.2 : 2017/03/17 構造化エラー対処、次の記事・前の記事スタイル調整
- v0.2.1 : 2017/03/10 HTML5 バリデーションエラー対処等のバグフィックス（#8~#11,#14~#23,#25）
- v0.2.0 : 2017/03/01 ベータ版一般公開

### v0.1.x

- v0.1.4 : 2017/02/27 テーマカスタマイザーに色変更機能を追加
- v0.1.3 : 2017/02/19 ログイン中はGoogle Analyticsのトラッキングタグを出力しないように調整、一覧ページの画像が縦横比固定で出力されるように調整
- v0.1.2 : 2017/02/16 404ページ調整,検索結果ページのアーカイブタイトル修正
- v0.1.1 : 2017/02/12 個別投稿の構造化データでauthorがエラーになる問題対処
- v0.1.0 : ~~2017/02/12 ベータ版公開~~

### v0.0.x

- 2017/02/06：ソース管理をGitHubに移行
- 2016/12/xx：「Google PageSpeed Insightsでの高得点を出しやすい」「速い」をコンセプトに再作成開始
- 2016/11/15：とりあえずGithubにて公開…の予定をやっぱりやめてもう一度構想から練り直し
- 2016/10/xx：開発開始
