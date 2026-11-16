# コンテナ幅と入れ子の余白の調査

調査日：2026-10-08。標準テーマとの比較追記：2026-10-09。実装前の検討資料。

## 結論

最大幅と画面端の余白を分け、入れ子の各階層で左右余白を差し引かない設計にする。

WordPressコアが出力するレイアウトCSSを尊重し、テーマ側の過剰な指定を取り除く方針とする。入れ子のブロックに補正CSSを重ねて解決しない。

`.entry-content`自体や、その外側のラッパーへ今回の対策として最大幅を追加しない。幅制限は`.entry-content`の内側で行い、直下の全幅ブロックが画面いっぱいに背景を敷ける構造を保つ。外側への共通padding追加によって全幅を狭める方法も採用しない。

まず本文内の幅広ブロックへの適用範囲を見直す。サイト全体で使う`--ystd--container--size`の定義を一括変更する方法は、ヘッダーやフッターなどにも影響するため採用しない。

## 確認した原因

`src/styles/foundation/custom-properties/_container.scss:7`では、次の式を定義している。

```css
--ystd--container--size: min(calc(100% - var(--ystd--container--gutter) * 2), var(--ystd--container--width));
```

初期値のgutterは16px。この式の100%は利用先の包含ブロックの幅を基準にするため、親の幅から左右32pxを差し引く。

`src/styles/components/contents/_entry-content.scss:20`の`.alignwide`指定には直下を示す`>`がなく、本文内のすべての階層に適用される。幅広ブロックを入れ子にすると、それぞれの階層で幅が32pxずつ減る。

`--ystd--content--size`にも同じ引き算がある。こちらの本文への指定は直下限定だが、本文背景付きラッパーなど、外側でもサイズ制限する構成は別途確認が必要。

## ブラウザでの確認

agent-browserで、リポジトリの既存`css/ystandard.css`を読み込んだ一時HTMLを表示した。本文内に幅広グループを3階層配置し、実寸とスクリーンショットを確認した。

| 画面幅 | 条件 | 1階層目の幅 | 2階層目の幅 | 3階層目の幅 |
| --- | --- | --- | --- | --- |
| 390px | 現行CSS | 358px | 326px | 294px |
| 390px | 内側の幅指定を一時変更 | 358px | 358px | 358px |
| 1440px | 現行CSS | 1200px | 1168px | 1136px |
| 1440px | 内側の幅指定を一時変更 | 1200px | 1200px | 1200px |

390pxの現行表示では、画面左端からの位置が16px、32px、48pxと増加した。試案では各階層が16pxになった。

試案では、本文直下より内側の`.alignwide`だけに`max-width: min(100%, var(--ystd--container--width))`を一時適用した。これは原因を切り分ける実験であり、そのまま採用する完成CSSではない。

この一時指定も入れ子の幅をテーマ側で決めるため、コアCSSを尊重する実装案としては採用しない。以下の対処方針を実装・検証した結果ではない。

実際に問題が出ているページのURLは未取得。WordPressが生成するレイアウトCSS、プラグイン、投稿内の個別設定を含む実ページの確認は未実施。

## 推奨する対処

- `.entry-content`は幅を制限しないラッパーとし、その直下の通常幅・幅広ブロックに必要な幅制限を扱う。まず`_entry-content.scss`の幅広指定を直下限定にし、入れ子に届くテーマ独自の指定を取り除く。
- `.entry-content > .alignfull`には、今回の対策として最大幅や共通の左右余白を付けない。全幅の背景と、その内側の本文幅を別々に扱う。
- 入れ子の幅・配置・余白は、WordPressのレイアウトCSSとブロックの設定に任せる。`.is-layout-constrained`や`.has-global-padding`をテーマ側で一律にリセットしない。`!important`や詳細度を上げた補正CSSを追加しない。
- 全幅グループの内側を通常幅に収めたい場合は、コアの「インナーブロックにコンテンツ幅を使用」などのレイアウト設定で実現する。制約のないレイアウトを選んだ場合まで、テーマ側で内側の最大幅を強制しない。
- 背景付きグループの内側余白や、ユーザーがブロックに設定したpaddingは意図的な余白として保持する。

`theme.json`では`useRootPaddingAwareAlignments: true`と左右gutterをすでに設定している。WordPressの`.has-global-padding`も余白を作るため、テーマの幅計算との二重適用を実ページで確認する。標準機構との関係は[WordPress公式資料](https://developer.wordpress.org/themes/global-settings-and-styles/settings/use-root-padding-aware-alignments/)を参照。

最大幅の設定元は現行のカスタマイザーとGlobal Stylesを維持する。`ys_content_width`は`contentSize`、`ys_container_width`は`wideSize`に対応しており、今回の原因は設定値そのものではない。

## 関連箇所と実装時の確認

| 対象 | 確認内容 |
| --- | --- |
| 通常・幅広・全幅グループの入れ子 | 階層だけを理由に左右余白が増えない。コアのレイアウト切り替え、個別幅、paddingが有効 |
| 全幅背景と内側の本文 | 1カラムの全幅表示で背景が画面端まで届き、内側の幅はブロック設定に従う。`.entry-content`や祖先に新たな最大幅がない |
| メディアとテキスト | `src/block-styles/core__media-text/media-text.scss:60`の全幅・スマホ時の幅指定も同じ変数を使用。入れ子と背景色ありを確認 |
| 本文背景あり・なし | 既存の背景付きarticleには幅制限があるため、既存仕様と今回追加しない外側の幅制限を区別し、全幅表示の要件との整合を確認 |
| 1カラム・2カラム・ワイド表示 | 既存の本文上限、サイドバー幅、カラム間隔を維持 |
| ヘッダー・フッター・ウィジェット | 共通変数を変更する場合に影響する。本文修正で巻き込まない |
| 投稿エディター | 公開画面と幅・余白の扱いが一致するか確認 |

## WordPress標準テーマとの比較

2026-10-09にWordPress公式のwordpress-developリポジトリのtrunkと公式資料を参照した。以下はソースに基づく確認であり、標準テーマを動かしたブラウザでの実測ではない。適用時には対象サイトのWordPressバージョンが出力するCSSを確認する。

### Twenty Twenty-Five

ブロックテーマのTwenty Twenty-Fiveは、固定ページテンプレートでPost Contentブロックを`align: full`と`layout.type: constrained`の組み合わせで配置している。本文の器を全幅にし、その子の通常幅・幅広を制限する構造。

標準設定は`contentSize: 645px`、`wideSize: 1340px`。これらはテーマの設定値であり、yStandardに数値を移植する必要はない。

コアのconstrainedレイアウトは通常の直下要素にcontentSize、直下の`.alignwide`にwideSizeを適用する。幅計算で毎回共通gutterを差し引く方式ではない。`.alignfull`は通常幅の制限から外れるが、入れ子にしたときの「全幅」は親のレイアウト内での全幅であり、どの階層からでも画面端へ広がる意味ではない。

`useRootPaddingAwareAlignments`で左右余白を扱い、全幅の子には負のmarginを適用して広げる。通常の入れ子では共通paddingを重複させない規則がある。全幅やflowレイアウトなどには例外があり、ユーザー指定のpaddingまで一律に消す仕組みではない。

参照：[ページテンプレート](https://github.com/WordPress/wordpress-develop/blob/trunk/src/wp-content/themes/twentytwentyfive/templates/page.html)、[theme.json](https://github.com/WordPress/wordpress-develop/blob/trunk/src/wp-content/themes/twentytwentyfive/theme.json)、[コアのレイアウト処理](https://github.com/WordPress/wordpress-develop/blob/trunk/src/wp-includes/block-supports/layout.php)、[共通paddingの生成処理](https://developer.wordpress.org/reference/classes/wp_theme_json/get_root_layout_rules/)。

### Twenty Twenty-One

クラシックテーマのTwenty Twenty-Oneは、`.entry-content > *`を基礎に、テーマ自身のCSSで通常幅・幅広・全幅を扱う。通常幅と幅広の計算にビューポート幅を利用しており、今回の親幅から毎階層32pxを引く式とは異なる。旧来の内側ラッパーやブロック別の補正も含むため、CSS全体を今のコア標準として移植する方針にはしない。

参照：[Twenty Twenty-OneのCSS](https://github.com/WordPress/wordpress-develop/blob/trunk/src/wp-content/themes/twentytwentyone/style.css)。

### yStandardへの適用方針

今回の幅の責務を考えるうえでは、エディターの種類に加え、PHPテンプレートを使うクラシックテーマか、Post Contentブロックを使うブロックテーマかを区別する。クラシックテーマでもブロックエディターとtheme.jsonを利用できる。

yStandardの本文はPHPが出力する`.entry-content`であり、Post Contentブロックではない。theme.jsonを設定するだけでこのラッパーの構造・クラスがブロックテーマと同じになるわけではない。

- 目指す挙動はTwenty Twenty-Fiveと同じく、本文の器を全幅に保ち、子の通常幅・幅広・全幅を分けるものとする。
- ブロック内のレイアウトはコアに任せる。PHPが出力する本文の入口で不足する幅・余白処理だけを補う。
- 前述の`.alignwide`の直下限定は修正の一部。直下のグループにコアの共通paddingとテーマの幅引き算が重ならないことまで確認して完成とする。
- コアのpaddingと全幅用の負のmarginをセットで使い、全幅背景を維持できる方式は検討対象とする。外側に最大幅を付ける方法や、paddingだけを追加して全幅を狭める方法は採用しない。
- クラシックエディター由来の段落・見出しなども、本文直下の通常幅で収める。入れ子のブロック用CSSを旧本文に強制しない。

参照：[theme.jsonはクラシックテーマでも利用可能](https://developer.wordpress.org/themes/global-settings-and-styles/introduction-to-theme-json/)。

今回変更したのはこの調査メモのみ。テーマの実装、設定、投稿データは変更していない。実装変更がないためビルド・PHPテストは実施していない。
