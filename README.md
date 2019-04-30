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

- WordPress : 4.9以上
- PHP : 7.2以上

## スタイルシートについて

### ブレークポイント

- SP
  - 指定なし
- Tablet
  - `@media (min-width: 600px) {}`（テーマデフォルト）
- PC
  - `@media (min-width: 1024px) {}`（テーマデフォルト）

### 汎用的なスタイル

#### 装飾

- `.ys-box`
  - 囲みブロック用クラス

#### テキスト操作

- `.text--sm`
  - フォントサイズ少し小さく(0.8em)
- `.text--lg`
  - フォントサイズ少し大きく(1.2em)
- `.text--b`
  - 太字にする
- `.text-sub`
  - グレーにする
- `.text--center`
  - 中央揃え
- `.text--left`
  - 左揃え
- `.text--right`
  - 右揃え

#### ボタン

- `.btn`
  - ボタン用クラス
- `btn--lg`
  - ちょっと大きいボタン(※`.btn`と合わせて使う)
- `btn--block`
  - 全幅ボタン(※`.btn`と合わせて使う)


## カスタマイズ

### CSS,JSの読み込み

- 調整中

## Third-party resources

### Font Awesome

Font License: SIL OFL 1.1  
Code License: MIT License  
Source      : <https://fortawesome.github.io/Font-Awesome/>

### Theme Update Checker Library

License: GPL  
Source : <http://w-shadow.com/>


### \_decimal.scss

License: MIT License  
Source : <https://gist.github.com/terkel/4373420>

## 変更履歴

### v3.0.0

- v3.0.0-alpha-1
  - `template-parts`内のテンプレート構造をほぼ固めた
  - 不足機能多数だが、テンプレート構造のチャック用として案内
