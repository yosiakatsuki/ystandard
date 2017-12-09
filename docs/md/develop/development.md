# 開発メモ

## テスト
- Travis CIでのテストを行う
  - テストの勉強に結構時間かかるのでは？
  - まずは出来るところからテストも導入し、徐々に増やしていく
  - 30分かけても糸口が見つからなければ手動テストでv2公開まで乗り切る、その後のアップデートで順次追加



## フォルダ構成
ystandard
├ assets
  ├ images                    : テーマデフォルトの画像類
├ css
├ inc                         : 関数群、基本的には表示に必要なデータを作るだけ
├ js
├ Library                     : ライブラリ関連
  ├ font-awesome
├ page-template               : カスタムテンプレート類をまとめる
├ src
  ├ sass
  ├ js                        : ES2015? Coffee? 何で書くかは検討中
├ template-parts              : ページを構成するテンプレートをまとめる
│
├ 各テーマテンプレート
├ gulpfile.js,package.json等の開発用ファイル
│
├ user-custom-head.php        : 非AMPフォーマットのheadタグのユーザー拡張部分
├ user-custom-head-amp.php    : AMPフォーマットのheadタグのユーザー拡張部分
└ user-custom-append-body.php : 非AMPフォーマットの</body>直前のユーザー拡張部分
