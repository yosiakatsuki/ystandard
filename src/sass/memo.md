## CSS設計


FLOCSSを参考にする

* Foundation
  + base
  + variable
  + mixin
  + function
* Layout
  + レイアウト関連
  + サイドバーあり条件下でのレイアウト調整なども含める
    * layout/has-sidebar/ \*.scss | component/\*.scss 
* Object
  * Component
    + 各コンポーネント
    + ./blocks/... ブロック関連のスタイル
  * Project
    + ./admin/... 管理画面関連のスタイル
  * Utility
    + テキストサイズ変更等
    + 基本はコンポーネントで考える


## CSS種類
* ystandard.css : インラインCSS読み込み用キーファイル（空ファイル）
* ystandard-light.css : モバイル・AMP用の最小構成
* ystandard-desktop.css : PC表示ようにメディアクエリなどを含める
* ystandard-has-sidebar.css : モバイル・サイドバー部分の表示あり構成
* ystandard-has-sidebar-desktop.css : PC表示ようにメディアクエリなどを含める・サイドバー部分の表示あり構成
