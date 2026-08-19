# ヘッダーのサイトロゴ縮小制御計画

## 目的

`.site-branding`へ`flex-shrink: 0`を適用する条件を、次の両方を満たす場合だけに限定する。

- ヘッダータイプが`row1`
- グローバルナビが展開されて表示されている

それ以外ではFlexboxの初期値`flex-shrink: 1`を使用する。

## ブランチ

- `feature/mobile-site-branding-flex-shrink`

## 設計

`src/styles/components/site-branding/_site-branding-base.scss`から、常時適用されている`flex-shrink: 0`を削除する。`flex-shrink: 1`は初期値のため、明示的な指定は追加しない。

グローバルナビの展開とドロワーボタンの非表示を切り替えている`Drawer_Menu::inline_css()`の動的メディアクエリ内で、`row1`かつ`.global-nav`が存在する場合だけ次の指定を追加する。

```css
@media (min-width: {ドロワーメニュー開始サイズ + 1}px) {
	:where(body.header-type--row1 .site-header__content:has(> .global-nav) > .site-branding) {
		flex-shrink: 0;
	}
}
```

`:has(> .global-nav)`により、グローバルナビが未設定でHTMLへ出力されない場合を除外する。現在のBrowserslist対象ブラウザは`:has()`に対応しているため、PHP側へ状態クラスは追加しない。

## 適用結果

| ヘッダータイプ | グローバルナビ | 表示状態 | `flex-shrink` |
|---|---|---|---|
| `row1` | あり | 展開表示 | `0` |
| `row1` | あり | ドロワーボタン表示 | 初期値`1` |
| `row1` | なし | 未出力 | 初期値`1` |
| `row2`、`center` | 任意 | 任意 | 初期値`1` |

「常にドロワーメニューで表示」が有効な場合は展開用ブレークポイントへ到達しないため、初期値`1`のままになる。

## 実装対象

- `src/styles/components/site-branding/_site-branding-base.scss`
- `inc/navigation/class-drawer-menu.php`
- ビルドで更新される`css/ystandard.css`

HTML構造、PHPの表示判定、JavaScriptは変更しない。

## 検証

- `npm run build:css`
- `npm run lint:php`
- `php -l inc/navigation/class-drawer-menu.php`
- `git diff --check`
- `row1`でブレークポイント前後の切り替えを確認する
- `row1`でグローバルナビ未設定時に縮小可能なことを確認する
- `row2`、`center`の既存レイアウトに影響がないことを確認する
- カスタムブレークポイントと「常にドロワーメニューで表示」で条件がずれないことを確認する
