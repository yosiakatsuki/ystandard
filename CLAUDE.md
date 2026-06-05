# yStandard

theme.json 対応のクラシック WordPress テーマ（v5.0.0 開発中）。テキストドメインは `ystandard`。

- テーマ動作要件: WordPress 6.5+ / PHP 7.4+（`style.css` ヘッダー）
- 開発環境要件: PHP 8.0+（`composer.json` の `require`）

## 現在の開発状況

- v4 系から v5 への刷新中。下位互換性重視を終了し、最新 WP 2 バージョン前までのサポートに変更
- 実装タスクは [docs/v5-dev.md](docs/v5-dev.md) に集約。実装着手前に必ず参照する
- 作業ブランチは `5.0.0`（master にマージ前）。`style.css` のバージョンは `5.0.0-alpha-*`

## 開発コマンド

### アセット
```bash
npm run watch          # 全アセット監視（CSS + JS）
npm run watch:css      # CSS のみ（theme + block-style）
npm run watch:script   # JS/TS のみ（webpack）
npm run build          # 本番ビルド（clean → CSS/JS/アイコン）
npm run clean          # ビルド成果物削除（css / js / zip / upload）
npm run zip            # 配布用 zip 作成（build 込み）
```

### 品質チェック
```bash
npm run lint           # PHP + JS 一括（run-s lint:*）
npm run lint:php       # PHPCS（composer phpcs と等価）
npm run lint:js        # ESLint（src/scripts/*.js を --fix）
```

### テスト
```bash
npm run test           # = npm run test:php
npm run test:php       # wp-env を起動してから PHPUnit を実行
composer test          # vendor/bin/phpunit を直接実行（wp-env 起動済み前提）
```

### 翻訳ファイル生成
```bash
npm run i18n:pot       # languages/ystandard.pot を生成（wp i18n make-pot）
npm run i18n:blocks    # ja.po → ブロックエディター用 JSON 変換
```

### ローカル環境
```bash
npm run wpenv:start    # wp-env + Xdebug 起動
npm run wpenv:stop
npm run wpenv:destroy
```

## コーディング規約

### PHPCS
- `.phpcs.xml.dist`（`WordPress-Core` + `WordPress-Docs`）に従う
- ショート配列構文 `[]` は許可（`DisallowShortArraySyntax` を除外済み）
- `vendor/` `build/` `node_modules/` `library/` `tests/` は対象外
- 違反を見つけたら PHPCS を実行して確認する

### JS / TS
- ESLint v9 だが設定は `.eslintrc.js`（旧形式）、Prettier は `.prettierrc.js`
- `@wordpress/eslint-plugin` ベース。コード変更後は `npm run lint:js` を実行
- pre-commit で husky が動く（`.husky/`）

### フックプレフィックス
既存コードで **`ys_` と `ystd_` が混在**している。新規追加時は該当ファイル・近隣コードのプレフィックスに合わせる。勝手に統一しない。
- 多数派: `ys_`
- 初期化・ローダー系: `ystd_`（例: `ystd_inc_before_load` / `ystd_inc_after_load` / `ystd_inc_load_file`）

### 名前空間とクラス命名
- 一部のクラスは `namespace ystandard` を使用（例: `inc/template/class-template.php`, `inc/blocks/class-blocks.php`）
- 一部はグローバル名前空間で `class-ys-*` プレフィックス（例: `inc/toc/class-ys-widget-toc.php`）
- **既存ファイルの流儀に合わせる**。新しい方式を混ぜない

### CSS / TailwindCSS
- TailwindCSS クラスは全て `tw-` プレフィックス付き（`tailwind.config.js`）
- Tailwind の preflight は無効、`important: true`（クラスに `!important` 付与）
- 色・サイズ等は `theme.json` のプリセットを優先し、ハードコード値を避ける
- カスタムは CSS カスタムプロパティ経由で定義

### 翻訳
- 表示文字列はすべて `__()`, `_e()`, `esc_html__()` 等の翻訳関数を通す
- テキストドメインは `ystandard`
- 日本語がデフォルト言語だが、ハードコードせず翻訳可能な形で記述

## アーキテクチャ

### ディレクトリの意図
- `/inc/` — PHP ロジック。`inc/index.php` がローダーとなり各サブディレクトリの `index.php` を自動読み込み（`utils` / `config` / `option` を先に、`template-function` を最後に読み込む）。新規追加時はディレクトリ + `index.php` を作れば自動認識
- `/template-parts/` — テンプレート部品。カテゴリ別ディレクトリ（header/footer/archive 等）が基本、`parts/` は旧構造（v5 で整理予定）
- `/src/scripts/` — TypeScript ソース（エントリは `ystandard.ts`）。webpack でコンパイル
- `/src/styles/` — テーマ用 SCSS ソース
- `/src/block-styles/` — ブロックスタイル用 SCSS ソース（`core__*` 等ブロック単位）
- `/library/` — レガシー/外部由来コード（PHPCS 対象外）
- `/css/`, `/js/` — ビルド成果物（コミット対象）。`/temp/` はビルド中間生成物（git 管理外）

### CSS ビルドパイプライン（2系統）
- テーマ: `src/styles` → SCSS → `temp/css` → PostCSS（Tailwind + autoprefixer + cssnano）→ `css/`
- ブロックスタイル: `src/block-styles` → SCSS → `temp/css/block-styles` → PostCSS → `css/block-styles/`

### ブロックテーマではない
- `theme.json` は採用しているが `templates/*.html` は**存在しない**
- クラシックテーマのテンプレート階層（`single.php`, `archive.php` 等）で動作
- 新規に `templates/` 配下の HTML を追加しない

### カスタムブロック
- 独自のブロック登録は無し（`src/` 配下に `block.json` なし）
- ブロックスタイルとエディター拡張のみ

## ワークフロー

### コミット
- コミットメッセージは日本語、簡潔に
- Conventional Commits は未採用
- `/project:commit` スラッシュコマンドで現在の差分をコミット可能（`.claude/commands/commit.md` 参照）

### CI
- `.github/workflows/` に v3/v4 系のワークフロー（`create-release` / `dev-v3` / `dev-v4` / `release-v3` / `release-v4`）あり
- v5 用の workflow はまだ未整備

## 注意事項

- **IMPORTANT**: 出力する値は必ずエスケープする（`esc_html`, `esc_attr`, `esc_url`, `wp_kses` 等）。入力は `sanitize_*` でサニタイズし、フォーム送信・Ajax・REST では nonce 検証を省略しない
- **IMPORTANT**: `$wpdb` 直接クエリを書く場合は必ず `$wpdb->prepare()` を通す。標準関数（`WP_Query`, `get_posts`, `get_option` 等）で済むならそちらを優先する
- **IMPORTANT**: フックやクラスを追加する際、既存の `ys_` / `ystd_` / 名前空間有無の混在を統一しようとしない。該当箇所の周辺コードに合わせる
