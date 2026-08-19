# ヘッダー画像のCSSカスタムプロパティ化計画

## 目的

`.site-header-thumbnail`内のアイキャッチ画像について、既存のレスポンシブ表示を維持しながら、子テーマや追加CSSからサイズとトリミング方法を調整できるようにする。

## ブランチ

- `feature/header-thumbnail-css-custom-properties`

## 現状

`src/styles/components/site-header/_header-thumbnail.scss`では、画像に次の値を直接指定している。

- 全画面幅共通
  - `display: block`
  - `width: 100%`
  - `object-fit: cover`
- 640px以上
  - `height: 50vh`
  - `max-height: 400px`
  - `object-position: 50% 50%`

640px未満では`height`と`max-height`を指定せず、画像本来の縦横比で表示している。

## 設計

`src/styles/foundation/custom-properties/_site-header.scss`へ次のカスタムプロパティを追加する。

| カスタムプロパティ | 初期値 | 用途 |
|---|---:|---|
| `--ystd--header--thumbnail--width` | `100%` | 画像幅 |
| `--ystd--header--thumbnail--height--mobile` | `auto` | 640px未満の画像高さ |
| `--ystd--header--thumbnail--height--tablet` | `50vh` | 640px以上の画像高さ |
| `--ystd--header--thumbnail--max-height--mobile` | `none` | 640px未満の最大高さ |
| `--ystd--header--thumbnail--max-height--tablet` | `400px` | 640px以上の最大高さ |
| `--ystd--header--thumbnail--object-fit` | `cover` | 画像の収め方 |
| `--ystd--header--thumbnail--object-position` | `50% 50%` | トリミング位置 |

`mobile`と`tablet`は既存のアーカイブ画像用カスタムプロパティと同じ命名規則を使う。`tablet`への切り替えは、現在と同じ`global.media-up-mobile()`で行い、ブレークポイントを変更しない。

`src/styles/components/site-header/_header-thumbnail.scss`では、画像の各宣言を対応するカスタムプロパティ参照へ置き換える。640px未満にも`height: auto`、`max-height: none`、`object-position: 50% 50%`を明示するが、いずれも現在の初期挙動と同じである。

`display: block`は画像下の行ボックス余白を防ぐ構造上の指定であり、変更するとレイアウトが崩れるためカスタムプロパティ化しない。`.site-header-thumbnail`の`overflow: hidden`も既存の役割を維持する。

## 実装対象

- `src/styles/foundation/custom-properties/_site-header.scss`
- `src/styles/components/site-header/_header-thumbnail.scss`
- `docs/design-system.md`

HTML、PHPの画像出力、カスタマイザー設定、ブレークポイント定義は変更しない。

## 検証

- `npm run build:css`
- `git diff --check`
- 生成CSSから`width: 100%`、`height: 50vh`、`max-height: 400px`、`object-fit: cover`、`object-position: 50% 50%`の直接指定が対象セレクターに残っていないことを確認する
- 640px未満で画像が従来どおり自然高になることを確認する
- 640px以上で画像が従来どおり`50vh`かつ最大`400px`になることを確認する
- `height`、`max-height`、`object-position`を上書きし、表示へ反映されることを確認する
- 投稿、固定ページ、アーカイブ、タクソノミーで共通の`.site-header-thumbnail`表示に回帰がないことを確認する
