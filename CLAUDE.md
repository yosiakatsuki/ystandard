# yStandard

WordPress クラシックテーマ（GPL-2.0+）。無料配布のカスタマイズ志向テーマ。

- テキストドメイン: `ystandard`
- PHP 名前空間: `ystandard`（サブ名前空間: `ystandard\helper`）
- フックプレフィックス: `ys_`
- 必須 PHP: 7.4+（composer.json は 8.0+）
- 必須 WP: 6.1+

## 開発コマンド

### ビルド

```bash
npm run build          # CSS(Sass→PostCSS)・JS(Babel) の全ビルド
npm run watch          # 開発用ウォッチ（CSS・JS）
npm run zip            # 配布用 zip 作成
```

### Lint

```bash
npm run lint           # PHP(PHPCS) を実行
composer phpcs         # PHPCS 直接実行（WordPress-Core + WordPress-Docs）
```

### テスト

```bash
npm run test           # wp-env 上で PHPUnit 実行（wp-env の起動を含む）
composer test          # PHPUnit 直接実行（ローカル WP テスト環境が必要）
```

テストファイルは `tests/` 配下、`test-` プレフィックス付き PHP ファイル。

### wp-env

```bash
npm run wpenv:start    # Xdebug 有効で起動
npm run wpenv:stop     # 停止
```

## コーディング規約

- PHPCS ルール: WordPress-Core + WordPress-Docs（`.phpcs.xml.dist` 参照）
- 短縮配列構文 `[]` を許可（`DisallowShortArraySyntax` を除外済み）
- JS/CSS: `@wordpress/eslint-plugin` + `@wordpress/prettier-config` に準拠
- CSS は Sass（`src/sass/`, `src/block-styles/`）で記述し、PostCSS で後処理

## アーキテクチャ

- `inc/`: PHP クラス群。機能ごとにサブディレクトリ（`header/`, `seo/`, `blocks/` 等）
- `inc/class-ys-loader.php`: テーマの起点。`functions.php` からこれだけ読み込む
- `inc/helper/`: 汎用ヘルパー（`ystandard\helper` 名前空間）
- `src/sass/` → `css/`: テーマ CSS のビルド元
- `src/block-styles/` → `css/block-styles/`: ブロックスタイル CSS のビルド元
- `src/js/` → `js/`: Babel でトランスパイル
- `template-parts/`: テンプレートパーツ群
- `library/`: 外部ライブラリ（PHPCS・ビルド対象外）

## ワークフロー

- メインブランチ: `master`
- 開発ブランチ: `develop-v4`（push で CI 実行 → PHP 構文チェック → PHPCS → PHPUnit → 開発版デプロイ）
- リリースブランチ: `release-v4`（push で本番デプロイ）
- CI: PHP 7.2〜8.2 での構文チェック、PHP 8.1 での PHPCS・テスト

## 注意事項

- IMPORTANT: ユーザー入力・出力には必ずサニタイズ・エスケープを行い、フォーム処理には nonce チェックを入れること
- IMPORTANT: `$wpdb` 直接クエリを使う場合は必ず `$wpdb->prepare()` を通すこと。既存の WP 関数で代替できないか先に検討する
- IMPORTANT: 翻訳関数（`__()`, `esc_html__()` 等）を使い、テキストドメイン `ystandard` を指定すること。ハードコードされた日本語文字列を直接出力しない
