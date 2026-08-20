# ドロワーメニューのdialog化計画

## 目的

ドロワーメニューの外側を`<dialog>`へ変更し、モーダル表示時のフォーカス制御、背面コンテンツの操作抑止、Escapeキーで閉じる動作、フォーカス復帰をブラウザ標準機能へ委ねる。

参考記事: [dialogタグで面倒が減る！ 見直したいハンバーガーメニューの作り方](https://ics.media/entry/260527/)

## ブランチ

- `feature/dialog-drawer-menu`

## 採用判断

実装は進めて問題ない。

`<dialog>`、`showModal()`、`close()`を必須機能とし、記事で紹介されている新しいCSSやHTML属性は必須にしない。未対応ブラウザでは現在の`.is-open`による開閉へフォールバックし、メニューが利用できなくなる状態を避ける。

## ブラウザ対応

### dialog本体

`<dialog>`はChrome 37、Safari 15.4、Firefox 98以降で利用でき、主要ブラウザ共通では2022年3月からBaseline Widely availableとなっている。

iOS 15.4はiPhone 6s以降へ提供されている。iPhone 6sは2015年9月発売のため、2026年時点で10年以内に発売されたiPhoneは、OSを更新していれば`<dialog>`を利用できる。

Android版ChromeはChrome 37から`<dialog>`を利用できる。2016年以降の一般的なAndroid端末で、ChromeまたはWebViewが更新されていれば問題にならない。

注意が必要なのは端末の製造年ではなく、更新されていないOS・ブラウザである。次の環境ではネイティブ`<dialog>`が動作しない可能性がある。

- iOS 15.3以前のSafari
- 古いAndroid標準ブラウザ
- 更新されていないアプリ内WebView
- 長期間ブラウザ更新を停止している端末

これらには現在のクラス開閉をフォールバックとして残す。フォーカストラップなどの標準モーダル機能は利用できないが、メニューの開閉とリンク操作は維持する。

### 今回必須にしない機能

次の機能は10年前のスマートフォンまで含めた互換性を確保できないため、今回の実装には使用しない。

- `@starting-style`
- `transition-behavior: allow-discrete`
- `interactivity: inert`
- `command`、`commandfor`
- `closedby="any"`

`@starting-style`と`transition-behavior`は2024年以降のブラウザ向け機能で、`interactivity`は2026年時点でも対応が限定的である。HTMLだけで開閉する`command`関連も、記事で案内されているとおり現時点ではJavaScriptより対応範囲が狭い。

## 現行実装

- `template-parts/navigation/drawer-nav.php`が外側を`<div id="drawer-nav">`で出力する
- `src/scripts/drawer-nav/index.ts`が`.is-open`を各要素へ付け外しする
- 本文のスクロール位置は`body`を`position: fixed`にして保持する
- ドロワー内のアンカーリンクと閉じるボタンで閉じる
- `.drawer-nav`を固定配置し、幅、高さ、可視性、`z-index`で表示を制御する
- モーダル表示中も背面要素へのTab移動をブラウザ標準では抑止できない

Toolboxなどが利用する`ys_drawer_nav_*`フックの位置と、`wp_footer`優先度0での出力は変更しない。

## HTMLとPHP

`template-parts/navigation/drawer-nav.php`の外側だけを`<dialog>`へ変更する。内部の閉じるボタン、検索フォーム、ナビゲーション、アクションフックは現在の順序を維持する。

ダイアログには翻訳可能な`aria-label`を設定する。開くボタンと閉じるボタンには次の属性を追加する。

- `type="button"`
- 用途に応じた翻訳可能な`aria-label`
- 開くボタンの`aria-controls="drawer-nav"`
- 開くボタンの`aria-expanded="false"`

既存の`Drawer_Menu::get_toggle_button()`にある`type`引数を、開くボタンと閉じるボタンの属性を切り替えるために使用する。`ys_get_toggle_button_html`フィルターは残し、既存のアイコン差し替え経路も維持する。

## JavaScript

`src/scripts/drawer-nav/index.ts`で`showModal()`の有無を判定する。

ネイティブ対応ブラウザでは次のように制御する。

- 開くボタンで`showModal()`を実行する
- 閉じるボタンと対象リンクで`close()`を実行する
- ダイアログ本体がクリック対象になった場合は背景クリックとして閉じる
- Escapeキーによる標準の`cancel`処理を妨げない
- `close`イベントを終了処理の正本とし、クラス、`aria-expanded`、本文スクロール、モバイルフッターの状態を必ず戻す
- 閉じたあと、フォーカスが開くボタンへ戻ることを確認する

未対応ブラウザでは`showModal()`と`close()`を呼ばず、現在の`.is-open`による開閉を使用する。Escapeキーで閉じる処理と開くボタンへのフォーカス復帰を追加するが、独自のフォーカストラップは実装しない。

開閉状態を単純な`toggle`だけで操作せず、開く処理と閉じる処理を分離する。Escapeキー、背景クリック、リンククリックなど複数の終了経路でも状態がずれない構成にする。ページのスクロール固定状態と、ダイアログの表示アニメーション状態も分離する。

標準CSSでスクロール連鎖とスクロールバー領域を制御できる環境では、`body`を固定配置へ変更しない。`overscroll-behavior`または必要な`scrollbar-gutter`が未対応の環境では、本文のスクロール位置を保持する既存処理へフォールバックする。フォールバック時はスクロールバー幅を差し引き、変更前のインラインスタイルを復元する。

閉じるボタン領域の高さ計算、グローバルナビ検索とモバイルフッターのクラス連携は維持する。

## CSS

`src/styles/components/navigation/_drawer-menu.scss`を`<dialog>`向けに調整する。

- ブラウザ標準の`margin`、`border`、`max-width`、`max-height`をリセットする
- ネイティブ環境は`[open]`、フォールバック環境は`.is-open`を表示状態として扱う
- トップレイヤーで表示されるため、開閉判定に`z-index`を使わない
- 既存の全画面レイアウト、背景色、内側コンテナ幅、内部スクロールを維持する
- `html.is-drawer-open`で背景ページの`overflow`を制御し、`:has()`だけには依存しない
- ダイアログへ`overscroll-behavior: none`、内部スクロール領域へ`overscroll-behavior: contain`を指定する
- クラシックスクロールバーがある場合は`scrollbar-gutter: stable`で表示幅を維持する
- 開くときの視覚効果は既存の`opacity`を基礎にし、JavaScriptで付ける状態クラスで制御する
- `prefers-reduced-motion: reduce`ではトランジションを無効にする

`--ystd--z-index--drawer-nav`は既存サイトや拡張機能から参照される可能性があるため、今回の変更では削除しない。

## 実装対象

- `template-parts/navigation/drawer-nav.php`
- `inc/navigation/class-drawer-menu.php`
- `src/scripts/drawer-nav/index.ts`
- `src/styles/components/navigation/_drawer-menu.scss`
- ドロワーメニュー用のPHPテスト

ビルドで`js/ystandard.js`と`css/`配下が更新されるが、現在のGit管理方針に従う。

## 検証

- `npm run build`
- `npm run test:php`のドロワーメニュー関連テスト
- 変更PHPファイルの構文チェックとPHPCS
- `git diff --check`
- 開くボタンでモーダル表示され、`aria-expanded`が更新される
- 開いた直後のフォーカスがダイアログ内へ移る
- TabキーとShift+Tabキーで背面コンテンツへ移動しない
- Escapeキー、閉じるボタン、背景クリック、同一ページ内リンクで閉じる
- 閉じたあとに開くボタンへフォーカスが戻る
- 開閉前後で本文のスクロール位置が変わらない
- 開閉時に背景コンテンツとヘッダー画像の表示幅が変わらない
- ダイアログ内のスクロール終端から背景ページへスクロールが連鎖しない
- ドロワー内の検索フォーム、WordPressメニュー、Toolboxのウィジェット出力が維持される
- モバイルフッターメニューと管理バーの既存表示制御が維持される
- `showModal()`を無効化した状態でも、フォールバックでメニューを開閉できる
- 640px前後とカスタムのドロワー開始幅で表示状態がずれない

## 参考資料

- [ICS MEDIAの記事](https://ics.media/entry/260527/)
- [WebKit: Introducing the Dialog Element](https://webkit.org/blog/12209/introducing-the-dialog-element/)
- [MDN: dialog要素](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/dialog)
- [Apple: iOS 15.4の対応端末](https://support.apple.com/en-us/102850)
- [Apple: iPhone 6s発表資料](https://www.apple.com/newsroom/2015/09/09Apple-Introduces-iPhone-6s-iPhone-6s-Plus/)
- [MDN: @starting-style](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/At-rules/%40starting-style)
- [MDN: transition-behavior](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/Properties/transition-behavior)
- [MDN: interactivity](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/Properties/interactivity)
- [CSS Overscroll Behavior Module Level 1](https://drafts.csswg.org/css-overscroll/)
- [CSS Overflow Module Level 3: scrollbar-gutter](https://drafts.csswg.org/css-overflow/#scrollbar-gutter-property)
- [WebKit Bug 240859: bodyのoverflow hiddenとiOS](https://bugs.webkit.org/show_bug.cgi?id=240859)
