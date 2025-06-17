# CLAUDE.md

このファイルは、このリポジトリでコードを扱う際にClaude Code (claude.ai/code) にガイダンスを提供します。

## 言語設定
**重要**: このプロジェクトでは日本語でのやり取りを行ってください。コメント、コミットメッセージ、説明はすべて日本語で記述してください。

## プロジェクト概要

yStandardは、WordPress 6.5+とPHP 7.4+を必要とするモダンなWordPressテーマ（v5.0.0）です。TypeScript、SCSS、Webpack、TailwindCSS、PostCSSなどのモダンビルドツールで構築されています。

## 開発コマンド

### アセット開発
```bash
npm run watch        # 開発用に全アセットを監視
npm run watch:css    # CSSのみ監視
npm run watch:script # JS/TSのみ監視
npm run build        # 本番用に全アセットをビルド
npm run clean        # ビルドディレクトリをクリーン
```

### コード品質
```bash
npm run lint         # 全コードをリント（PHP、JS、CSS）
npm run lint:php     # WordPress標準でPHPをリント
npm run lint:js      # JavaScript/TypeScriptをリント
npm run test:php     # PHPユニットテストを実行
composer test        # 代替PHPテストコマンド
```

### WordPress環境
```bash
npm run wpenv:start    # ローカルWordPress環境を開始
npm run wpenv:stop     # 環境を停止
npm run wpenv:destroy  # 環境をクリーンアップ
```

### 配布
```bash
npm run zip          # 配布パッケージを作成
```

## アーキテクチャ

### ディレクトリ構造
- `/src/` - アセットのソースコード
  - `/scripts/` - TypeScriptモジュール（管理画面、フロントエンド機能）
  - `/styles/` - foundation/components/utilitiesで整理されたSCSSファイル
- `/inc/` - 機能別に整理されたPHPクラス（Admin、Blocks、Customizer等）
- `/template-parts/` - テーマ構造用のテンプレートパートシステム
- `/dist/` - ビルド済みアセット（生成）

### PHPアーキテクチャ
- オートローダー付きオブジェクト指向（`class-ys-loader.php`）
- `/inc/`サブディレクトリのモジュラーコンポーネント構造
- 機能別に整理されたテンプレート関数
- WordPress コーディング規約を適用

### アセットパイプライン
- **TypeScript**: WordPress Scripts webpack設定でコンパイル
- **SCSS**: TailwindCSS（プレフィックス `tw-`）付きPostCSSパイプライン
- **CSS**: カスタムプロパティシステム、モジュラーローディング
- **JavaScript**: WordPress依存関係付きESモジュール

### ビルド設定
- `webpack.app.config.js` - @wordpress/scriptsを拡張したメインビルド設定
- `postcss.config.js` - TailwindCSS、autoprefixer、cssnanoでのCSS処理
- `tailwind.config.js` - プリフライト無効化でのカスタムユーティリティクラス
- `tsconfig.json` - 厳密なTypeScript設定

## 主要機能

### WordPress統合
- カスタムブロックスタイル付きフルブロックエディターサポート
- theme.json設定でFSE対応
- 広範囲なカスタマイザー統合
- WordPress 6.5+必須機能

### 開発ツール
- WordPress テスト環境付きPHPUnitテスト
- コードフォーマット用ESLint + Prettier
- WordPress標準でのPHP CodeSniffer
- @wordpress/envでのローカル開発

## 重要な注意事項

- テキストドメイン: `ystandard`
- 開発依存関係にPHP 8.0+が必要（Composer）
- TailwindCSSクラスは `tw-` でプレフィックス
- すべてのカスタムCSSはCSSカスタムプロパティを使用
- 日本語が主要言語だが翻訳対応済み