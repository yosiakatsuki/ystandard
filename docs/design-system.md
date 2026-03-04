# yStandard デザインシステム 現状ドキュメント

## 概要

yStandard v5.0.0 のデザインシステムは、**CSSカスタムプロパティを中核としたトークンベースの設計**を採用している。theme.json（v3）による WordPress ネイティブのデザイントークンと、テーマ独自の `--ystd--` プレフィックス付きカスタムプロパティの二層構造で構成される。

---

## 1. デザイントークン

### 1.1 カラー

#### theme.json カラーパレット（18色）

WordPress ブロックエディターに登録されるカラーパレット。`ys-` プレフィックスのスラッグで定義。

| スラッグ | 名前 | 値 |
|---------|------|-----|
| `ys-blue` | Blue | `#07689f` |
| `ys-light-blue` | Light Blue | `#ceecfd` |
| `ys-red` | Red | `#ae3b43` |
| `ys-light-red` | Pink | `#f2d9db` |
| `ys-green` | Green | `#007660` |
| `ys-light-green` | Light Green | `#c8eae4` |
| `ys-yellow` | Yellow | `#e29e21` |
| `ys-light-yellow` | Light Yellow | `#ffedcc` |
| `ys-orange` | Orange | `#dc760a` |
| `ys-light-orange` | Light Orange | `#fdebd8` |
| `ys-purple` | Purple | `#711593` |
| `ys-light-purple` | Light Purple | `#f6e3fd` |
| `ys-gray` | Gray | `#656565` |
| `ys-light-gray` | Light Gray | `#f1f1f3` |
| `ys-link-blue` | Link Blue | `#0073aa` |
| `ys-link-hover-blue` | Link Hover Blue | `#409ad5` |
| `ys-black` | Black | `#222222` |
| `ys-white` | White | `#ffffff` |

ユーザー定義色を最大3つ追加可能（`ys-user-1` ~ `ys-user-3`）。

#### CSSカスタムプロパティ - ベースカラー

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--text-color` | `#222222` | `ys_color_site_text` |
| `--ystd--text-color--white` | `#ffffff` | - |
| `--ystd--text-color--gray` | `#656565` | `ys_color_site_gray` |
| `--ystd--link--text-color` | `#2980b9` | `ys_color_link` |
| `--ystd--link--text-color--hover` | `#409ad5` | `ys_color_link_hover` |

#### CSSカスタムプロパティ - 背景色

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--site--background` | `#ffffff` | `ys_color_site_bg` |
| `--ystd--site--background-rgb` | `255, 255, 255` | - |
| `--ystd--site--background--gray` | `#e9ecef` | - |
| `--ystd--site--background--light-gray` | `#f1f1f3` | - |

#### CSSカスタムプロパティ - ボーダー色

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--site--border-color--dark` | `#292b2c` |
| `--ystd--site--border-color--gray` | `#bdc3c7` |
| `--ystd--site--border-color--light-gray` | `#eeeeee` |

各ボーダー色には `-rgb` サフィックスのRGB値版も定義されている。

#### CSSカスタムプロパティ - アラートカラー

| プロパティ（背景） | プロパティ（テキスト） | 系統 |
|-------------------|---------------------|------|
| `--ystd--alert--background--blue` (`#ceecfd`) | `--ystd--alert--text-color--blue` (`#07689f`) | 情報 |
| `--ystd--alert--background--red` (`#f2d9db`) | `--ystd--alert--text-color--red` (`#ae3b43`) | エラー |
| `--ystd--alert--background--green` (`#c8eae4`) | `--ystd--alert--text-color--green` (`#007660`) | 成功 |
| `--ystd--alert--background--yellow` (`#ffedcc`) | `--ystd--alert--text-color--yellow` (`#e29e21`) | 警告 |

#### CSSカスタムプロパティ - SNSブランドカラー

`--ystd--sns--color--{service}` の形式で約20サービス分定義。各色にHEX版と `-rgb` サフィックスのRGB値版がある。

対応サービス: globe, twitter, x, facebook, hatenabookmark, rss, feedly, pocket, instagram, line, tumblr, youtube, github, pinterest, linkedin, amazon, wordpress, twitch, dribbble, bluesky

---

### 1.2 タイポグラフィ

#### フォントファミリー

**theme.json 定義（3種）:**

| スラッグ | 名前 | font-family |
|---------|------|------------|
| `ystd-gothic` | ゴシック系 | `"Helvetica Neue", Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif` |
| `ystd-serif` | 明朝系 | `serif` |
| `ystd-yu-gothic` | 游ゴシック | `"Helvetica Neue", Arial, "Yu Gothic", YuGothic, sans-serif` |

**CSSカスタムプロパティ:**

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--font-family` | `"Helvetica neue", "Segoe UI", "Hiragino Sans", ...sans-serif` | `ys_design_font_type` |
| `--ystd--font-family--code` | `SFMono-Regular, Menlo, Monaco, Consolas, monospace` | - |
| `--ystd--font-family--yu` | `Avenir, "Segoe UI", "游ゴシック体", YuGothic, ...sans-serif` | - |

カスタマイザー選択肢: `meihiragino`（メイリオ・ヒラギノ角ゴシック）、`yugo`（游ゴシック）、`serif`（明朝体）。`ys_usable_fonts` フィルターで拡張可能。

#### フォントサイズ

**theme.json 定義（約80種）:**

2つのパターンで定義:

1. **固定サイズ** (`ys-static-{size}`): `fluid: false` で固定値
2. **Fluidサイズ** (`ys-{max}-{min}`): `clamp()` で可変。390px〜1200px のビューポート幅で補間

例: `ys-24-16` → `clamp(16px, calc(...), 24px)` - モバイルで16px、デスクトップで24px

**ブロックエディターフォントサイズプリセット（7段階）:**

| スラッグ | ラベル | サイズ |
|---------|-------|--------|
| `ys-x-small` | 極小 | 12px |
| `ys-small` | 小 | 14px |
| `ys-normal` | 標準 | 16px |
| `ys-medium` | 中 | 18px |
| `ys-large` | 大 | 20px |
| `ys-x-large` | 極大 | 22px |
| `ys-huge` | 巨大 | 26px |

**CSSカスタムプロパティ:**

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--line-height` | `1.7` |
| `--ystd--letter-spacing` | `0.05em` |
| `--ystd--font-weight--normal` | `400` |
| `--ystd--font-weight--bold` | `700` |

#### 見出し

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--headline--default--margin-top` | `3em` |
| `--ystd--headline--default--margin-bottom` | `0.25em` |
| `--ystd--headline--font-size--h1` | `1.4em` |
| `--ystd--headline--font-size--h2` | `1.4em` |
| `--ystd--headline--font-size--h3` | `1.3em` |
| `--ystd--headline--font-size--h4` | `1.2em` |
| `--ystd--headline--font-size--h5` | `1.1em` |
| `--ystd--headline--font-size--h6` | `1.1em` |
| `--ystd--headline--line-height` | `1.3` |
| `--ystd--headline--letter-spacing` | `0.05em` |

---

### 1.3 スペーシング

#### theme.json スペーシングサイズ（約60種）

固定値とFluid値の2パターン:

1. **固定値** (`ys-static-{size}`): 10px〜200pxまで
2. **Fluid値** (`ys-fluid-{min}-{max}`): `clamp()` で可変

#### CSSカスタムプロパティ

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--layout-gap` | `var(--wp--style--block-gap, 1.5rem)` |
| `--ystd--block-gap` | `var(--ystd--layout-gap, 1.5em)` |
| `--ystd--body--padding-top` | `0` |

---

### 1.4 レイアウト

#### theme.json レイアウト設定

| 設定 | 値 |
|------|-----|
| contentSize | `800px` |
| wideSize | `1200px` |

#### CSSカスタムプロパティ - コンテナ

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--container--width` | `var(--wp--style--global--wide-size, 1200px)` |
| `--ystd--container--gutter` | `1rem` |
| `--ystd--container--size` | `min(calc(100% - gutter * 2), width)` |
| `--ystd--container--margin-vertical` | `var(--ystd--layout-gap)` |

#### CSSカスタムプロパティ - コンテンツ

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--content--width` | `var(--wp--style--global--content-size, 800px)` |
| `--ystd--content--min-width` | `66.66%` |
| `--ystd--content--margin-bottom` | `calc(2 * var(--ystd--layout-gap))` |

#### CSSカスタムプロパティ - サイドバー

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--sidebar--2col--size` | `clamp(12.5rem, -36.955rem + 77.27vw, 21rem)` |
| `--ystd--sidebar--2col--gap` | `2rem` |

---

### 1.5 Z-Index

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--z-index--header` | `8` |
| `--ystd--z-index--global-nav-search` | `11` |
| `--ystd--z-index--drawer-nav` | `11` |
| `--ystd--z-index--global-nav--button` | `10` |
| `--ystd--z-index--global-nav--sub-menu` | `12` |
| `--ystd--z-index--mobile-footer` | `8` |
| `--ystd--z-index--back-to-top` | `8` |

---

## 2. ブレークポイント

SCSSの `$breakpoints_setting` マップで定義。CSSカスタムプロパティではなく、SCSSのmixinで利用。

| 名前 | 値 | 用途 |
|------|-----|------|
| `mobile` | 640px | モバイル判定 |
| `tablet` | 768px | タブレット判定（iPad縦） |
| `desktop` | 1024px | デスクトップ判定（iPad Pro縦） |
| `wide` | 1200px | ワイド判定 |
| `sm` | 600px | WordPress管理画面互換 |
| `md` | 769px | WordPress管理画面互換 |
| `lg` | 1025px | WordPress管理画面互換 |

### メディアクエリMixin

| mixin | 適用範囲 |
|-------|---------|
| `media-only-mobile()` | 640px未満 |
| `media-up-mobile()` | 640px以上 |
| `media-up-tablet()` | 768px以上 |
| `media-under-tablet()` | 768px未満 |
| `media-up-desktop()` | 1024px以上 |
| `media-up-show-sidebar()` | 1024px以上（サイドバー表示） |
| `media-up-wide()` | 1200px以上 |

---

## 3. CSSアーキテクチャ

### 3.1 レイヤー構成

メインCSS（`ystandard.scss`）の読み込み順:

```
1. foundation/
   ├── custom-properties/  -- CSSカスタムプロパティ定義（body:where([class]) セレクタ）
   ├── reset/              -- 独自リセットCSS（:where() セレクタ）
   └── base/               -- HTML要素のベーススタイル（:where() セレクタ）
2. components/             -- UIコンポーネント
3. project/                -- プロジェクト固有スタイル
4. utility/                -- ユーティリティクラス
```

### 3.2 詳細度の設計方針

- **リセット・ベーススタイル**: `:where()` セレクタで詳細度0に抑制
- **CSSカスタムプロパティ**: `body:where([class])` セレクタで定義
- **コンポーネント**: 通常のクラスセレクタ
- **TailwindCSS**: `!important` 付き（管理画面のみで使用、フロントエンドには含まれない）

### 3.3 CSS単位の方針（アクセシビリティ対応）

ブラウザのフォントサイズ設定変更に対応するため、CSS単位の使い分けを以下のとおりとする。

| 用途 | 推奨単位 | 理由 |
|------|---------|------|
| `font-size` | `rem` / `em` | ブラウザのフォントサイズ設定に追従させるため |
| `line-height` | 単位なし数値（例: `1.5`） | フォントサイズに比例して自動調整されるため |
| `letter-spacing` | `em` | フォントサイズに比例させるため |
| `padding` / `margin` / `gap` | `px` | フォントサイズ設定変更時に余白が膨らみ、可読性が低下するのを防ぐため |
| `max-width`（コンテナ） | `rem` | フォントサイズに応じてコンテナ幅も拡大し、1行あたりの文字数を維持するため |
| メディアクエリ | `rem` | フォントサイズが大きいユーザーに適切なレイアウトを提供するため（Tailwind CSS v4 も同方針） |
| `border-width` | `px` | 拡大しても太くなる必要がないため |
| `box-shadow` | `px` | 装飾的な値であり拡大に追従する必要がないため |

※ブラウザズーム（Ctrl/Cmd +/-）ではすべての単位が同じように拡大されるため、上記の使い分けはブラウザのフォントサイズ設定変更時に差が出る。
※`max-width` やメディアクエリに `rem` を使うことで、フォントサイズを大きくしたユーザーでも1行あたりの文字数が極端に減らず、可読性を維持できる。

### 3.4 命名規則

#### CSSカスタムプロパティ

```
--ystd--{コンポーネント}--{プロパティ}
```

例:
- `--ystd--header--background`
- `--ystd--content--post-title--font-size`
- `--ystd--archive--item--title--font-size`

#### CSSクラス（BEM風）

テーマ独自クラスは `ystd` プレフィックス:
- `.ystd` — テーマルート
- コンポーネント単位でクラスを付与

#### TailwindCSS

- プレフィックス: `tw-`
- 管理画面（`admin.scss`）でのみ使用
- フロントエンド（`ystandard.scss`）には含まれない

---

## 4. CSSカスタムプロパティの出力フロー

### 4.1 PHPからの動的出力

```
[各コンポーネントのPHPクラス]
    │
    ├── add_filter('ys_get_css_custom_properties_args', ...)
    │
    └── add_filter('ys_get_css_custom_properties_args_presets', ...)
         │
         ▼
[Enqueue_Styles クラス]
    │
    ├── get_css_custom_properties()
    │   → apply_filters('ys_get_css_custom_properties_args')
    │   → セレクタ: body:where([class])
    │
    └── get_css_custom_properties_override_wp_preset()
        → apply_filters('ys_get_css_custom_properties_args_presets')
        → セレクタ: body
         │
         ▼
[wp_add_inline_style() で <style> タグとして出力]
```

### 4.2 フィルターを利用しているコンポーネント

**`ys_get_css_custom_properties_args`（通常のカスタムプロパティ）:**

- `Header` — ヘッダー背景色・文字色・影
- `Footer` — フッター全般（メイン・ウィジェット・ナビ・サブ・トップへ戻る）
- `Global_Nav` — グローバルナビゲーション色設定
- `Drawer_Menu` — ドロワーメニュー色設定
- `Typography` — フォント・文字色・リンク色
- `Site_Background` — サイト背景色
- `Info_Bar` — インフォバー
- `Mobile_Footer` — モバイルフッター
- `Copyright` — コピーライト
- `Breadcrumbs` — パンくずリスト
- `Post_Content` — 投稿コンテンツ背景色

**`ys_get_css_custom_properties_args_presets`（WPプリセット上書き）:**

- `Archive` — アーカイブページ
- `Block_Editor_Font_Size` — フォントサイズプリセット

### 4.3 デフォルト値の制御

`Enqueue_Utility::get_css_var()` がデフォルト値と比較し、同じ場合は空文字を返すことで不要なプロパティ出力を抑制。SCSS側のデフォルト値とPHP側のデフォルト値が一致している前提の設計。

---

## 5. コンポーネントトークン一覧

### 5.1 サイトヘッダー

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--header--background` | `transparent` | `ys_color_header_bg` |
| `--ystd--header--text-color` | `var(--ystd--text-color)` | `ys_color_header_font` |
| `--ystd--header--description--text-color` | `var(--ystd--text-color--gray)` | `ys_color_header_dscr_font` |
| `--ystd--header--shadow` | `none` | `ys_header_box_shadow` |

### 5.2 サイトブランディング

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--site-branding--margin` | `0 0` |
| `--ystd--site-branding--padding` | `clamp(0.5rem,...,1rem) 0` |
| `--ystd--site-branding--text-color` | `var(--ystd--header--text-color)` |
| `--ystd--site-branding--font-size` | `1.5em` |
| `--ystd--site-branding--font-weight` | `var(--ystd--font-weight--normal)` |

### 5.3 グローバルナビゲーション

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--global-nav--font-size` | `0.9em` | `ys_global_nav_font_size` |
| `--ystd--global-nav--font-weight` | `normal` | `ys_global_nav_bold` |
| `--ystd--global-nav--current--text-color` | `var(--ystd--header--text-color)` | `ys_global_nav_hover_current_text_color` |
| `--ystd--global-nav--sub-menu--background` | `#fff` | `ys_global_nav_sub_menu_background_color` |
| `--ystd--global-nav--sub-menu--text-color` | `currentColor` | `ys_global_nav_sub_menu_text_color` |

### 5.4 ドロワーメニュー

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--drawer-menu--background` | `var(--ystd--text-color)` | `ys_color_nav_bg_sp` |
| `--ystd--drawer-menu--text-color` | `var(--ystd--text-color--white)` | `ys_color_nav_font_sp` |
| `--ystd--drawer-menu--font-size` | `16px` | `ys_drawer_menu_font_size` |
| `--ystd--drawer-menu--width` | `600px` | - |
| `--ystd--drawer-menu--button-color--open` | `var(--ystd--text-color)` | `ys_color_nav_btn_sp_open` |
| `--ystd--drawer-menu--button-color--close` | `var(--ystd--text-color--white)` | `ys_color_nav_btn_sp` |

### 5.5 パンくずリスト

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--breadcrumbs--background` | `transparent` |
| `--ystd--breadcrumbs--text-color` | `var(--ystd--text-color--gray)` |
| `--ystd--breadcrumbs--font-size` | `0.75em` |

### 5.6 アーカイブ

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--archive--gap` | `var(--ystd--layout-gap)` |
| `--ystd--archive--item--title--font-size` | `1em` |
| `--ystd--archive--card--col--mobile` | `1` |
| `--ystd--archive--card--col--tablet` | `2` |
| `--ystd--archive--card--col--desktop` | `3` |

アーカイブには3つの表示パターン（カード型・リスト型・シンプル型）があり、それぞれ専用のトークンを持つ。

### 5.7 サイトフッター

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--footer--background` | `var(--ystd--site--background--gray)` | `ys_color_footer_bg` |
| `--ystd--footer--text-color` | `var(--ystd--text-color)` | `ys_color_footer_font` |
| `--ystd--footer--padding-top` | `4em` | `ys_footer_main_padding_top` |
| `--ystd--footer--padding-bottom` | `1em` | `ys_footer_main_padding_bottom` |

### 5.8 サブフッター

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--sub-footer--background` | `var(--ystd--site--background--light-gray)` | `ys_color_sub_footer_bg` |
| `--ystd--sub-footer--text-color` | `var(--ystd--text-color)` | `ys_color_sub_footer_text` |

### 5.9 コピーライト

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--copyright--text-color` | `var(--ystd--text-color)` |
| `--ystd--copyright--background-color` | `var(--ystd--site--background--gray)` |
| `--ystd--copyright--font-size` | `12px` |

### 5.10 モバイルフッターナビ

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--mobile-footer--text-color` | `var(--ystd--text-color)` |
| `--ystd--mobile-footer--background` | `rgba(#ffffff, 0.95)` |
| `--ystd--mobile-footer-nav--icon--size` | `1.5em` |

### 5.11 ボタン

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--button--text-color` | `#ffffff` |
| `--ystd--button--background-color` | `var(--ystd--text-color)` |
| `--ystd--button--padding` | `0.5em 1.5em` |
| `--ystd--button--border-radius` | `4px` |
| `--ystd--button--box-shadow` | `3px 3px 6px rgba(#000, 0.2)` |

### 5.12 フォーム

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--form--text-color` | `var(--ystd--text-color)` |
| `--ystd--form--background` | `rgba(#fff, 0.9)` |
| `--ystd--form--border-color` | `rgba(#bdc3c7, 0.9)` |

### 5.13 テーブル

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--table--border-color` | `var(--ystd--site--border-color--gray)` |
| `--ystd--table--background` | `var(--ystd--site--background--light-gray)` |

### 5.14 目次（TOC）

| プロパティ | 初期値 |
|-----------|--------|
| `--ystd--toc--text-color` | `var(--ystd--text-color)` |
| `--ystd--toc--background` | `rgba(var(--ystd--site--border-color--light-gray-rgb), .2)` |
| `--ystd--toc--border-color` | `var(--ystd--site--border-color--gray)` |

### 5.15 トップへ戻るボタン

| プロパティ | 初期値 | カスタマイザー設定 |
|-----------|--------|------------------|
| `--ystd--back-to-top--text-color` | `#222` | `ys_back_to_top_color` |
| `--ystd--back-to-top--background-color` | `#fff` | `ys_back_to_top_bg_color` |
| `--ystd--back-to-top--border-radius` | `0` | `ys_back_to_top_border_radius` |

---

## 6. SCSS グローバルリソース

### 6.1 SCSS変数

| 変数名 | 内容 |
|--------|------|
| `$color__sns` | SNSブランドカラーのマップ（約20サービス） |
| `$card_margin` | カード表示マージン（`1.5em`） |
| `$font__editor_font_size` | エディターフォントサイズマップ（6段階） |
| `$breakpoints_setting` | ブレークポイント定義マップ |

### 6.2 SCSS Mixin

| mixin名 | 概要 |
|---------|------|
| `media-only-mobile()` | 640px未満のメディアクエリ |
| `media-up-mobile()` | 640px以上 |
| `media-up-tablet()` | 768px以上 |
| `media-under-tablet()` | 768px未満 |
| `media-up-desktop()` | 1024px以上 |
| `media-up-show-sidebar()` | サイドバー表示サイズ（1024px以上） |
| `media-up-wide()` | 1200px以上 |
| `media-breakpoint($name)` | 汎用min-widthメディアクエリ |
| `media-breakpoint-down($name)` | 汎用max-widthメディアクエリ |
| `media-breakpoint-between($up, $down)` | 範囲メディアクエリ |
| `box-shadow(...)` | box-shadow適用 |
| `child_margin($margin)` | 子要素マージン（デフォルト: `--ystd--layout-gap`） |
| `clearfix` | フロート解除 |
| `posts_column($i, $margin)` | 投稿カラムレイアウト（calc方式） |
| `posts_column_cp($i, $gap)` | 投稿カラムレイアウト（CSS変数方式） |
| `svg_size($width, $height)` | SVGサイズ設定 |

### 6.3 SCSS Function

| function名 | 概要 |
|------------|------|
| `decimal-round($number, $digits, $mode)` | 数値丸め |
| `decimal-ceil($number, $digits)` | 切り上げ |
| `decimal-floor($number, $digits)` | 切り捨て |
| `get_sns_color($sns)` | SNSブランドカラー取得 |
| `get_font_size($type)` | フォントサイズ取得 |

---

## 7. ブロックスタイル

29個のコアブロックに対してカスタムスタイルを提供。`wp_enqueue_block_style()` によるブロック単位の遅延読み込み。

### 対応ブロック

accordion, archives, button, calendar, categories, code, column, columns, cover, details, embed, file, gallery, group, heading, image, latest-comments, latest-posts, media-text, paragraph, pullquote, quote, search, separator, spacer, table, tag-cloud, verse, video

### ファイル命名規則

- ディレクトリ: `css/block-styles/core__{block-name}/`
- フロント用: `{block-name}.css`
- エディター専用: `{block-name}-editor.css`

---

## 8. アセットパイプライン

### ビルドフロー

```
src/styles/*.scss
    │
    ├── sass → temp/css/ or css/
    │
    └── postcss (tailwindcss → autoprefixer → cssnano → css-declaration-sorter)
        │
        └── css/*.css（本番CSS）

src/scripts/*.ts
    │
    └── webpack (@wordpress/scripts) → js/*.js
```

### CSS読み込み順（フロントエンド）

1. `css/ystandard.css` — メインCSS（`ystandard` ハンドル）
2. CSSカスタムプロパティ — インラインCSS（`ystandard-custom-properties` ハンドル）
3. インラインCSS — 各コンポーネントの追加CSS（`ystandard-custom-inline` ハンドル）
4. `css/blocks.css` — ブロック追加CSS + インラインCSS（`ys-blocks` ハンドル）
5. `css/block-styles/core__*/` — ブロック個別CSS（`wp_enqueue_block_style()` で遅延読み込み）
6. `style.css` — テーマルートCSS（最後に読み込み）

### 削除されるCSS

- `wp-block-library-theme` — WordPressブロックライブラリテーマCSS
- `classic-theme-styles` — WordPress 6.1クラシックテーマスタイル

---

## 9. カスタマイザー構成

### パネル・セクション優先度一覧

| キー | 優先度 | 種別 |
|------|--------|------|
| `ys_design` | 900 | パネル（廃止予定） |
| `ys_info_bar` | 1000 | セクション |
| `ys_site_typography` | 1100 | セクション |
| `ys_site_background` | 1110 | セクション |
| `ys_site_header` | 1200 | セクション |
| `ys_global_nav` | 1210 | セクション |
| `ys_drawer_nav` | 1220 | セクション |
| `ys_color_palette` | 1230 | セクション |
| `ys_post_type_option` | 1300 | セクション |
| `ys_site_footer` | 1400 | セクション |
| `ys_mobile_footer` | 1410 | セクション |
| `ys_site_copyright` | 1420 | セクション |
| `ys_breadcrumbs` | 1500 | セクション |
| `ys_toc` | 1510 | セクション |

### 設定値の保存方式

- `setting_type: 'option'`（`theme_mod` ではなくオプションテーブルに保存）
- デフォルト値は `Option::get_default()` で取得

---

## 10. 統計

| 項目 | 数量 |
|------|------|
| CSSカスタムプロパティ総数 | 約220個 |
| theme.json カラーパレット | 18色 + ユーザー定義3色 |
| theme.json フォントサイズ | 約80種 |
| theme.json スペーシング | 約60種 |
| SCSS変数 | 4個 |
| SCSS Mixin | 16個 |
| SCSS Function | 5個 |
| ブレークポイント | 7段階 |
| ブロックスタイル対応 | 29ブロック |
| カスタマイザー設定項目 | 約60項目 |
